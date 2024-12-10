<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductPricing;
use App\Models\StockReport;
use App\Models\Stores;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockReportExport;

class StockReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $role = $user->roles->pluck('name')->first();
        $userStores = $user->getstores->pluck('id')->toArray();
        $stores = $user->getstores;
    
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $store_id = $request->input('store') ?: ($userStores[0] ?? null); 
        if ($request->store) {
            $store_id = $request->store;
            // if (!in_array($store_id, $userStores)) {
            //     die('You do not have access to this store.');
            // }
        }
        $report = StockReport::get_report_filter($store_id);   
        return view('stock-report.index', compact('report', 'stores'));
    }

    public function exportExcel(Request $request)
    {
        $store_id = $request->store_id;
        $store = Stores::find($store_id); 
        $storeName = $store ? $store->store_name : 'Semua Toko';
        $transactions = StockReport::get_report_filter($store_id);

        return Excel::download(new StockReportExport($transactions, $storeName), 'stock_report.xlsx');
    }

}
