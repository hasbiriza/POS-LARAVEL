<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = [
        'supplier_id',
        'reference_no',
        'purchase_date',
        'store_id',
        'payment_method',
        'status',
        'isreturn',
        'payment_status',
        'total_amount',
        'discount',
        'remaining_payment',
    ];
    protected $table = 'purchase_orders';

    public static function get_noreff(){
        $noreff = DB::raw("CONCAT('PR-', DATE_FORMAT(CURDATE(), '%Y%m%d'), '-', LPAD(COALESCE(MAX(id), 0) + 1, 3, '0')) as noreff");
        return Purchase::select($noreff)->first();
    }
    public static function get_transaction() {
        return DB::table('purchase_orders AS po')
            ->leftJoin('purchase_expenses AS pe', 'pe.purchase_order_id', '=', 'po.id')
            ->leftJoin('suppliers AS s', 'po.supplier_id', '=', 's.id')
            ->leftJoin('stores AS st', 'po.store_id', '=', 'st.id')
            ->selectRaw('
                po.id,
                po.reference_no,
                po.purchase_date,
                po.payment_method,
                po.payment_status,
                (po.total_amount - po.discount + IFNULL(SUM(pe.amount), 0)) AS total_purchase,
                po.remaining_payment,
                s.store_name AS supplier_name,
                st.store_name AS store_name,
                po.isreturn
            ')
            ->groupByRaw('
                po.id, 
                po.reference_no, 
                po.purchase_date, 
                po.payment_method, 
                po.payment_status, 
                po.total_amount,
                po.discount,
                po.remaining_payment, 
                s.store_name, 
                st.store_name,
                po.isreturn
            ')
            ->orderBy('po.id', 'desc')
            ->get();
    }
    public static function update_remaining_payment($purchase_id, $payment_amount){
        $purchase = Purchase::find($purchase_id);
        $purchase->remaining_payment = $purchase->remaining_payment - $payment_amount;
        if ($purchase->remaining_payment <= 0) {
            $purchase->payment_status = 'lunas';
            $purchase->remaining_payment = 0;
        }
        $purchase->save();
    }

    public static function get_transaction_by_id($transaction_id){
        $transaction = DB::table('purchase_orders as po')
        ->select(
            'po.*',
            DB::raw("CONCAT('PR-', DATE_FORMAT(po.purchase_date, '%Y%m%d'), '-', LPAD(po.id, 3, '0')) as transaction_id")
        )
        ->where('po.id', '=', $transaction_id)
        ->first();
        return $transaction;
    }

    public static function get_purchase_transactions_id(){
        $purchase_transactions_id = Purchase::select(
            DB::raw("CONCAT('PR-', DATE_FORMAT(purchase_date, '%Y%m%d'), '-', LPAD(id, 3, '0')) as transaction_id"),
            'id'
        )
        ->where(function($query) {
            $query->where('isreturn', '!=', 'yes')
                  ->orWhereNull('isreturn');
        })
        ->pluck('transaction_id', 'id')
        ->all();
        return $purchase_transactions_id;
    }
    
    public static function get_report_filter($store_id = null, $start_date = null, $end_date = null)
    {
        $report = DB::table('purchase_orders as po')
            ->leftJoin('return_purchase as rp', 'po.id', '=', 'rp.purchase_order_id')
            ->leftJoin('purchase_expenses as pe', 'po.id', '=', 'pe.purchase_order_id')
            ->leftJoin('purchase_order_detail as pod', 'po.id', '=', 'pod.purchase_order_id')
            ->leftJoin('stores as s', 'po.store_id', '=', 's.id')
            ->leftJoin('suppliers as su', 's.id', '=', 'su.id')
            ->leftJoin('purchase_payments as pp', 'po.id', '=', 'pp.purchase_order_id')
            ->select(
                DB::raw("CONCAT('PR-', DATE_FORMAT(po.purchase_date, '%Y%m%d'), '-', LPAD(po.id, 3, '0')) AS transaction_id"),
                'po.purchase_date AS transaction_date',
                DB::raw("(po.total_amount - po.discount + IFNULL(SUM(pe.amount), 0)) AS total_purchase"),
                DB::raw("IFNULL(SUM(rp.total_amount - IFNULL(rp.discount, 0)), 0) AS total_return"),
                DB::raw("((po.total_amount - po.discount + IFNULL(SUM(pe.amount), 0)) - IFNULL(SUM(rp.total_amount - IFNULL(rp.discount, 0)), 0)) AS net_total"),
                's.store_name AS store_name',
                'su.store_name AS supplier_name',
                'po.remaining_payment',
                'po.payment_method',
                'po.status',
                DB::raw("IFNULL(SUM(pod.quantity), 0) AS total_quantity"),
                DB::raw("IFNULL(SUM(pp.amount_paid), 0) AS total_amount_paid")
            )
            ->groupBy(
                'po.id', 
                'po.reference_no', 
                'po.purchase_date', 
                'po.total_amount', 
                'po.discount', 
                's.store_name', 
                'su.store_name',
                'su.pic_name',
                'po.remaining_payment',
                'po.payment_method',
                'po.status'
            )
            ->orderBy('po.purchase_date');        
        if ($store_id) {
            $report->where('po.store_id', $store_id);
        }

        if ($start_date) {
            $report->whereDate('po.purchase_date', '>=', $start_date);
        }

        if ($end_date) {
            $report->whereDate('po.purchase_date', '<=', $end_date);
        }
        return $report->get();
    }

}
