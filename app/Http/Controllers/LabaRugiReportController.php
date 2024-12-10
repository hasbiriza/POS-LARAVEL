<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LabaRugi;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LabaRugiReportExport;
class LabaRugiReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $role = $user->roles->pluck('name')->first();
        $userStores = $user->getstores->pluck('id')->toArray();
        $store_id = $userStores[0];
        $stores = $user->getstores;
        if ($request->store) {
            $store_id = $request->store;
            if (!in_array($store_id, $userStores)) {
                die('You do not have access to this store.');
            }
        }

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $store_id = $request->input('store') ?: ($userStores[0] ?? null); 

        $penjualan_barang = LabaRugi::get_penjualan_barang($store_id, $start_date, $end_date);
        $return_penjualan = LabaRugi::get_return_penjualan($store_id, $start_date, $end_date);
        $total_pendapatan_bersih = $penjualan_barang['total_penjualan'] - $return_penjualan;
        $pembelian = LabaRugi::get_pembelian($store_id, $start_date, $end_date);
        $retur_pembelian = LabaRugi::get_retur_pembelian($store_id, $start_date, $end_date);
        $stock_opname = LabaRugi::get_stock_opname($store_id, $start_date, $end_date);
        $expense_category = LabaRugi::get_expense_category($store_id, $start_date, $end_date);
        return view('laba-rugi-report.index', compact('penjualan_barang', 'return_penjualan', 'total_pendapatan_bersih', 'pembelian', 'retur_pembelian', 'stock_opname', 'expense_category', 'stores'));
    }

    public function exportExcel(Request $request)
    {
        $store_id = $request->input('store_id');
        $penjualan_barang = LabaRugi::get_penjualan_barang($store_id);
        $return_penjualan = LabaRugi::get_return_penjualan($store_id);
        $total_pendapatan_bersih = $penjualan_barang['total_penjualan'] - $return_penjualan;
        $pembelian = LabaRugi::get_pembelian($store_id);
        $retur_pembelian = LabaRugi::get_retur_pembelian($store_id);
        $stock_opname = LabaRugi::get_stock_opname($store_id);
        $expense_category = LabaRugi::get_expense_category($store_id);
        return Excel::download(new LabaRugiReportExport($penjualan_barang, $return_penjualan, $total_pendapatan_bersih, $pembelian, $retur_pembelian, $stock_opname, $expense_category), 'laba-rugi-report.xlsx');
    }

    


}
