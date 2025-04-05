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
            'contact_number' => $user->contact_number ?? '',
            'address' => $user->address ?? ''
        ]);
    }

    // Process the checkout and show success
    public function process(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer|min:1',
            'size' => 'required|in:XS,S,M,L,XL,XXL',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            $product = Product::where('product_id', $validated['product_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

            // Stock check remains to prevent overselling at checkout
            if ($product->stock < $validated['quantity']) {
                DB::rollBack();
                return back()->withErrors(['quantity' => 'Only '.$product->stock.' items available'])->withInput();
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $product->sell_price * $validated['quantity'],
                'status' => 'pending',
                'shipping_address' => $validated['address'],
                'contact_number' => $validated['contact_number'],
                'customer_name' => Auth::user()->name,
                'size' => $validated['size']
            ]);

            OrderLine::create([
                'order_id' => $order->id,
                'product_id' => $product->product_id,
                'quantity' => $validated['quantity'],
                'sell_price' => $product->sell_price,
                'total_price' => $product->sell_price * $validated['quantity'],
                'size' => $validated['size']
            ]);

            // Removed stock deduction here - will be handled when admin accepts order

            Auth::user()->update([
                'contact_number' => $validated['contact_number'],
                'address' => $validated['address']
            ]);

            DB::commit();

            return back()->with([
                'success' => 'Order placed successfully! Your order ID is: '.$order->id,
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed: '.$e->getMessage());
            return back()->with('error', 'An error occurred during checkout. Please try again.');
        }
    }
}