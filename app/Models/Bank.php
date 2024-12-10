<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Bank extends Model
{
    use HasFactory;
    protected $table = 'bank';
    protected $fillable = ['name','branch','account_number','account_name','swift_code','credit','debit','saldo','logo'];
    
    public static function masterbank(){
        $banks = DB::table('bank as b')
        ->select(
            'b.id as bank_id',
            'b.name as bank_name',
            'b.logo',
            'b.swift_code',
            'b.branch',
            'b.account_number',
            'b.account_name',
            DB::raw('SUM(IFNULL(ep.total_amount, 0) + IFNULL(pp.total_amount, 0) + IFNULL(stp.total_amount, 0)) as Debit'),
            DB::raw('SUM(IFNULL(pm.total_amount, 0)) as Kredit')
        )
        ->leftJoin(DB::raw('(SELECT bank_id, SUM(amount_paid) as total_amount 
                             FROM expense_payment 
                             GROUP BY bank_id) as ep'), 'b.id', '=', 'ep.bank_id')
        ->leftJoin(DB::raw('(SELECT bank_id, SUM(amount_paid) as total_amount 
                             FROM purchase_payments 
                             GROUP BY bank_id) as pp'), 'b.id', '=', 'pp.bank_id')
        ->leftJoin(DB::raw('(SELECT bank_id, SUM(amount_paid) as total_amount 
                             FROM stock_transfer_payments 
                             GROUP BY bank_id) as stp'), 'b.id', '=', 'stp.bank_id')
        ->leftJoin(DB::raw('(SELECT bank_id, SUM(amount) as total_amount 
                             FROM payment_methods 
                             GROUP BY bank_id) as pm'), 'b.id', '=', 'pm.bank_id')
        ->groupBy('b.id', 'b.name', 'b.branch', 'b.account_number', 'b.account_name', 'b.logo', 'b.swift_code')
        ->get();
        return $banks;
    }
    public static function get_bank_report($storeId, $startDate = null, $endDate = null)
    {
        // Purchases
        $purchase = DB::table('purchase_orders as po')
            ->join('purchase_payments as pp', 'po.id', '=', 'pp.purchase_order_id')
            ->leftJoin('bank as b', 'pp.bank_id', '=', 'b.id')
            ->select(
                'pp.payment_date AS payment_date',
                'po.payment_method AS payment_method',
                'pp.amount_paid AS payment_amount',
                'b.name AS bank_name',
                'b.account_number AS account_number',
                DB::raw("'Debit' AS type"),
                DB::raw("'Purchase' AS transaction_type"),
                'po.id AS purchase_id',
                DB::raw("CONCAT('PR-', DATE_FORMAT(po.purchase_date, '%Y%m%d'), '-', LPAD(po.id, 3, '0')) AS transaction_id")
            )
            ->where('po.store_id', $storeId);
        
        // Sales
        $sales = DB::table('sales_transactions as st')
            ->leftJoin('payment_methods as pm', 'st.id', '=', 'pm.transaction_id')
            ->leftJoin('bank as b', 'pm.bank_id', '=', 'b.id')
            ->select(
                'pm.payment_date AS payment_date',
                'st.payment_method AS payment_method',
                DB::raw('pm.amount - COALESCE(st.change_payment, 0) AS payment_amount'),
                'b.name AS bank_name',
                'b.account_number AS account_number',
                DB::raw("'Kredit' AS type"),
                DB::raw("'Sales' AS transaction_type"),
                'st.id AS sales_id',
                DB::raw("CONCAT('INV-', DATE_FORMAT(st.transaction_date, '%Y%m%d'), '-', LPAD(st.id, 3, '0')) AS transaction_id")
            )
            ->where('st.store_id', $storeId);
        
        // Stock Transfers
        $stockTransfer = DB::table('stock_transfers as st')
            ->join('stock_transfer_payments as stp', 'st.id', '=', 'stp.stock_transfer_id')
            ->leftJoin('bank as b', 'stp.bank_id', '=', 'b.id')
            ->select(
                'stp.payment_date AS payment_date',
                'stp.payment_method AS payment_method',
                'stp.amount_paid AS payment_amount',
                'b.name AS bank_name',
                'b.account_number AS account_number',
                DB::raw("'Debit' AS type"),
                DB::raw("'Stock Transfer' AS transaction_type"),
                'st.id AS stock_transfer_id',
                DB::raw("CONCAT('ST-', DATE_FORMAT(st.transfer_date, '%Y%m%d'), '-', LPAD(st.id, 3, '0')) AS transaction_id")
            )
            ->where('st.from_store_id', $storeId);
        
        // Expenses
        $expenses = DB::table('expenses as e')
            ->join('expense_payment as ep', 'e.id', '=', 'ep.expense_id')
            ->leftJoin('bank as b', 'ep.bank_id', '=', 'b.id')
            ->select(
                'ep.payment_date AS payment_date',
                'ep.payment_method AS payment_method',
                'ep.amount_paid AS payment_amount',
                'b.name AS bank_name',
                'b.account_number AS account_number',
                DB::raw("'Debit' AS type"),
                DB::raw("'Expense' AS transaction_type"),
                'e.id AS expense_id',
                DB::raw("CONCAT('EXP-', DATE_FORMAT(e.expense_date, '%Y%m%d'), '-', LPAD(e.id, 3, '0')) AS transaction_id")
            )
            ->where('e.store_id', $storeId);
        
        if ($startDate && $endDate) {
            $purchase->whereBetween('pp.payment_date', [$startDate, $endDate]);
            $sales->whereBetween('pm.payment_date', [$startDate, $endDate]);
            $stockTransfer->whereBetween('stp.payment_date', [$startDate, $endDate]);
            $expenses->whereBetween('ep.payment_date', [$startDate, $endDate]);
        }
        
        $results = $purchase->union($sales)
            ->union($stockTransfer)
            ->union($expenses)
            ->orderBy('payment_date')
            ->get();
        return $results;
    }
}