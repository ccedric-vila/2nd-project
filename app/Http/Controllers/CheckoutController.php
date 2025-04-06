<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    // Handle POST request from Buy Now button
    public function handleSingleCheckout(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,product_id'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        return redirect()->route('checkout.single', ['product' => $product->product_id]);
    }

    // Show single product checkout page
    public function single(Product $product)
    {
        $user = Auth::user();
        
        return view('checkout.single', [
            'product' => $product->load('images'),
            'user' => $user
        ]);
    }

    // Handle AJAX quantity update
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Check stock availability
        if ($request->quantity > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Only '.$product->stock.' items available',
                'max_quantity' => $product->stock
            ], 422);
        }

        // Calculate prices
        $unitPrice = $product->sell_price;
        $totalPrice = $unitPrice * $request->quantity;

        return response()->json([
            'success' => true,
            'quantity' => $request->quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
            'formatted_unit_price' => '$'.number_format($unitPrice, 2),
            'formatted_total' => '$'.number_format($totalPrice, 2),
            'available_stock' => $product->stock,
            'message' => 'Quantity updated successfully'
        ]);
    }

    // Process the checkout and show success
    public function process(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer|min:1',
            'size' => 'required|in:XS,S,M,L,XL,XXL'
        ]);

        try {
            DB::beginTransaction();

            $product = Product::where('product_id', $validated['product_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

            // Check stock availability (but don't deduct yet)
            if ($product->stock < $validated['quantity']) {
                DB::rollBack();
                return back()->withErrors(['quantity' => 'Only '.$product->stock.' items available'])->withInput();
            }

            // Create the order with 'pending' status
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $product->sell_price * $validated['quantity'],
                'status' => 'pending' // Admin will change this to 'accepted' when approved
            ]);

            // Create order line item
            OrderLine::create([
                'order_id' => $order->id,
                'product_id' => $product->product_id,
                'quantity' => $validated['quantity'],
                'sell_price' => $product->sell_price,
                'size' => $validated['size']
            ]);

            // Note: We're NOT deducting stock here anymore
            // Stock will be deducted when admin accepts the order

            DB::commit();

            // Redirect to success page
            return redirect()->route('checkout.success', ['order_id' => $order->id])
                            ->with('success', 'Order placed successfully! Your order ID is #'.$order->id.'. It will be processed after payment confirmation.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed: '.$e->getMessage());
            return back()->with('error', 'An error occurred during checkout. Please try again.');
        }
    }

    public function success($order_id)
    {
        $order = Order::with([
            'orderLines.product.images',
            'orderLines.product.supplier',
            'user'
        ])->findOrFail($order_id);
        
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Format order data for display
        $orderDetails = [
            'order_id' => $order->id,
            'order_date' => $order->created_at->format('F j, Y \a\t g:i A'),
            'status' => ucfirst($order->status),
            'total_amount' => '$'.number_format($order->total_amount, 2),
            'payment_status' => 'Pending', // You might want to add payment status to your orders table
            'shipping_address' => optional($order->user)->address ?? 'Not specified',
            'contact_number' => optional($order->user)->contact_number ?? 'Not specified',
            'items' => $order->orderLines->map(function ($line) {
                return [
                    'product_name' => $line->product->product_name,
                    'size' => $line->size,
                    'quantity' => $line->quantity,
                    'unit_price' => '$'.number_format($line->sell_price, 2),
                    'total_price' => '$'.number_format($line->sell_price * $line->quantity, 2),
                    'image' => $line->product->images->first()->image_path ?? null,
                    'brand' => $line->product->supplier->brand_name ?? 'Unknown Brand'
                ];
            })
        ];

        return view('checkout.success', [
            'order' => $order,
            'product' => $order->orderLines->first()->product,
            'orderDetails' => $orderDetails,
            'user' => $order->user
        ]);
    }
}