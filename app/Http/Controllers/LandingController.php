<?php

namespace App\Http\Controllers;

use App\Models\Product;

class LandingController extends Controller
{
    public function index()
    {
        $product = Product::with(['supplier', 'images', 'reviews'])
            ->orderBy('created_at', 'desc')
            ->take(8) // Show 8 featured products
            ->get();
            
        return view('landing', compact('product'));
    }
}