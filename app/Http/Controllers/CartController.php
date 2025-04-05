<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderLine;
use Illuminate\Http\Request;

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
        $cartItems = Cart::with('product')
                       ->where('user_id', auth()->id())
                       ->get();
    
        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }
    
        $total = $cartItems->sum(function($item) {
            return $item->quantity * $item->product->sell_price; // Changed to sell_price
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
                'sell_price' => $item->product->sell_price, // Changed to sell_price
            ]);
        }
    
        Cart::where('user_id', auth()->id())->delete();
    
        return redirect()->route('cart.index')->with('success', 'Order placed successfully!');
    }
}