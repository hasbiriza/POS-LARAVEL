<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Expense extends Model
{
    use HasFactory;
    protected $table = 'expenses';
    protected $fillable = [
        'total_amount',
        'description',
        'payment_method',
        'store_id',
        'remaining_amount',
        'status',
        'expense_date',
    ];
    
    public static function get_noreff(){
        $noreff = DB::raw("CONCAT('EXP-', DATE_FORMAT(CURDATE(), '%Y%m%d'), '-', LPAD(COALESCE(MAX(id), 0) + 1, 3, '0')) as noreff");
        return Expense::select($noreff)->first();
    }   
    public function expenseItems()
    {
        return $this->hasMany(ExpenseItems::class);
    }

    public static function get_transaction(){
        $expenses = DB::table('expenses as e')
        ->join('stores as s', 'e.store_id', '=', 's.id')
        ->select(
            DB::raw("CONCAT('EXP-', DATE_FORMAT(e.created_at, '%Y%m%d'), '-', LPAD(e.id, 3, '0')) AS transaction_id"),
            'e.id',
            'e.created_at as transaction_date',
            's.store_name',
            'e.total_amount',
            'e.remaining_amount',
            'e.status'
        )
        ->orderBy('e.created_at', 'desc')
        ->get();
        return $expenses;
    }

    public static function get_transaction_by_id($transaction_id){
        $expenses = DB::table('expenses as e')
        ->join('stores as s', 'e.store_id', '=', 's.id')
        ->select(
            'e.id',
            'e.store_id',
            's.store_name',
            'e.description',
            'e.total_amount',
            'e.remaining_amount',
            'e.status',
            'e.payment_method',
            'e.status as payment_status',
            'e.total_amount',
            'e.remaining_amount',
            'e.created_at',
            'e.updated_at',
            DB::raw("CONCAT('EXP-', DATE_FORMAT(e.created_at, '%Y%m%d'), '-', LPAD(e.id, 3, '0')) as transaction_id")
        )
        ->where('e.id', $transaction_id)   
        ->first();
        return $expenses;
    }
    // PURCHASE
    public static function get_transaction_type_by_id($transaction_id){
        $expenses = DB::table('purchase_orders as po')
        ->join('stores as s', 'po.store_id', '=', 's.id')
        ->select(
            'po.id',
            'po.store_id',   
            's.store_name',
            'po.purchase_date as transaction_date',
            'po.payment_status',
            'po.total_amount',
            'po.payment_method',
            'po.payment_status',
            'po.created_at',
            'po.updated_at',
            DB::raw("CONCAT('PR-', DATE_FORMAT(po.created_at, '%Y%m%d'), '-', LPAD(po.id, 3, '0')) as transaction_id")
        )
        ->where('po.id', $transaction_id)   
        ->first();
        return $expenses;
    }
    public static function get_expense_type_by_id($transaction_id){
        $expenses = DB::table('purchase_expenses as pe')
        ->join('expense_categories as ec', 'pe.expense_category_id', '=', 'ec.id')
        ->select(
            'pe.id',
            'pe.purchase_order_id',
            'pe.expense_category_id',
            'ec.name as category_name',
            'pe.amount',
            'pe.created_at',
            'pe.updated_at'
        )
        ->where('pe.purchase_order_id', $transaction_id)
        ->get();
        return $expenses;
    }
    public static function get_expense_payment_type_by_id($transaction_id){
        $expenses = DB::table('purchase_payments as pp')
        ->leftJoin('bank', 'pp.bank_id', '=', 'bank.id')
        ->select(
            'pp.id',
            'pp.purchase_order_id',
            'pp.amount_paid',
            'pp.payment_method',
            'bank.name as bank_name',
            'bank.account_number',
            'pp.created_at',
            'pp.updated_at'
        )
        ->where('pp.purchase_order_id', $transaction_id)
        ->get();
        return $expenses;
    }   
    
    public static function get_purchase_expense($store_id){
        $totalExpenseAmount = DB::table('purchase_expenses as pe')
        ->join('purchase_orders as po', 'pe.purchase_order_id', '=', 'po.id')
        ->where('po.store_id', $store_id)
        ->sum('pe.amount');
        return $totalExpenseAmount;
    }
    public static function get_purchase_expense_detail($store_id){
        $results = DB::table('purchase_expenses as pe')
        ->join('purchase_orders as po', 'pe.purchase_order_id', '=', 'po.id')
        ->join('stores as s', 'po.store_id', '=', 's.id')
        ->select(
            'po.id',
            'pe.purchase_order_id', 
            'po.payment_status',
            's.store_name',
            'po.purchase_date as transaction_date',
            DB::raw("CONCAT('PR-', DATE_FORMAT(po.created_at, '%Y%m%d'), '-', LPAD(po.id, 3, '0')) AS transaction_id"),
            DB::raw('SUM(pe.amount) AS total_expense_amount')
        )
        ->where('po.store_id', $store_id)
        ->groupBy(
            'po.id',
            'pe.purchase_order_id',
            'po.payment_status', 
            's.store_name',
            'po.purchase_date',
            DB::raw("CONCAT('PR-', DATE_FORMAT(po.created_at, '%Y%m%d'), '-', LPAD(po.id, 3, '0'))")
        )
        ->get();
        return $results;
    }
    
    public static function get_stocktransfer_expense($store_id){
        $totalExpenseAmount = DB::table('stock_transfers as st')
        ->join('stock_transfer_expenses as ste', 'st.id', '=', 'ste.stock_transfer_id')
        ->where('st.from_store_id', $store_id)
        ->sum('ste.amount');
        return $totalExpenseAmount;
    }
    public static function get_stocktransfer_expense_detail($store_id){
        $results = DB::table('stock_transfers as st')
        ->join('stock_transfer_expenses as ste', 'st.id', '=', 'ste.stock_transfer_id')
        ->join('stores as s', 'st.to_store_id', '=', 's.id')
        ->select(
            'st.id',
            'st.from_store_id', 
            'st.to_store_id',
            'st.transfer_date',
            'st.payment_status',
            's.store_name',
            'st.transfer_date as transaction_date',
            DB::raw("CONCAT('ST-', DATE_FORMAT(st.created_at, '%Y%m%d'), '-', LPAD(st.id, 3, '0')) AS transaction_id"),
            DB::raw('SUM(ste.amount) AS total_expense_amount')
        )
        ->where('st.from_store_id', $store_id)
        ->groupBy(
            'st.id',
            'st.from_store_id',
            'st.to_store_id', 
            'st.transfer_date',
            'st.payment_status',
            's.store_name',
            'st.transfer_date',
            'st.created_at'
        )
        ->get();
        return $results;
    }   
    public static function get_other_expense($store_id){
        $totalExpenseAmount = DB::table('expenses as e')
        ->join('expense_payment as ep', 'e.id', '=', 'ep.expense_id')
        ->where('e.store_id', $store_id)
        ->sum('ep.amount_paid');
        return $totalExpenseAmount;
    }
    public static function get_other_expense_detail($store_id){
        $results = DB::table('expenses as e')
        ->leftJoin('expense_payment as ep', 'e.id', '=', 'ep.expense_id')
        ->join('stores as s', 'e.store_id', '=', 's.id')
        ->select(
            'e.id',
            'e.store_id', 
            'e.payment_method',
            'e.status as payment_status',
            'e.remaining_amount',
            's.store_name',
            'e.expense_date as transaction_date',
            DB::raw("CONCAT('EXP-', DATE_FORMAT(e.created_at, '%Y%m%d'), '-', LPAD(e.id, 3, '0')) AS transaction_id"),
            DB::raw('SUM(ep.amount_paid) AS total_expense_amount')
        )
        ->where('e.store_id', $store_id)
        ->groupBy(
            'e.id',
            'e.store_id',
            'e.payment_method', 
            'e.status',
            'e.remaining_amount',
            's.store_name',
            'e.expense_date',
            'e.created_at'
        )
        ->get();
        return $results;
    }
}
