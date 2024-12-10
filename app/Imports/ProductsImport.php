<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductPricing;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class ProductsImport implements ToModel, WithHeadingRow
{
    private $rowCount = 0;
    private $productSet = [];

    public function model(array $row)
    {
        // Cek apakah name dan description ada
        if (empty($row['name']) || empty($row['description'])) {
            Log::error('Missing name or description', $row);
            return null;
        }

        // Ambil brand_id dan unit_id dengan default 1
        $brandId = isset($row['brand_id']) && is_numeric($row['brand_id']) ? (int)$row['brand_id'] : 1;
        $unitId = isset($row['unit_id']) && is_numeric($row['unit_id']) ? (int)$row['unit_id'] : 1;

        // Cek apakah produk sudah ada menggunakan product_id
        $product = null;

        // Jika produk sudah ada di $productSet, ambil dari sana
        if (isset($this->productSet[$row['product_id']])) {
            $product = Product::find($this->productSet[$row['product_id']]);
        } else {
            // Jika produk belum ada, cek ke database
            $product = Product::where('id', $row['product_id'])->first();

            // Jika produk tidak ada, buat produk baru
            if (!$product) {
                $product = Product::create([
                    'id' => $row['product_id'],  // Menggunakan product_id dari Excel
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'brand_id' => $brandId,
                    'unit_id' => $unitId,
                    'has_varian' => isset($row['has_varian']) ? ($row['has_varian'] === 'Y' ? 'Y' : 'N') : 'N',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Simpan product_id untuk mencegah duplikasi produk di masa mendatang
            $this->productSet[$row['product_id']] = $product->id;
        }

        // Pastikan $product valid sebelum melanjutkan
        if (!$product) {
            Log::error('Product creation failed or not found', $row);
            return null;
        }

        // Simpan variasi dan harga di tabel ProductPricing
        ProductPricing::create([
            'product_id' => $product->id,
            'variasi_1' => ($row['has_varian'] === 'Y' ? ($row['variasi_1'] ?? null) : null),
            'variasi_2' => ($row['has_varian'] === 'Y' ? ($row['variasi_2'] ?? null) : null),
            'variasi_3' => ($row['has_varian'] === 'Y' ? ($row['variasi_3'] ?? null) : null),
            'purchase_price' => $row['purchase_price'] ?? 0,
            'sale_price' => $row['sale_price'] ?? 0,
            'stock' => $row['stock'] ?? 0,
            'weight' => $row['weight'] ?? 0,
            'store_id' => $row['store_id'] ?? null,
            'sku' => $row['sku'] ?? '',
            'barcode' => $row['barcode'] ?? '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->rowCount++;
        return $product;
    }

    public function getRowCount()
    {
        return $this->rowCount;
    }
}
