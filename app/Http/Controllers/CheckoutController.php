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
            'product_id' => 'required|exists:product,product_id'  // Changed from 'product' to 'products'
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
            'user' => $user  // Pass the whole user object for better access
        ]);
    }

    // Handle AJAX quantity update
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,product_id',  // Changed from 'product' to 'products'
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
            'formatted_unit_price' => number_format($unitPrice, 2),
            'formatted_total' => number_format($totalPrice, 2),
            'available_stock' => $product->stock
        ]);
    }

    // Process the checkout and show success
    public function process(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:product,product_id',  // Changed from 'product' to 'products'
            'quantity' => 'required|integer|min:1',
            'size' => 'required|in:XS,S,M,L,XL,XXL'
        ]);

        try {
            DB::beginTransaction();

            $product = Product::where('product_id', $validated['product_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

            // Final stock verification
            if ($product->stock < $validated['quantity']) {
                DB::rollBack();
                return back()->withErrors(['quantity' => 'Only '.$product->stock.' items available'])->withInput();
            }

            // Create the order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $product->sell_price * $validated['quantity'],
                'status' => 'pending'  // Changed from default 'pending' to match your schema
            ]);

            // Create order line item
            OrderLine::create([
                'order_id' => $order->id,
                'product_id' => $product->product_id,
                'quantity' => $validated['quantity'],
                'sell_price' => $product->sell_price
            ]);

            // Update product stock
            $product->decrement('stock', $validated['quantity']);

            DB::commit();

            // Redirect to success page with order ID
            return redirect()->route('order.success', ['order' => $order->id])
                            ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed: '.$e->getMessage());
            return back()->with('error', 'An error occurred during checkout. Please try again.');
        }
    }
}