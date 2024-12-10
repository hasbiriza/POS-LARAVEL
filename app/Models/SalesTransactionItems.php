<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SalesTransactionItems extends Model
{
    use HasFactory;
    protected $table = 'sales_transaction_items';

    protected $fillable = [
        'transaction_id',
        'product_pricing_id',
        'quantity',
        'price',
        'discount_item',
        'subtotal'
    ];

    public static function get_items_by_transaction_id($transaction_id){
        $items = DB::table('sales_transaction_items')
        ->join('product_pricing', 'sales_transaction_items.product_pricing_id', '=', 'product_pricing.id')
        ->join('products', 'product_pricing.product_id', '=', 'products.id')
        ->join('units', 'products.unit_id', '=', 'units.id')
        ->select(
            'sales_transaction_items.id as sales_transaction_items_id',
            'products.name as item_name',
            'product_pricing.id as product_pricing_id',
            'product_pricing.variasi_1',
            'product_pricing.variasi_2',
            'product_pricing.variasi_3',
            'units.name as unit_name',
            'sales_transaction_items.quantity',
            'sales_transaction_items.discount_item',
            'product_pricing.sale_price'
        )
        ->where('sales_transaction_items.transaction_id', $transaction_id)
        ->get();
        return $items;
    }
}
