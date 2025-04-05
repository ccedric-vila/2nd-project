<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Display all orders (Admin)
    public function index()
        {
            $orders = Order::with(['user', 'orderLines.product'])
                        ->latest()
                        ->paginate(10); // Changed from get() to paginate(10)
            
            return view('admin.orders.index', compact('orders'));
        }

    // Display user's order history
    public function history()
    {
        $orders = auth()->user()->orders()
                    ->with(['orderLines.product'])
                    ->where('status', '!=', Order::STATUS_CANCELLED)
                    ->latest()
                    ->paginate(10);

        return view('orders.history', compact('orders'));
    }

    // Show order details
    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    
        $order->load(['user', 'orderLines.product']);
        return view('orders.show', compact('order'));
    }

    // Accept order (Admin) - Creates sales records
    public function accept($id)
{
    DB::beginTransaction();
    
    try {
        $order = Order::with('orderLines.product')->findOrFail($id);

        if ($order->status !== Order::STATUS_PENDING) {
            return redirect()->back()->with('error', 'Only pending orders can be accepted.');
        }
        
        // Update order status
        $order->update(['status' => Order::STATUS_ACCEPTED]);
        
        // Update stock and create sales records
        foreach ($order->orderLines as $orderLine) {
            $product = $orderLine->product;
            
            // Check stock
            if ($product->stock < $orderLine->quantity) {
                DB::rollBack();
                return redirect()->back()->with('error', "Insufficient stock for: {$product->name}");
            }
            
            // Decrease stock
            $product->decrement('stock', $orderLine->quantity);
            
            // Create sale record (removed total_price as it's auto-generated)
            Sale::create([
                'order_id' => $order->id,
                'order_line_id' => $orderLine->id,
                'product_id' => $orderLine->product_id,
                'user_id' => $order->user_id,
                'quantity' => $orderLine->quantity,
                'unit_price' => $orderLine->sell_price,
                'sale_date' => now()->toDateString()
            ]);
        }
        
        DB::commit();
        return redirect()->route('admin.orders.index')->with('success', 'Order accepted and sales recorded!');
        
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}
    // Cancel order (Admin)
    public function cancel($id)
    {
        $order = Order::findOrFail($id);
    
        if ($order->status === Order::STATUS_PENDING) {
            $order->update(['status' => Order::STATUS_CANCELLED]);
            return redirect()->back()->with('success', 'Order cancelled!');
        }
    
        return redirect()->back()->with('error', 'Only pending orders can be cancelled.');
    }

    // Deliver order (Admin)
    public function deliver($id)
    {
        $order = Order::findOrFail($id);
            
        if ($order->status !== Order::STATUS_ACCEPTED) {
            return redirect()->back()->with('error', 'Only accepted orders can be delivered.');
        }
        
        $order->update([
            'status' => Order::STATUS_DELIVERED,
            'delivered_at' => now()
        ]);
        
        return redirect()->route('admin.orders.index')->with('success', 'Order marked as delivered!');
    }
}