<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $supplier_id;

    public function __construct($supplier_id = null)
    {
        $this->supplier_id = $supplier_id;
    }

    public function model(array $row)
    {
        return new Product([
            'product_name' => $this->cleanString($row['product_name']),
            'size' => isset($row['size']) ? strtoupper(trim($row['size'])) : null,
            'category' => $this->normalizeCategory($row['category']),
            'types' => isset($row['types']) ? $this->normalizeType($row['types']) : null,
            'description' => isset($row['description']) ? $this->cleanString($row['description']) : null,
            'cost_price' => $this->parsePrice($row['cost_price']),
            'sell_price' => $this->parsePrice($row['sell_price']),
            'stock' => isset($row['stock']) ? (int)$row['stock'] : 0,
            'supplier_id' => $this->supplier_id,
        ]);
    }

    public function rules(): array
    {
        return [
            'product_name' => 'required|string|max:255',
            'size' => 'nullable|in:XS,S,M,L,XL,XXL',
            'category' => [
                'required',
                Rule::in(['Mens', 'Womens', 'Kids'])
            ],
            'types' => [
                'nullable',
                Rule::in([
                    'T-shirt', 'Polo Shirt', 'Sweater', 'Hoodie', 
                    'Jersey', 'Dress', 'Sweatshirt', 'Pants', 'Shorts'
                ])
            ],
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'category.in' => 'The category must be one of: Mens, Womens, Kids',
            'types.in' => 'Invalid product type. Valid types are: T-shirt, Polo Shirt, etc.',
            'size.in' => 'Size must be one of: XS, S, M, L, XL, XXL',
        ];
    }

    // Helper methods
    protected function cleanString($value)
    {
        return trim(preg_replace('/\s+/', ' ', $value));
    }

    protected function parsePrice($value)
    {
        if (is_string($value)) {
            return (float) preg_replace('/[^0-9.]/', '', $value);
        }
        return (float) $value;
    }

    protected function normalizeCategory($value)
    {
        $value = trim($value);
        return ucfirst(strtolower($value));
    }

    protected function normalizeType($value)
    {
        $value = trim($value);
        
        // Handle common variations
        $variations = [
            'tshirt' => 'T-shirt',
            't-shirt' => 'T-shirt',
            'poloshirt' => 'Polo Shirt',
            'hoodies' => 'Hoodie',
            'jerseys' => 'Jersey',
            'dresses' => 'Dress',
            'sweatshirts' => 'Sweatshirt',
            'pant' => 'Pants',
            'short' => 'Shorts',
        ];
        
        $lowerValue = strtolower($value);
        if (isset($variations[$lowerValue])) {
            return $variations[$lowerValue];
        }
        
        return ucwords(strtolower($value));
    }
}