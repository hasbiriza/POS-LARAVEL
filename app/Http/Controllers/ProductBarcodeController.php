<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
class ProductBarcodeController extends Controller
{
    public function index()
    {
        return view('productbarcode.index');
    }

    public function search(Request $request)
    {
        $products = Product::searchProduct($request->name);
        // dd($products);
        return response()->json($products);
    }
}
