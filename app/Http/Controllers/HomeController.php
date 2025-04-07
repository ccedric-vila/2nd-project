<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Supplier; // Add this line
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    const PAGINATION_LENGTH = 12;

    public function index(): View
    {
        $suppliers = Supplier::select('supplier_id', 'brand_name')->get(); // Add this line
        
        $products = Product::with(['supplier', 'images', 'reviews.user'])
            ->when(request('search'), function($query) {
                $searchTerm = request('search');
                $query->where(function($q) use ($searchTerm) {
                    $q->where('product_name', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%")
                      ->orWhereHas('supplier', function($q) use ($searchTerm) {
                          $q->where('brand_name', 'like', "%{$searchTerm}%");
                      });
                });
            })
            ->when(request('price_range'), function($query) {
                $range = explode('-', request('price_range'));
                if (count($range) == 2) {
                    $min = (float)trim($range[0]);
                    $max = (float)trim($range[1]);
                    $query->whereBetween('sell_price', [$min, $max]);
                }
            })
            ->when(request('size'), function($query) {
                $query->where('size', request('size'));
            })
            ->when(request('category'), function($query) {
                $query->where('category', request('category'));
            })
            ->when(request('types'), function($query) {
                $query->where('types', request('types'));
            })
            ->when(request('brand'), function($query) { // Add this filter
                $query->where('supplier_id', request('brand'));
            })
            ->when(request('sort') == 'price_asc', function($query) {
                $query->orderBy('sell_price', 'asc');
            })
            ->when(request('sort') == 'price_desc', function($query) {
                $query->orderBy('sell_price', 'desc');
            })
            ->when(!request('sort'), function($query) {
                $query->latest();
            })
            ->paginate(self::PAGINATION_LENGTH);

        return view('home', [
            'products' => $products,
            'suppliers' => $suppliers // Add this line
        ]);
    }

    public function search(Request $request): View|RedirectResponse
    {
        $suppliers = Supplier::select('supplier_id', 'brand_name')->get(); // Add this line
        
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'price_range' => 'nullable|string|regex:/^\d+-\d+$/',
            'size' => 'nullable|string',
            'category' => 'nullable|string',
            'types' => 'nullable|string',
            'brand' => 'nullable|exists:supplier,supplier_id' // Add this validation
        ]);

        $searchTerm = $validated['search'] ?? '';

        if(empty(trim($searchTerm)) && !$request->hasAny(['price_range', 'size', 'category', 'types', 'brand'])) {
            return redirect()->route('home');
        }

        $products = Product::with(['supplier', 'images', 'reviews.user'])
            ->when($searchTerm, function($query) use ($searchTerm) {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('product_name', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%")
                      ->orWhereHas('supplier', function($q) use ($searchTerm) {
                          $q->where('brand_name', 'like', "%{$searchTerm}%");
                      });
                });
            })
            ->when($validated['price_range'] ?? null, function($query) use ($validated) {
                $range = explode('-', $validated['price_range']);
                if (count($range) == 2) {
                    $min = (float)trim($range[0]);
                    $max = (float)trim($range[1]);
                    $query->whereBetween('sell_price', [$min, $max]);
                }
            })
            ->when($validated['size'] ?? null, function($query) use ($validated) {
                $query->where('size', $validated['size']);
            })
            ->when($validated['category'] ?? null, function($query) use ($validated) {
                $query->where('category', $validated['category']);
            })
            ->when($validated['types'] ?? null, function($query) use ($validated) {
                $query->where('types', $validated['types']);
            })
            ->when($validated['brand'] ?? null, function($query) use ($validated) { // Add this filter
                $query->where('supplier_id', $validated['brand']);
            })
            ->when(request('sort') == 'price_asc', function($query) {
                $query->orderBy('sell_price', 'asc');
            })
            ->when(request('sort') == 'price_desc', function($query) {
                $query->orderBy('sell_price', 'desc');
            })
            ->when(!request('sort'), function($query) {
                $query->latest();
            })
            ->paginate(self::PAGINATION_LENGTH);

        return view('home', [
            'products' => $products,
            'searchTerm' => $searchTerm,
            'suppliers' => $suppliers // Add this line
        ]);
    }

    /**
     * Apply price range filter to query
     */
    private function applyPriceRangeFilter($query, $priceRange): void
    {
        $range = explode('-', $priceRange);
        if (count($range) == 2) {
            $min = (float)trim($range[0]);
            $max = (float)trim($range[1]);
            $query->whereBetween('sell_price', [$min, $max]);
        }
    }

    /**
     * Apply sorting to query
     */
    private function applySorting($query): void
    {
        switch(request('sort')) {
            case 'price_asc':
                $query->orderBy('sell_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('sell_price', 'desc');
                break;
            default:
                $query->latest();
        }
    }
}