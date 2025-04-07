<?php

namespace App\Http\Controllers;

use App\Models\Product;

class LandingController extends Controller
{
    public function index()
    {
        $products = Product::with(['supplier', 'images', 'reviews'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
            
        return view('landing', compact('products'));
    }
}