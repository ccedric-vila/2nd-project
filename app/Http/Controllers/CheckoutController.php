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
            'product_id' => 'required|exists:product,product_id',
            'size' => 'required|in:XS,S,M,L,XL,XXL',
            'quantity' => 'sometimes|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        return redirect()->route('checkout.single', [
            'product' => $product->product_id,
            'size' => $request->size,
            'quantity' => $request->quantity ?? 1
        ]);
    }

    // Show single product checkout page
    public function single(Product $product, Request $request)
    {
        $request->validate([
            'size' => 'required|in:XS,S,M,L,XL,XXL',
            'quantity' => 'sometimes|integer|min:1|max:10'
        ]);

        $user = Auth::user();
        $quantity = $request->quantity ?? 1;
        
        // Check product availability
        if ($product->stock < $quantity) {
            return back()->with('error', "Only {$product->stock} items available in stock");
        }

        return view('checkout.single', [
            'product' => $product->load('images'),
            'contact_number' => $user->contact_number ?? '',
            'address' => $user->address ?? '',
            'selected_size' => $request->size,
            'quantity' => $quantity,
            'total_price' => $product->sell_price * $quantity
        ]);
    }

    // Process the checkout and show success
    public function process(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer|min:1|max:10',
            'size' => 'required|in:XS,S,M,L,XL,XXL',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'payment_method' => 'required|in:cod,credit_card,paypal'
        ]);

        try {
            DB::beginTransaction();

            $product = Product::where('product_id', $validated['product_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

            // Stock availability check
            if ($product->stock < $validated['quantity']) {
                DB::rollBack();
                return back()
                    ->withErrors(['quantity' => 'Only '.$product->stock.' items available'])
                    ->withInput();
            }

            // Create the order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $product->sell_price * $validated['quantity'],
                'status' => 'pending',
                'shipping_address' => $validated['address'],
                'contact_number' => $validated['contact_number'],
                'customer_name' => Auth::user()->name,
                'size' => $validated['size'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_method'] === 'cod' ? 'pending' : 'processing'
            ]);

            // Create order line item
            OrderLine::create([
                'order_id' => $order->id,
                'product_id' => $product->product_id,
                'quantity' => $validated['quantity'],
                'sell_price' => $product->sell_price,
                'total_price' => $product->sell_price * $validated['quantity'],
                'size' => $validated['size']
            ]);

            // Update user contact information
            Auth::user()->update([
                'contact_number' => $validated['contact_number'],
                'address' => $validated['address']
            ]);

            DB::commit();

            // Redirect to order confirmation page
            return redirect()->route('checkout.success', $order->id)
                ->with('order', $order);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed: '.$e->getMessage(), [
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id'] ?? null,
                'error' => $e->getTraceAsString()
            ]);
            
            return back()
                ->with('error', 'An error occurred during checkout. Please try again.')
                ->withInput();
        }
    }

    // Show order success page
    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order');
        }

        $order->load('orderLines.product');
        
        return view('checkout.success', [
            'order' => $order,
            'product' => $order->orderLines->first()->product
        ]);
    }
}