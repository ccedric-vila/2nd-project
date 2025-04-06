<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    // Add a product to the cart
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:product,product_id'
        ]);

        $productId = $request->input('product_id');
        $userId = auth()->id();

        // Find product by product_id only (since that's your primary key)
        $product = Product::where('product_id', $productId)->first();

        if (!$product) {
            return back()->with('error', 'Product not found.');
        }

        // Check if product is already in cart
        $cartItem = Cart::where('user_id', $userId)
                      ->where('product_id', $productId)
                      ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    // View the cart items
    public function index()
    {
        $cartItems = Cart::with('product') // Make sure this relationship is defined in Cart model
                       ->where('user_id', auth()->id())
                       ->get();
        
        return view('cart.index', compact('cartItems'));
    }
    
    // Remove a product from the cart
    public function removeFromCart($id)
    {
        $cartItem = Cart::where('id', $id)
                      ->where('user_id', auth()->id())
                      ->first();

        if ($cartItem) {
            $cartItem->delete();
            return redirect()->route('cart.index')->with('success', 'Product removed from cart.');
        }

        return redirect()->route('cart.index')->with('error', 'Product not found in cart.');
    }

    // Update cart item quantity
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('id', $id)
                      ->where('user_id', auth()->id())
                      ->firstOrFail();

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated successfully.');
    }

    public function checkout(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $cartItems = Cart::with('product')
                           ->where('user_id', auth()->id())
                           ->get();
        
            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Your cart is empty.');
            }
        
            $total = $cartItems->sum(function($item) {
                return $item->quantity * $item->product->sell_price;
            });
        
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_amount' => $total,
                'status' => 'pending',
            ]);
        
            foreach ($cartItems as $item) {
                OrderLine::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'sell_price' => $item->product->sell_price,
                ]);
            }
        
            Cart::where('user_id', auth()->id())->delete();
            
            DB::commit();
            
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
                    'size' => $line->size ?? 'N/A',
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
            'orderLines' => $order->orderLines,
            'orderDetails' => $orderDetails,
            'user' => $order->user
        ]);
    }
}