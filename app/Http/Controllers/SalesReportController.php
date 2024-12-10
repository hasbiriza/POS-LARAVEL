<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesTransactions;
use App\Models\Stores;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;
class SalesReportController extends Controller
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
        $transactions = SalesTransactions::get_report_filter($store_id, $start_date, $end_date);    
        // dd($transactions);
        return view('sales-report.index', compact('transactions', 'stores'));
    }
    

    public function exportExcel(Request $request)
    {
        $store_id = $request->store_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $store = Stores::find($store_id); 
        $storeName = $store ? $store->store_name : 'Semua Toko';
        $transactions = SalesTransactions::get_report_filter($store_id, $start_date, $end_date);

        return Excel::download(new SalesReportExport($transactions, $start_date, $end_date, $storeName), 'sales_report.xlsx');
    }
}
