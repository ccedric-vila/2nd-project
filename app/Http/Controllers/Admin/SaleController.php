<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
{
    $request->validate([
        'search' => 'nullable|string|max:255',
        'month' => 'nullable|integer|between:0,12',
        'year' => 'nullable|integer',
    ]);

    $query = Sale::with([
            'order:id,id as order_number', // Using id as order_number if no order_number column exists
            'product:product_id,product_name',
            'user:id,name'
        ])
        ->latest('sale_date');

    // Search functionality
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->whereHas('product', function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%");
            })
            ->orWhereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orWhere('order_id', 'like', "%{$search}%"); // Searching by order ID instead
        });
    }

    // Rest of the controller remains the same...

        // Month filter
        if ($request->filled('month') && $request->month > 0) {
            $query->whereMonth('sale_date', $request->month);
        }

        // Year filter
        if ($request->filled('year')) {
            $query->whereYear('sale_date', $request->year);
        }

        $sales = $query->paginate(25);

        // Get distinct years for filter dropdown
        $years = Sale::selectRaw('YEAR(sale_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('admin.sales.index', compact('sales', 'years'));
    }
}