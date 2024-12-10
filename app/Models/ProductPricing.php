<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductPricing extends Model
{
    protected $table = 'product_pricing';

    protected $fillable = [
        'product_id',
        'variasi_1',
        'variasi_2',
        'variasi_3',
        'purchase_price',
        'sale_price',
        'stock',
        'weight',
        'store_id',
        'sku',
        'barcode'
    ];

    public function images()
    {
        return $this->hasMany(ProductImages::class, 'product_pricing_id');
    }

    public function store()
    {
        return $this->belongsTo(Stores::class, 'store_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function get_report_filter($store_id = null, $start_date = null, $end_date = null)
    {
            
    
    }
    



}
