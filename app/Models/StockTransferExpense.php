<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class StockTransferExpense extends Model
{
    use HasFactory;
    protected $table = 'stock_transfer_expenses';
    protected $fillable = [
        'stock_transfer_id',
        'expense_category_id',
        'amount',
        'description',
    ];

    public static function get_expenses_by_transaction_id($id)
    {
        return DB::table('stock_transfer_expenses')
            ->join('expense_categories', 'stock_transfer_expenses.expense_category_id', '=', 'expense_categories.id')
            ->where('stock_transfer_expenses.stock_transfer_id', $id)
            ->select('expense_categories.name', 'stock_transfer_expenses.amount', 'stock_transfer_expenses.description', 'stock_transfer_expenses.expense_category_id')
            ->get();
    }

     public static function get_transaction_by_id($transaction_id){
        $expenses = DB::table('stock_transfers as st')
        ->join('stores as s', 'st.to_store_id', '=', 's.id')
        ->select(
            'st.id',
            'st.to_store_id as store_id',   
            's.store_name',
            'st.transfer_date as transaction_date',
            'st.payment_status',
            'st.total_amount',
            'st.created_at',
            'st.updated_at',
            DB::raw("CONCAT('ST-', DATE_FORMAT(st.created_at, '%Y%m%d'), '-', LPAD(st.id, 3, '0')) as transaction_id")
        )
        ->where('st.id', $transaction_id)   
        ->first();
        return $expenses;
    }
    public static function get_expense_detail($transaction_id){
        $expenses = DB::table('stock_transfer_expenses as ste')
        ->join('expense_categories as ec', 'ste.expense_category_id', '=', 'ec.id')
        ->select(
            'ste.id',
            'ste.stock_transfer_id',
            'ste.expense_category_id',
            'ec.name as category_name',
            'ste.amount',
            'ste.created_at',
            'ste.updated_at'
        )
        ->where('ste.stock_transfer_id', $transaction_id)
        ->get();
        return $expenses;
    }
    public static function get_expense_payment($transaction_id){
        $expenses = DB::table('stock_transfer_payments as stp')
        ->leftJoin('bank', 'stp.bank_id', '=', 'bank.id')
        ->select(
            'stp.id',
            'stp.stock_transfer_id',
            'stp.amount_paid',
            'stp.payment_method',
            'bank.name as bank_name',
            'bank.account_number',
            'stp.created_at',
            'stp.updated_at'
        )
        ->where('stp.stock_transfer_id', $transaction_id)
        ->get();
        return $expenses;
    }   
}
