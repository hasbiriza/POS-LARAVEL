<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\Stores;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BankReportExport;
class BankReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $role = $user->roles->pluck('name')->first();
        $userStores = $user->getstores->pluck('id')->toArray();
        $store_id = $userStores[0];
        if ($request->store) {
            $store_id = $request->store;
            if (!in_array($store_id, $userStores)) {
                die('You do not have access to this store.');
            }
        } 
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        if ($startDate && $endDate) {
            $bank_report = Bank::get_bank_report($store_id, $startDate, $endDate);
        } else {
            $bank_report = Bank::get_bank_report($store_id);
        }
        $stores = $user->getstores;
        $bank = Bank::all();
        // dd($bank_report);
        return view('bank-report.index', compact('bank', 'stores', 'bank_report'));
    }
   
    public function exportExcel(Request $request)
    {
        $store_id = $request->input('store_id');
        $store_name = Stores::find($store_id)->store_name;
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        // dd($startDate, $endDate);
        $bank_report = Bank::get_bank_report($store_id, $startDate, $endDate);
        // dd($bank_report);
        return Excel::download(new BankReportExport($store_name, $bank_report, $startDate, $endDate), 'bank-report.xlsx');
    }
}
