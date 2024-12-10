<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\SalesTransactionItems;
use App\Models\ProductPricing;

class SalesTransactions extends Model
{
    use HasFactory;
    protected $table = 'sales_transactions';

    protected $fillable = [
        'transaction_date',
        'store_id',
        'customer_id',
        'total_amount',
        'discount_amount',
        'tax_amount',
        'payment_method',
        'user_id',
        'total_payment',
        'change_payment',
        'remaining_payment',
        'status',
        'note',
        'isreturn'
    ];

    public function items()
    {
        return $this->hasMany(SalesTransactionItems::class, 'transaction_id', 'id');
    }

    public static function get_transaction(){
        $transactions = DB::table('sales_transactions as st')
        ->join('customers as c', 'st.customer_id', '=', 'c.id')
        ->join('stores as s', 'st.store_id', '=', 's.id')
        ->join('users as u', 'st.user_id', '=', 'u.id')
        ->join('model_has_roles as mhr', 'u.id', '=', 'mhr.model_id')
        ->join('roles as r', 'mhr.role_id', '=', 'r.id')
        ->select(
            DB::raw("CONCAT('INV-', DATE_FORMAT(st.transaction_date, '%Y%m%d'), '-', LPAD(st.id, 3, '0')) as transaction_id"),
            'st.id as transaction_id_ori',
            'st.transaction_date',
            'st.status',
            'st.total_amount',
            'st.discount_amount',
            'st.tax_amount',
            'st.total_payment',
            'st.change_payment',
            'st.remaining_payment',
            'st.payment_method',
            'st.isreturn',
            'c.name as customer_name',
            's.store_name as store_name',
            'u.name as kasir_name'
        )
        ->where('r.name', '=', 'kasir')
        ->get();
        return $transactions;
    }
    
    public static function get_transaction_by_id($transaction_id){
        $transaction = DB::table('sales_transactions as st')
        ->select(
            'st.*',
            DB::raw("CONCAT('INV-', DATE_FORMAT(st.transaction_date, '%Y%m%d'), '-', LPAD(st.id, 3, '0')) as transaction_id")
        )
        ->where('st.id', '=', $transaction_id)
        ->first();
        return $transaction;
    }


    

    public static function update_remaining_payment($transaction_id, $amount){
        $transaction = SalesTransactions::find($transaction_id);
        $transaction->remaining_payment = $transaction->remaining_payment - $amount;
        $transaction->total_payment = $transaction->total_payment + $amount;
        
        if ($transaction->remaining_payment <= 0) {
            $transaction->status = 'lunas';
            
            if ($transaction->remaining_payment < 0) {
                $transaction->change_payment = abs($transaction->remaining_payment);
                $transaction->remaining_payment = 0;
            }
        }
        
        $transaction->save();
    }

    public static function get_sales_transactions_id(){
        $sales_transactions_id = SalesTransactions::select(
            DB::raw("CONCAT('INV-', DATE_FORMAT(transaction_date, '%Y%m%d'), '-', LPAD(id, 3, '0')) as transaction_id"),
            'id'
        )
        ->where(function($query) {
            $query->where('isreturn', '!=', 'yes')
                  ->orWhereNull('isreturn');
        })
        ->pluck('transaction_id', 'id')
        ->all();
        return $sales_transactions_id;
    }

    public static function get_report_filter($store_id = null, $start_date = null, $end_date = null)
    {
        $query = DB::table('sales_transactions as st')
        ->selectRaw("
            CONCAT('INV-', DATE_FORMAT(st.transaction_date, '%Y%m%d'), '-', LPAD(st.id, 3, '0')) AS transaction_id,
            st.transaction_date,
            (st.total_amount - st.discount_amount + st.tax_amount) AS total_sales_after_discount_tax,
            IFNULL(SUM(rst.total_return - rst.discount_return + rst.tax_return), 0) AS total_sales_return,
            ((st.total_amount - st.discount_amount + st.tax_amount) - IFNULL(SUM(rst.total_return), 0)) AS net_total,
            st.total_payment,
            st.payment_method,
            st.remaining_payment,
            st.status,
            customers.name AS customer_name,
            stores.store_name AS store_name,
            users.name AS user_name,
            IFNULL(SUM(sti.quantity), 0) AS total_qty
        ")
        ->leftJoin('return_sales_transactions as rst', 'st.id', '=', 'rst.sales_transaction_id')
        ->leftJoin('customers', 'st.customer_id', '=', 'customers.id')
        ->leftJoin('stores', 'st.store_id', '=', 'stores.id')
        ->leftJoin('users', 'st.user_id', '=', 'users.id')
        ->leftJoin('sales_transaction_items as sti', 'st.id', '=', 'sti.transaction_id')
        ->groupBy('st.id', 'st.transaction_date', 'st.total_amount', 'st.discount_amount', 
                 'st.tax_amount', 'st.total_payment', 'st.payment_method', 'st.remaining_payment', 
                 'st.status', 'customers.name', 'stores.store_name', 'users.name')
        ->orderByDesc('st.id');    

        if ($store_id) {
            $query->where('st.store_id', $store_id);
        }

        if ($start_date) {
            $query->whereDate('st.transaction_date', '>=', $start_date);
        }

        if ($end_date) {
            $query->whereDate('st.transaction_date', '<=', $end_date);
        }
        // $results = $query->get();
        // Log::info('Report Results:', $results->toArray());
    
        return $query->get();
    }

    public static function delete_transaction($transaction_id){
        $transactionItems = SalesTransactionItems::where('transaction_id', $transaction_id)
            ->select('product_pricing_id', 'quantity')
            ->get();

        foreach ($transactionItems as $item) {
            ProductPricing::where('id', $item->product_pricing_id)
                ->increment('stock', $item->quantity);
        }
        SalesTransactions::where('id', $transaction_id)->delete();
    }

    

}
