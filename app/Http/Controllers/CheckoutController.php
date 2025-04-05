<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderLine;
use Illuminate\Support\Facades\Auth;

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
        
        // Ensure we have default values if fields are null
        $contact_number = $user->contact_number ?? '';
        $address = $user->address ?? '';
        
        return view('checkout.single', [
            'product' => $product->load('images'),
            'contact_number' => $contact_number,
            'address' => $address
        ]);
    }

    // Process the checkout
    public function process(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer|min:1',
            'size' => 'required|in:XS,S,M,L,XL,XXL',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:255'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Verify stock is available
        if ($product->stock < $request->quantity) {
            return back()->withErrors(['quantity' => 'Insufficient stock available'])->withInput();
        }

        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $product->sell_price * $request->quantity,
            'status' => 'pending',
            'shipping_address' => $request->address,
            'contact_number' => $request->contact_number,
            'customer_name' => Auth::user()->name
        ]);

        // Create order line with size information
        $orderLine = OrderLine::create([
            'order_id' => $order->id,
            'product_id' => $product->product_id,
            'quantity' => $request->quantity,
            'unit_price' => $product->sell_price,
            'total_price' => $product->sell_price * $request->quantity,
            'size' => $request->size
        ]);

        // Update product stock
        $product->decrement('stock', $request->quantity);

        // Update user's contact information
        $user = Auth::user();
        $user->update([
            'contact_number' => $request->contact_number,
            'address' => $request->address
        ]);

        return redirect()->route('checkout.success', ['order_id' => $order->id]);
    }

    // Show success page
    public function success(Request $request)
    {
        $order = Order::with(['orderLines.product'])
                   ->findOrFail($request->order_id);

        // Verify the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('checkout.success', [
            'order' => $order,
            'status_message' => 'Your order is pending admin approval'
        ]);
    }
}