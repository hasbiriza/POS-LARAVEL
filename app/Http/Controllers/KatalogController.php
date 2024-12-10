<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KatalogModel;
use App\Models\Stores;

class KatalogController extends Controller
{
        public function index(){
            $storeIds = [1];
            $products = KatalogModel::getProductsWithDetails($storeIds);
            $stores = Stores::all();
            return view('katalog.index', compact('products', 'stores'));
        }

        public function getProductVariants($id)
        {
            $variants = KatalogModel::getProductVariants($id);
            return response()->json(['variants' => $variants]);
        }
}
