<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    protected $supplierId;
    protected $successCount = 0;
    protected $skippedRows = [];
    protected $importedProducts = [];
    protected $processedRows = 0;

    public function __construct($supplierId)
    {
        $this->supplierId = $supplierId;
    }

    public function model(array $row)
    {
        $this->processedRows++;
        
        try {
            // Validate required fields first
            if (empty($row['product_name'])) {
                throw new \Exception("Product name is required");
            }

            $productData = $this->prepareProductData($row);
            $this->validatePrices($productData);

            $product = new Product($productData);
            $product->save();
            
            $this->trackSuccessfulImport($product);
            return $product;

        } catch (\Exception $e) {
            $this->trackFailedImport($e, $row);
            return null;
        }
    }

    protected function prepareProductData(array $row): array
    {
        return [
            'product_name' => $this->cleanString($row['product_name'] ?? ''),
            'size' => $this->normalizeSize($row['size'] ?? null),
            'category' => $this->normalizeCategory($row['category'] ?? ''),
            'types' => $this->normalizeType($row['types'] ?? null),
            'description' => $this->cleanString($row['description'] ?? ''),
            'cost_price' => $this->parsePrice($row['cost_price'] ?? 0),
            'sell_price' => $this->parsePrice($row['sell_price'] ?? 0),
            'stock' => $this->parseStock($row['stock'] ?? 0),
            'supplier_id' => $this->supplierId,
        ];
    }

    protected function validatePrices(array $productData): void
    {
        if ($productData['sell_price'] < $productData['cost_price']) {
            throw new \Exception(
                "Sell price ({$productData['sell_price']}) cannot be less than cost price ({$productData['cost_price']})"
            );
        }
    }

    protected function trackSuccessfulImport(Product $product): void
    {
        $this->successCount++;
        $this->importedProducts[] = [
            'product_id' => $product->product_id,
            'product_name' => $product->product_name,
            'category' => $product->category,
            'types' => $product->types,
            'sku' => $product->sku ?? 'N/A',
            'cost_price' => $product->cost_price,
            'sell_price' => $product->sell_price
        ];
    }

    protected function trackFailedImport(\Exception $e, array $row): void
    {
        Log::error("Import failed for row {$this->processedRows}: " . $e->getMessage(), [
            'row_data' => $this->getRelevantRowData($row),
            'trace' => Str::limit($e->getTraceAsString(), 200)
        ]);

        $this->skippedRows[] = [
            'row' => $this->processedRows,
            'errors' => [$e->getMessage()],
            'values' => $this->getRelevantRowData($row)
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->skippedRows[] = [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $this->getRelevantRowData($failure->values())
            ];
        }
    }

    public function rules(): array
    {
        return [
            'product_name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (preg_match('/[^\p{L}\p{N}\s\-_]/u', $value)) {
                        $fail('Product name contains invalid characters');
                    }
                }
            ],
            'size' => 'nullable|in:XS,S,M,L,XL,XXL',
            'category' => [
                'required',
                Rule::in(['Mens', 'Womens', 'Kids']),
                function ($attribute, $value, $fail) {
                    $normalized = $this->normalizeCategory($value);
                    if (!in_array($normalized, ['Mens', 'Womens', 'Kids'])) {
                        $fail('Category must be Mens, Womens or Kids');
                    }
                }
            ],
            'types' => [
                'nullable',
                Rule::in([
                    'T-shirt', 'Polo Shirt', 'Sweater', 'Hoodie', 
                    'Jersey', 'Dress', 'Sweatshirt', 'Pants', 'Shorts'
                ])
            ],
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0|gte:cost_price',
            'stock' => 'nullable|integer|min:0',
        ];
    }

    protected function getRelevantRowData(array $row): array
    {
        return [
            'product_name' => $row['product_name'] ?? 'N/A',
            'category' => $row['category'] ?? 'N/A',
            'types' => $row['types'] ?? 'N/A',
            'size' => $row['size'] ?? 'N/A',
            'cost_price' => $row['cost_price'] ?? 'N/A',
            'sell_price' => $row['sell_price'] ?? 'N/A',
            'stock' => $row['stock'] ?? 'N/A'
        ];
    }

    protected function cleanString(?string $value): string
    {
        return trim(preg_replace('/\s+/', ' ', (string)$value));
    }

    protected function parsePrice($value): float
    {
        if (is_string($value)) {
            $value = str_replace(',', '.', $value);
            $cleaned = preg_replace('/[^0-9.]/', '', $value);
            return (float)($cleaned !== '' ? $cleaned : 0);
        }
        return (float)$value;
    }

    protected function parseStock($value): int
    {
        $value = is_numeric($value) ? $value : 0;
        return max(0, (int)$value);
    }

    protected function normalizeSize(?string $value): ?string
    {
        if (empty($value)) return null;
        
        $value = strtoupper(trim($value));
        $validSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        
        if (!in_array($value, $validSizes)) {
            throw new \Exception("Invalid size: {$value}. Valid sizes are: " . implode(', ', $validSizes));
        }
        
        return $value;
    }

    protected function normalizeCategory(?string $value): string
    {
        $value = trim((string)$value);
        $value = strtolower($value);
        
        return match($value) {
            'mens', 'men' => 'Mens',
            'womens', 'women' => 'Womens',
            'kids', 'kid' => 'Kids',
            default => $value
        };
    }

    protected function normalizeType(?string $value): ?string
    {
        if (empty($value)) return null;
        
        $value = strtolower(trim($value));
        $map = [
            'tshirt' => 'T-shirt', 't-shirt' => 'T-shirt', 'tee' => 'T-shirt',
            'poloshirt' => 'Polo Shirt', 'polo' => 'Polo Shirt',
            'hoodie' => 'Hoodie', 'hoodies' => 'Hoodie',
            'jersey' => 'Jersey', 'jerseys' => 'Jersey',
            'dress' => 'Dress', 'dresses' => 'Dress',
            'sweatshirt' => 'Sweatshirt', 'sweatshirts' => 'Sweatshirt',
            'pant' => 'Pants', 'pants' => 'Pants',
            'short' => 'Shorts', 'shorts' => 'Shorts'
        ];
        
        return $map[$value] ?? ucwords($value);
    }

    public function getImportCount(): int
    {
        return $this->successCount;
    }

    public function getSkippedRows(): array
    {
        return $this->skippedRows;
    }

    public function getImportedProducts(): array
    {
        return $this->importedProducts;
    }

    public function getProcessedCount(): int
    {
        return $this->processedRows;
    }
}