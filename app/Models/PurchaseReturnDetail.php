<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class PurchaseReturnDetail extends Model
{
    use HasFactory;
    protected $table = 'return_purchase_detail';

    protected $fillable = [
        'return_purchase_id',
        'purchase_order_detail_id',
        'product_pricing_id',
        'quantity_purchased',
        'quantity_returned',
        'price',
        'discount_item',
        'subtotal',
    ];

    public static function get_items_by_transaction_id($returnPurchaseId)
    {
        return DB::table('return_purchase_detail as rpd')
            ->join('product_pricing as pp', 'rpd.product_pricing_id', '=', 'pp.id')
            ->join('products as p', 'pp.product_id', '=', 'p.id')
            ->select(
                'p.name as product_name',
                'pp.variasi_1',
                'pp.variasi_2',
                'pp.variasi_3',
                'rpd.id as return_purchase_detail_id',
                'rpd.quantity_purchased',
                'rpd.quantity_returned',
                'rpd.discount_item',
                'rpd.price',
                'rpd.subtotal'
            )
            ->where('rpd.return_purchase_id', $returnPurchaseId)
            ->get();
    }
}
