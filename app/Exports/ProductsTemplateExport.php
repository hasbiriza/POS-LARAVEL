<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductsTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Product' => new ProductsDataExport(),
            'Petunjuk' => new InstructionsExport(),
        ];
    }
}
