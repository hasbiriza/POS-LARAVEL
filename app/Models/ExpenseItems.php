<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class ExpenseItems extends Model
{
    use HasFactory;
    protected $table = 'expense_items';
    protected $fillable = [
        'expense_id',
        'expense_category_id',
        'user_id',
        'customer_id',
        'amount',
        'note',
    ];

    public static function get_items_by_transaction_id($transaction_id){
        $items = DB::table('expense_items as ei')
        ->select(
            'ei.*',
            'ec.name as expense_category_name',
            'u.name as user_name',
        )
        ->join('expense_categories as ec', 'ec.id', '=', 'ei.expense_category_id')
        ->join('users as u', 'u.id', '=', 'ei.user_id')
        ->where('ei.expense_id', '=', $transaction_id)
        ->get();
        return $items;
    }

    public static function get_transaction_by_id($transaction_id){
        $expenses = DB::table('expenses as e')
        ->join('stores as s', 's.id', '=', 'e.store_id')
        ->select(
            'e.id',
            'e.store_id',   
            's.store_name',
            'e.expense_date as transaction_date',
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
    public static function get_expense_detail($transaction_id){
        $expenses = DB::table('expense_items as ei')
        ->join('expense_categories as ec', 'ei.expense_category_id', '=', 'ec.id')
        ->select(
            'ei.id',
            'ei.expense_id',
            'ei.expense_category_id',
            'ec.name as category_name',
            'ei.amount',
            'ei.created_at',
            'ei.updated_at'
        )
        ->where('ei.expense_id', $transaction_id)
        ->get();
        return $expenses;
    }
    public static function get_expense_payment($transaction_id){
        $expenses = DB::table('expense_payment as ep')
        ->leftJoin('bank', 'ep.bank_id', '=', 'bank.id')
        ->select(
            'ep.id',
            'ep.expense_id',
            'ep.amount_paid',
            'ep.payment_method',
            'bank.name as bank_name',
            'bank.account_number',
            'ep.created_at',
            'ep.updated_at'
        )
        ->where('ep.expense_id', $transaction_id)
        ->get();
        return $expenses;
    }   
}
