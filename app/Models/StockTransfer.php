<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StockTransfer extends Model
{
    use HasFactory;
    protected $table = 'stock_transfers';
    protected $fillable = [
        'user_id',
        'from_store_id',
        'to_store_id',
        'shipping_status',
        'transfer_date',
        'payment_method',
        'payment_status',
        'total_amount',
        'discount',
        'remaining_payment',
        'received_at',
        'received_by',
    ];

    public static function get_noreff(){
        $noreff = DB::raw("CONCAT('ST-', DATE_FORMAT(CURDATE(), '%Y%m%d'), '-', LPAD(COALESCE(MAX(id), 0) + 1, 3, '0')) as noreff");
        return StockTransfer::select($noreff)->first();
    }

    public static function get_transaction($storeIds)
    {
        $transfers = DB::table('stock_transfers as st')
            ->selectRaw("
                CONCAT('ST-', DATE_FORMAT(CURDATE(), '%Y%m%d'), '-', LPAD(st.id, 3, '0')) AS no_reff,
                st.id,
                st.transfer_date AS tgl_transfer,
                fs.store_name AS from_store,
                ts.store_name AS to_store,
                st.payment_method,
                st.payment_status,
                st.remaining_payment,
                st.shipping_status AS status,
                u.name AS penerima,
                (st.total_amount + IFNULL(ste.total_expenses, 0)) AS total_all
            ")
            ->leftJoin('stores as fs', 'st.from_store_id', '=', 'fs.id')
            ->leftJoin('stores as ts', 'st.to_store_id', '=', 'ts.id')
            ->leftJoin('users as u', 'st.received_by', '=', 'u.id')
            ->leftJoin(
                DB::raw('(SELECT stock_transfer_id, SUM(amount) AS total_expenses FROM stock_transfer_expenses GROUP BY stock_transfer_id) as ste'),
                'st.id',
                '=',
                'ste.stock_transfer_id'
            )
            ->whereIn('st.from_store_id', $storeIds)
            ->orderBy('st.transfer_date', 'desc')
            ->get();

        return $transfers;
    }

    public static function get_transaction_by_id($transaction_id){
        $stockTransfers = DB::table('stock_transfers as st')
        ->join('users as u', 'st.user_id', '=', 'u.id')
        ->join('stores as from_store', 'st.from_store_id', '=', 'from_store.id')
        ->join('stores as to_store', 'st.to_store_id', '=', 'to_store.id')
        ->select(
            'st.id',
            'st.from_store_id',
            'st.to_store_id',
            'u.name as user_name',
            'from_store.store_name as from_store_name',
            'to_store.store_name as to_store_name',
            'st.shipping_status',
            'st.transfer_date',
            'st.payment_method',
            'st.payment_status',
            'st.total_amount',
            'st.discount',
            'st.remaining_payment',
            'st.received_at',
            'st.created_at',
            'st.updated_at',
            DB::raw("CONCAT('ST-', DATE_FORMAT(st.transfer_date, '%Y%m%d'), '-', LPAD(st.id, 3, '0')) as transaction_id")
        )
        ->where('st.id', $transaction_id)   
        ->first();
        return $stockTransfers;
    }
    
}
