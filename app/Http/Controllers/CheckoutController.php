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
        return view('checkout.single', [
            'product' => $product->load('images'),
            'user' => Auth::user()
        ]);
    }

    // Process the checkout
    public function process(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer|min:1',
            'size' => 'required|in:XS,S,M,L,XL,XXL',
            'contact_number' => 'required|string|max:255',
            'address' => 'required|string|max:255'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Verify stock is available
        if ($product->stock < $request->quantity) {
            return back()->withErrors(['quantity' => 'Insufficient stock available'])->withInput();
        }

        // Create order
        $order = new Order();
        $order->user_id = Auth::id();
        $order->total_amount = $product->sell_price * $request->quantity;
        $order->status = 'pending'; // Requires admin approval
        $order->save();

        // Create order line with size information
        $orderLine = new OrderLine();
        $orderLine->order_id = $order->id;
        $orderLine->product_id = $product->product_id;
        $orderLine->quantity = $request->quantity;
        $orderLine->sell_price = $product->sell_price;
        // Add size to order line (assuming you'll add this column)
        $orderLine->size = $request->size;
        $orderLine->save();

        // Update product stock
        $product->stock -= $request->quantity;
        $product->save();

        // Update user's contact information if changed
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