<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $product = Product::with(['supplier', 'images'])
            ->latest()
            ->paginate(10);

        return view('admin.product.index', compact('product'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $supplier = Supplier::orderBy('brand_name')->get();
        return view('admin.product.create', compact('supplier'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'supplier_id' => 'required|exists:supplier,supplier_id',
            'size' => 'required|in:XS,S,M,L,XL,XXL',
            'category' => 'required|in:Mens,Womens,Kids',
            'types' => 'required|in:T-shirt,Polo Shirt,Sweater,Hoodie,Jersey,Dress,Sweatshirt,Pants,Shorts',
            'description' => 'required|string',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0|gte:cost_price',
            'stock' => 'required|integer|min:0',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Create the product
        $product = Product::create($validated);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $path = $image->store('product', 'public');
                
                ProductImage::create([
                    'product_id' => $product->product_id,
                    'image_path' => $path,
                    'is_primary' => $key === 0 // First image as primary
                ]);
            }
        }

        try {
            // ... your existing import logic ...
            
            return redirect()
                ->route('admin.product.index') // Matches the route defined above
                ->with('success', $message);
                
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Import failed: ' . $e->getMessage())
                ->withInput();
        }
    }
    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return view('admin.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $supplier = Supplier::orderBy('brand_name')->get();
        $product->load('images');
        return view('admin.product.edit', compact('product', 'supplier'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'supplier_id' => 'required|exists:supplier,supplier_id',
            'size' => 'required|in:XS,S,M,L,XL,XXL',
            'category' => 'required|in:Mens,Womens,Kids',
            'types' => 'required|in:T-shirt,Polo Shirt,Sweater,Hoodie,Jersey,Dress,Sweatshirt,Pants,Shorts',
            'description' => 'required|string',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0|gte:cost_price',
            'stock' => 'required|integer|min:0',
            'images' => 'sometimes|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_images' => 'sometimes|array',
            'primary_image' => 'sometimes|exists:product_images,id'
        ]);

        // Update product
        $product->update($validated);

        // Handle image deletions
        if ($request->has('delete_images')) {
            foreach ($request->input('delete_images') as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product', 'public');
                
                ProductImage::create([
                    'product_id' => $product->product_id,
                    'image_path' => $path,
                    'is_primary' => false
                ]);
            }
        }

        // Update primary image if changed
        if ($request->has('primary_image')) {
            ProductImage::where('product_id', $product->product_id)
                ->update(['is_primary' => false]);
                
            ProductImage::where('id', $request->primary_image)
                ->update(['is_primary' => true]);
        }

        return redirect()->route('admin.product.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Delete associated images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $product->delete();

        return redirect()->route('admin.product.index')
            ->with('success', 'Product deleted successfully.');
    }


    public function showImportForm()
    {
        $suppliers = Supplier::orderBy('brand_name')
            ->select('supplier_id', 'brand_name')
            ->get();
        
        return view('admin.product.import', compact('suppliers'));
    }

    public function import(Request $request)
    {
        try {
            $supplier = Supplier::findOrFail($request->supplier_id);
            
            $import = new ProductsImport($request->supplier_id);
            Excel::import($import, $request->file('file'));
            
            $results = $import->getImportResults();

            $message = "Successfully imported {$results['success_count']} products for {$supplier->brand_name}";

            if (!empty($results['skipped_rows'])) {
                $message .= ". Skipped " . count($results['skipped_rows']) . " rows due to errors.";
                
                // Format errors for display
                $formattedErrors = array_map(function($error) use ($results) {
                    return [
                        'product' => $error['row']['product_name'] ?? 'Unknown',
                        'error' => $error['error'],
                        'allowed_types' => implode(', ', $results['allowed_types'])
                    ];
                }, $results['skipped_rows']);
                
                session()->flash('import_errors', $formattedErrors);
            }

            return redirect()
                ->route('admin.product.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Import failed: ' . $e->getMessage())
                ->withInput();
        }
    }
}