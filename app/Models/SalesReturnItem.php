<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SalesReturnItem extends Model
{
    use HasFactory;
    protected $table = 'return_sales_transaction_items';
    protected $fillable = [
        'return_sales_transaction_id',
        'sales_transaction_item_id',
        'product_pricing_id',
        'quantity_sold',
        'quantity_returned',
        'price',
        'discount_item',
        'subtotal',
    ];

    public static function get_items_by_transaction_id($returnSalesTransactionId)
    {
        return DB::table('return_sales_transaction_items as rsti')
            ->join('product_pricing as pp', 'rsti.product_pricing_id', '=', 'pp.id')
            ->join('products as p', 'pp.product_id', '=', 'p.id')
            ->select(
                'p.name as product_name',
                'pp.variasi_1',
                'pp.variasi_2',
                'pp.variasi_3',
                'rsti.id as return_sales_transaction_item_id',
                'rsti.quantity_sold',
                'rsti.quantity_returned',
                'rsti.discount_item',
                'rsti.price',
                'rsti.subtotal'
            )
            ->where('rsti.return_sales_transaction_id', $returnSalesTransactionId)
            ->get();
    }
}
