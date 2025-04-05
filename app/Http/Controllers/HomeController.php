<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    const PAGINATION_LENGTH = 12;

    public function index(): View
    {
        $products = Product::with(['supplier', 'images', 'reviews.user'])
            ->latest()
            ->paginate(self::PAGINATION_LENGTH);

        return view('home', compact('products'));
    }

    public function search(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255'
        ]);

        $searchTerm = $validated['search'] ?? '';

        if(empty(trim($searchTerm))) {
            return redirect()->route('home');
        }

        $products = Product::with(['supplier', 'images', 'reviews.user'])
            ->where(function($query) use ($searchTerm) {
                $query->where('product_name', 'like', "%{$searchTerm}%") // Fixed column name
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            })
            ->latest()
            ->paginate(self::PAGINATION_LENGTH);

        return view('home', [
            'products' => $products,
            'searchTerm' => $searchTerm
        ]);
    }
}