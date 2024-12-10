<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductsDataExport implements FromArray, WithTitle
{
    public function array(): array
    {
        return [
            [
                'product_id', 
                'name', 
                'description', 
                'brand_id', 
                'unit_id', 
                'has_varian',
                'variasi_1', 
                'variasi_2', 
                'variasi_3', 
                'purchase_price', 
                'sale_price', 
                'stock', 
                'weight', 
                'store_id', 
                'sku', 
                'barcode' 
            ],
            [
                null, // Placeholder untuk product_id, karena ini akan diisi saat penyimpanan
                'Produk A', 
                'Deskripsi A', 
                1, 
                1, 
                'Y',
                'Size M', 
                'Color Red', 
                null,
                100, 
                150, 
                10, 
                0.5, 
                1, 
                'SKU001', 
                'BARCODE1',
            ],
            [
                null, // Placeholder untuk product_id
                'Produk B', 
                'Deskripsi B', 
                2, 
                1, 
                'N',
                null, 
                null, 
                null, 
                200, 
                300, 
                5, 
                1.0, 
                1, 
                'SKU002', 
                'BARCODE2', 
            ],
        ];
    }

    public function title(): string
    {
        return 'Product';
    }
}
