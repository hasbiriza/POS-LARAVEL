<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpnameItems extends Model
{
    use HasFactory;
    protected $table = 'stock_opname_items';
    protected $fillable = [
        'stock_opname_id',
        'product_pricing_id',
        'system_stock',
        'physical_stock',
        'stock_difference',
        'unit_price',
        'total_loss',
        'id_reason',
    ];

    public static function get_detail($id)
    {
        return StockOpnameItems::where('stock_opname_id', $id)
            ->join('product_pricing', 'stock_opname_items.product_pricing_id', '=', 'product_pricing.id')
            ->join('products', 'product_pricing.product_id', '=', 'products.id')
            ->select('stock_opname_items.*', 'products.name as product_name', 'product_pricing.sku', 'product_pricing.barcode', 'product_pricing.variasi_1', 'product_pricing.variasi_2', 'product_pricing.variasi_3')
            ->get();
    }

}
