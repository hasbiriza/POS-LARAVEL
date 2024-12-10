<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Exports\ProductsTemplateExport;
use Illuminate\Support\Facades\Log;

class ProductImportController extends Controller
{
    public function index()
    {
        return view('import-product.index');
    }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);

        try {
            $import = new ProductsImport; 
            Excel::import($import, $request->file('file'));
    
            $importedCount = $import->getRowCount(); 
    
            Log::info('Products imported successfully.', ['count' => $importedCount]);
    
            return back()->with('success', "Products imported successfully. Total imported: $importedCount.");
        } catch (\Exception $e) {
            Log::error('Error importing products: ' . $e->getMessage());
    
            return back()->with('error', 'An error occurred while importing products. Please try again.');
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new ProductsTemplateExport, 'product_template.xlsx');
    }
}
