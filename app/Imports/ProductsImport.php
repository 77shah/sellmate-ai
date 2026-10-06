<?php
// app/Imports/ProductsImport.php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class ProductsImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $tenantId;

    public function __construct($tenantId)
    {
        $this->tenantId = $tenantId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Find or create category
            $categoryId = null;
            if (!empty($row['category'])) {
                $category = Category::firstOrCreate(
                    [
                        'tenant_id' => $this->tenantId,
                        'name' => trim($row['category'])
                    ],
                    [
                        'slug' => Str::slug($row['category']),
                        'status' => 'active'
                    ]
                );
                $categoryId = $category->id;
            }

            // Check if product already exists by SKU
            $product = Product::where('tenant_id', $this->tenantId)
                ->where('sku', $row['sku'])
                ->first();

            if ($product) {
                // Update existing product
                $product->update([
                    'name' => $row['name'],
                    'description' => $row['description'] ?? null,
                    'price' => $row['price'],
                    'stock_qty' => $row['stock_qty'],
                    'category_id' => $categoryId,
                    'status' => isset($row['status']) ? strtolower($row['status']) : 'active',
                ]);
            } else {
                // Create new product
                Product::create([
                    'tenant_id' => $this->tenantId,
                    'name' => $row['name'],
                    'description' => $row['description'] ?? null,
                    'price' => $row['price'],
                    'stock_qty' => $row['stock_qty'],
                    'sku' => $row['sku'] ?? $this->generateSku($row['name']),
                    'category_id' => $categoryId,
                    'images' => [],
                    'status' => isset($row['status']) ? strtolower($row['status']) : 'active',
                ]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_qty' => 'required|integer|min:0',
            'sku' => 'nullable|string',
            'category' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Product name is required in row :row',
            'price.required' => 'Price is required in row :row',
            'stock_qty.required' => 'Stock quantity is required in row :row',
        ];
    }

    private function generateSku($productName)
    {
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $productName), 0, 3));
        $random = strtoupper(substr(uniqid(), -6));
        return $prefix . '-' . $random . '-T001';
    }
}