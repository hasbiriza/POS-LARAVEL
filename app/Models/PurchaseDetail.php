<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PurchaseDetail extends Model
{
    use HasFactory;
    protected $table = 'purchase_order_detail';
    protected $fillable = [
        'purchase_order_id',
        'product_pricing_id',
        'purchase_price',
        'diskon',
        'quantity',
        'remaining_quantity',
        'purchase_date',
    ];

    public static function get_items_by_transaction_id($transaction_id){
        $items = DB::table('purchase_order_detail as pod')
        ->select(
            'pod.*',
            'pp.product_id',
            'p.name as product_name',
            'pp.variasi_1',
            'pp.variasi_2',
            'pp.variasi_3',
            'pp.sku',
            'pp.barcode',
            'u.name as unit_name'
        )
        ->join('product_pricing as pp', 'pp.id', '=', 'pod.product_pricing_id')
        ->join('products as p', 'p.id', '=', 'pp.product_id')
        ->join('units as u', 'u.id', '=', 'p.unit_id')
        ->where('pod.purchase_order_id', '=', $transaction_id)
        ->get();
        return $items;
    }

    public static function get_return_items_by_transaction_id($transaction_id){
        $items = DB::table('purchase_order_detail')
        ->join('product_pricing', 'purchase_order_detail.product_pricing_id', '=', 'product_pricing.id')
        ->join('products', 'product_pricing.product_id', '=', 'products.id')
        ->join('units', 'products.unit_id', '=', 'units.id')
        ->select(
            'purchase_order_detail.id as purchase_order_detail_id',
            'products.name as item_name',
            'product_pricing.id as product_pricing_id',
            'product_pricing.variasi_1',
            'product_pricing.variasi_2',
            'product_pricing.variasi_3',
            'units.name as unit_name',
            'purchase_order_detail.quantity',
            'purchase_order_detail.diskon',
            'product_pricing.purchase_price'
        )
        ->where('purchase_order_detail.purchase_order_id', $transaction_id)
        ->get();
        return $items;
    }


}
