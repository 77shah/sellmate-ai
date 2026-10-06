<?php
// app/Exports/ProductsExport.php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $tenantId;

    public function __construct($tenantId)
    {
        $this->tenantId = $tenantId;
    }

    public function query()
    {
        return Product::with('category')
            ->where('tenant_id', $this->tenantId);
    }

    public function headings(): array
    {
        return [
            '#',
            'Product Name',
            'Description',
            'Price',
            'Stock Quantity',
            'SKU',
            'Category',
            'Status',
            'Created At',
            'Updated At'
        ];
    }

    public function map($product): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        return [
            $rowNumber,
            $product->name,
            $product->description,
            $product->price,
            $product->stock_qty,
            $product->sku ?? 'N/A',
            $product->category ? $product->category->name : 'Uncategorized',
            ucfirst($product->status),
            $product->created_at ? $product->created_at->format('d-m-Y H:i:s') : 'N/A',
            $product->updated_at ? $product->updated_at->format('d-m-Y H:i:s') : 'N/A',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}