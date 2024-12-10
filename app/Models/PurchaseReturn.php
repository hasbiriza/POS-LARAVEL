<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PurchaseReturn extends Model
{
    use HasFactory;

    protected $table = 'return_purchase';
    protected $fillable = [
        'return_no',
        'purchase_order_id',
        'user_id',
        'supplier_id',
        'store_id',
        'return_date',
        'total_amount',
        'discount',
        'note'
    ];

    public static function get_return_purchase_transactions_id(){
        $return_number = DB::raw("CONCAT('IR-', DATE_FORMAT(CURDATE(), '%Y%m%d'), '-', LPAD(COALESCE(MAX(id), 0) + 1, 3, '0')) as return_number");
        return PurchaseReturn::select($return_number)->first();
    }
    public static function get_return_purchase_transactions(){
        return DB::table('return_purchase')
            ->join('stores', 'return_purchase.store_id', '=', 'stores.id')
            ->join('suppliers', 'return_purchase.supplier_id', '=', 'suppliers.id')
            ->join('purchase_orders', 'return_purchase.purchase_order_id', '=', 'purchase_orders.id')
            ->select(
                'return_purchase.*',
                'stores.store_name',
                'suppliers.store_name as supplier_name',
                DB::raw("CONCAT('PR-', DATE_FORMAT(purchase_orders.purchase_date, '%Y%m%d'), '-', LPAD(purchase_orders.id, 3, '0')) as invoice_no")
            )
            ->orderBy('return_purchase.id', 'desc')
            ->get();
    }
}
