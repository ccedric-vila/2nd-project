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

        return redirect()->route('admin.product.index')
            ->with('success', 'Product created successfully.');
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
        $request->validate([
            'supplier_id' => 'required|exists:supplier,supplier_id',
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120'
        ]);

        try {
            $supplier = Supplier::findOrFail($request->supplier_id);
            
            $import = new ProductsImport($request->supplier_id);
            Excel::import($import, $request->file('file'));
            
            $successCount = $import->getImportCount();
            $skippedRows = $import->getSkippedRows();
            $processedCount = $import->getProcessedCount();
            $importedProducts = $import->getImportedProducts();

            // Build comprehensive status message
            $message = "Processed {$processedCount} rows. ";
            $message .= "Successfully imported {$successCount} products for {$supplier->brand_name}.";
            
            if (!empty($skippedRows)) {
                $skippedCount = count($skippedRows);
                $message .= " Skipped {$skippedCount} row" . ($skippedCount > 1 ? 's' : '') . " due to:";
                
                // Categorize skipped rows by error type
                $errorSummary = [];
                foreach ($skippedRows as $row) {
                    foreach ($row['errors'] as $error) {
                        $errorSummary[$error] = ($errorSummary[$error] ?? 0) + 1;
                    }
                }
                
                foreach ($errorSummary as $error => $count) {
                    $message .= " {$count} " . strtolower($error);
                }
                
                // Format skipped rows for detailed display
                $formattedSkippedRows = array_map(function($row) {
                    return [
                        'row' => $row['row'],
                        'errors' => implode('; ', $row['errors']),
                        'product_name' => $row['values']['product_name'] ?? 'N/A',
                        'category' => $row['values']['category'] ?? 'N/A',
                        'types' => $row['values']['types'] ?? 'N/A',
                        'cost_price' => $row['values']['cost_price'] ?? 'N/A',
                        'sell_price' => $row['values']['sell_price'] ?? 'N/A'
                    ];
                }, $skippedRows);
                
                session()->flash('skipped_rows_details', $formattedSkippedRows);
            }

            session()->flash('imported_products', $importedProducts);

            return redirect()
                ->route('admin.products.index')
                ->with([
                    'success' => $message,
                    'stats' => [
                        'processed' => $processedCount,
                        'imported' => $successCount,
                        'skipped' => count($skippedRows ?? [])
                    ]
                ]);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $errors = collect($e->failures())->map(function($failure) {
                return [
                    'row' => $failure->row(),
                    'field' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'value' => $failure->values()[$failure->attribute()] ?? null
                ];
            });

            // Group errors by row for cleaner display
            $groupedErrors = [];
            foreach ($errors as $error) {
                $groupedErrors[$error['row']][] = $error;
            }

            return back()
                ->with([
                    'import_errors' => $groupedErrors,
                    'error' => 'Validation failed for ' . count($errors) . ' rows'
                ])
                ->withInput();

        } catch (\Exception $e) {
            \Log::error('Product Import Error', [
                'exception' => $e->getMessage(),
                'supplier' => $request->supplier_id,
                'file' => $request->file('file')->getClientOriginalName()
            ]);

            return back()
                ->with('error', 'Import failed: ' . $e->getMessage())
                ->withInput();
        }
    }
}