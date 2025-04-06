<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class ProductsImport implements ToModel, WithHeadingRow
{
    protected $supplierId;
    protected $skippedRows = [];
    protected $successCount = 0;
    
    // Database-allowed product types (update these to match your ENUM values)
    protected $allowedTypes = [
        'T-shirt',
        'Polo Shirt',
        'Sweater',
        'Hoodie',
        'Jersey',
        'Dress',
        'Sweatshirt',
        'Pants',
        'Shorts'
    ];

    public function __construct($supplierId)
    {
        $this->supplierId = $supplierId;
    }

    public function model(array $row)
    {
        try {
            $productData = $this->prepareProductData($row);
            
            // Validate against database constraints
            if (!$this->isValidType($productData['types'])) {
                throw new \Exception("Invalid product type: {$productData['types']}. Allowed types: ".implode(', ', $this->allowedTypes));
            }

            $product = new Product($productData);
            $product->save();
            
            $this->successCount++;
            return $product;

        } catch (\Exception $e) {
            $this->trackFailedRow($row, $e);
            return null;
        }
    }

    protected function prepareProductData(array $row): array
    {
        return [
            'product_name' => $this->cleanString($row['product_name'] ?? ''),
            'size' => $this->normalizeSize($row['size'] ?? null),
            'category' => $this->normalizeCategory($row['category'] ?? null),
            'types' => $this->normalizeType($row['types'] ?? null),
            'description' => $this->cleanString($row['description'] ?? ''),
            'cost_price' => $this->parsePrice($row['cost_price'] ?? 0),
            'sell_price' => $this->parsePrice($row['sell_price'] ?? 0),
            'stock' => $this->parseStock($row['stock'] ?? 0),
            'supplier_id' => $this->supplierId,
        ];
    }

    protected function isValidType(?string $type): bool
    {
        return in_array($type, $this->allowedTypes);
    }

    protected function trackFailedRow(array $row, \Exception $e): void
    {
        $this->skippedRows[] = [
            'row' => $row,
            'error' => $e->getMessage(),
            'values' => [
                'product_name' => $row['product_name'] ?? 'N/A',
                'types' => $row['types'] ?? 'N/A',
                'category' => $row['category'] ?? 'N/A'
            ]
        ];
        
        Log::error('Product import failed', [
            'row' => $row,
            'error' => $e->getMessage(),
            'allowed_types' => $this->allowedTypes
        ]);
    }

    protected function parsePrice($value): float
    {
        $normalized = str_replace(',', '.', (string)$value);
        $cleaned = preg_replace('/[^0-9.]/', '', $normalized);
        return is_numeric($cleaned) ? (float)$cleaned : 0.0;
    }

    protected function parseStock($value): int
    {
        return max(0, (int)$value);
    }

    protected function normalizeSize(?string $value): ?string
    {
        if (empty($value)) return null;
        $value = strtoupper(trim($value));
        return in_array($value, ['XS','S','M','L','XL','XXL']) ? $value : null;
    }

    protected function normalizeCategory(?string $value): string
    {
        $value = strtolower(trim($value ?? ''));
        return match(true) {
            str_contains($value, 'men') => 'Mens',
            str_contains($value, 'women') => 'Womens',
            str_contains($value, 'kid') => 'Kids',
            default => 'Mens'
        };
    }

    protected function normalizeType(?string $value): string
    {
        if (empty($value)) return $this->allowedTypes[0] ?? 'T-shirt';
        
        $value = strtolower(trim($value));
        $mappedType = match($value) {
            'tshirt', 't-shirt', 'tee' => 'T-shirt',
            'poloshirt', 'polo' => 'Polo Shirt',
            'sweater' => 'Sweater',
            'hoodie' => 'Hoodie',
            'jersey' => 'Jersey',
            'dress' => 'Dress',
            'sweatshirt' => 'Sweatshirt',
            'pant', 'pants' => 'Pants',
            'short', 'shorts' => 'Shorts',
            default => null
        };

        return $this->isValidType($mappedType) ? $mappedType : ($this->allowedTypes[0] ?? 'T-shirt');
    }

    protected function cleanString(?string $value): string
    {
        return trim(preg_replace('/\s+/', ' ', (string)$value));
    }

    public function getImportResults(): array
    {
        return [
            'success_count' => $this->successCount,
            'skipped_rows' => $this->skippedRows,
            'allowed_types' => $this->allowedTypes
        ];
    }
}