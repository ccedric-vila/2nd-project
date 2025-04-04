<?php

namespace App\Http\Controllers;

use App\Models\Product;

class UserProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['brand', 'images', 'reviews'])
            ->where('stock', '>', 0)
            ->paginate(12);

        return view('home', compact('products'));
    }
}