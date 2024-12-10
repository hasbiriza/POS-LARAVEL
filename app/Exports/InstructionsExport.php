<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class InstructionsExport implements FromArray, WithTitle
{
    public function array(): array
    {
        return [
            [
                'Petunjuk Pengisian', // Header
                '1. Pastikan semua kolom diisi dengan benar.',
                '2. Kolom "name" dan "description" tidak boleh kosong.',
                '3. "brand_id" dan "unit_id" harus berupa angka. Jika tidak ada, gunakan default 1.',
                '4. "has_varian" harus diisi dengan "Y" jika ada variasi dan "N" jika tidak.',
                '5. Kolom variasi hanya diisi jika "has_varian" adalah "Y".',
                '6. Pastikan "purchase_price", "sale_price", "stock", dan "weight" diisi dengan angka.',
                '7. "sku" dan "barcode" harus diisi sesuai format yang ditentukan.',
            ],
        ];
    }

    // Method untuk mengatur judul sheet
    public function title(): string
    {
        return 'Petunjuk'; // Setel nama sheet yang diinginkan
    }
}
