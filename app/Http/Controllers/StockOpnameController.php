<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stores;
use App\Models\User;
use App\Models\StockOpname;
use App\Models\StockOpnameItems;
use App\Models\ProductPricing;
use Illuminate\Support\Facades\DB;

class StockOpnameController extends Controller
{
    public function index()
    {
        $stockOpnames = StockOpname::get_transaction();
        $stores = Stores::all();
        return view('stockopname.index', compact('stockOpnames', 'stores'));
    }

    public function add()
    {
        $stores = Stores::all();
        $users = User::all();
        $noreff = StockOpname::get_noreff()->noreff;
        $reason = DB::table('stock_opname_reason')->select('id', 'name')->get();
        return view('stockopname.add', compact('stores', 'users', 'noreff', 'reason'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'opname_date' => 'required|date',
            'reference_no' => 'required|string',
            'store' => 'required|exists:stores,id',
            'user' => 'required|exists:users,id',
            'products' => 'required|array',
            'total_note' => 'nullable|string',
        ]);

        $stockOpname = StockOpname::create([
            'date' => $request->opname_date,
            'store_id' => $request->store,
            'user_id' => $request->user,
            'note' => $request->total_note,
        ]);

        foreach ($request->products as $productId => $productData) {
            StockOpnameItems::create([
                'stock_opname_id' => $stockOpname->id,
                'product_pricing_id' => $productId,
                'system_stock' => $productData['available_stock'],
                'physical_stock' => $productData['physical_stock'],
                'stock_difference' => $productData['stock_difference'],
                'unit_price' => $productData['unit_price'],
                'id_reason' => $productData['difference_cause']
            ]);

            $productPricing = ProductPricing::findOrFail($productId);
            $productPricing->stock = $productData['physical_stock'];
            $productPricing->save();
        }       
        return response()->json([
            'success' => true,
            'message' => 'Data stock opname berhasil disimpan'
        ]);
    }
    public function detail($id)
    {
        $stockOpname = StockOpname::get_transaction_by_id($id);
        $detail = StockOpnameItems::get_detail($id);
        return view('stockopname.detail', compact('stockOpname', 'detail'));
    }

    public function edit($id)
    {
        $stores = Stores::all();
        $users = User::all();
        $noreff = StockOpname::get_noreff()->noreff;
        $reason = DB::table('stock_opname_reason')->select('id', 'name')->get();
        $stockOpname = StockOpname::get_transaction_by_id($id);
        $detail = StockOpnameItems::get_detail($id);
        return view('stockopname.edit', compact('stockOpname', 'detail', 'stores', 'users', 'noreff', 'reason'));
    }
    
    public function update(Request $request, $id)
    {
        $stockOpname = StockOpname::findOrFail($id);
        $stockOpname->update([
            'date' => $request->opname_date,
            'store_id' => $request->store,
            'user_id' => $request->user,
            'note' => $request->total_note,
        ]);

        StockOpnameItems::where('stock_opname_id', $id)->delete();
        foreach ($request->products as $productId => $productData) {
            StockOpnameItems::create([
                'stock_opname_id' => $id,
                'product_pricing_id' => $productId,
                'system_stock' => $productData['available_stock'],
                'physical_stock' => $productData['physical_stock'],
                'stock_difference' => $productData['stock_difference'],
                'unit_price' => $productData['unit_price'],
                'id_reason' => $productData['difference_cause']
            ]);

            $productPricing = ProductPricing::findOrFail($productId);
            $productPricing->stock = $productData['physical_stock'];
            $productPricing->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Data stock opname berhasil diupdate'
        ]);
    }
    public function destroy($id)
    {
        $stockOpname = StockOpname::findOrFail($id);
        
        $stockOpnameItems = StockOpnameItems::where('stock_opname_id', $id)->get();
        
        foreach ($stockOpnameItems as $item) {
            $productPricing = ProductPricing::findOrFail($item->product_pricing_id);
            $productPricing->stock = $item->system_stock;
            $productPricing->save();
        }
        
        $stockOpname->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data stock opname berhasil dihapus'
        ]);
    }
}
