<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class StockTransferItem extends Model
{
    use HasFactory;
    protected $table = 'stock_transfer_items';
    protected $fillable = [
        'stock_transfer_id',
        'product_pricing_id',
        'quantity',
        'price',
        'quantity_received',
    ];

    public static function get_items_by_transaction_id($transaction_id){
        $items = DB::table('stock_transfer_items as sti')
        ->select(
            'sti.*',
            'pp.product_id',
            'p.name as product_name',
            'pp.variasi_1',
            'pp.variasi_2',
            'pp.variasi_3',
            'pp.sku',
            'pp.barcode',
            'pp.purchase_price',
            'u.name as unit_name'
        )
        ->join('product_pricing as pp', 'pp.id', '=', 'sti.product_pricing_id')
        ->join('products as p', 'p.id', '=', 'pp.product_id')
        ->join('units as u', 'u.id', '=', 'p.unit_id')
        ->where('sti.stock_transfer_id', '=', $transaction_id)
        ->get();
        return $items;
    }

    public static function get_item_to_delete($id){
        $items = DB::table('stock_transfer_items')
        ->join('product_pricing', 'stock_transfer_items.product_pricing_id', '=', 'product_pricing.id')
        ->where('stock_transfer_id', $id)
        ->select('stock_transfer_items.quantity', 'product_pricing.sku', 'product_pricing.store_id', 'product_pricing.barcode')
        ->get();
        return $items;
    }
}
