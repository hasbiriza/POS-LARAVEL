<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'unit_id',
        'brand_id',
        'has_varian'
    ];


public static function getProductsWithDetails($storeIds)
{
    return DB::table('products as p')
    ->select(
        'p.id',
        'p.name',
        'p.description',
        'p.has_varian',
        DB::raw("
            CASE 
                WHEN p.has_varian = 'Y' THEN 
                    CONCAT(
                        MIN(pp.purchase_price), ' - ', MAX(pp.purchase_price)
                    )
                ELSE 
                    (SELECT purchase_price FROM product_pricing WHERE product_id = p.id LIMIT 1)
            END AS purchase_price
        "),
        DB::raw("
            CASE 
                WHEN p.has_varian = 'Y' THEN 
                    CONCAT(
                        MIN(pp.sale_price), ' - ', MAX(pp.sale_price)
                    )
                ELSE 
                    (SELECT sale_price FROM product_pricing WHERE product_id = p.id LIMIT 1)
            END AS sale_price
        "),
        'u.name as unit_name',
        'b.name as brand_name',
        DB::raw("
            CASE 
                WHEN p.has_varian = 'Y' THEN 
                    (SELECT SUM(stock) FROM product_pricing WHERE product_id = p.id)
                ELSE 
                    (SELECT stock FROM product_pricing WHERE product_id = p.id LIMIT 1)
            END AS total_stock
        "),
        'cat.categories'
    )
    ->leftJoin('product_pricing as pp', 'pp.product_id', '=', 'p.id')
    ->leftJoin('units as u', 'p.unit_id', '=', 'u.id')
    ->leftJoin('brands as b', 'p.brand_id', '=', 'b.id')
    ->leftJoin(DB::raw('
        (
            SELECT 
                pc.product_id, 
                GROUP_CONCAT(c.name SEPARATOR ", ") AS categories
            FROM 
                product_category pc
            LEFT JOIN 
                categories c ON c.id = pc.category_id
            GROUP BY 
                pc.product_id
        ) as cat
    '), 'cat.product_id', '=', 'p.id')
    ->groupBy('p.id', 'p.name', 'p.description', 'p.has_varian', 'u.name', 'b.name', 'cat.categories')
    ->whereIn('pp.store_id', $storeIds)
    ->orderBy('p.id')
    ->get();
}

public function variants()
{
    return $this->hasMany(ProductPricing::class);
}
public function categories()
{
    return $this->belongsToMany(Categories::class, 'product_category', 'product_id', 'category_id');
}
public function brand()
{
    return $this->belongsTo(Brands::class);
}
public function unit()
{
    return $this->belongsTo(Units::class);
}

public static function cart_products(){
    $products = DB::table('product_pricing as pp')
    ->join('products as p', 'pp.product_id', '=', 'p.id')
    ->leftJoin('product_images as pi', function ($join) {
        $join->on('pi.product_pricing_id', '=', 'pp.id')
             ->where('pi.id', '=', DB::raw('(SELECT MIN(id) FROM product_images WHERE product_pricing_id = pp.id)'));
    })
    ->select('p.name as product_name','pp.id','p.has_varian', 'pp.sale_price', 'pp.variasi_1 as variant1', 'pp.variasi_2 as variant2', 'pp.variasi_3 as variant3', 'pi.image_url')
    ->orderBy('pp.id')
    ->get();
    return $products;
}

    public static function user_products_default($userId, $store_id){
        $products = DB::table('product_pricing as pp')
        ->join('products as p', 'pp.product_id', '=', 'p.id')
        ->join('stores as s', 'pp.store_id', '=', 's.id')
        ->join('store_user as us', 's.id', '=', 'us.store_id')
        ->leftJoin('product_images as pi', function ($join) {
            $join->on('pi.product_pricing_id', '=', 'pp.id')
                ->where('pi.id', '=', DB::raw('(SELECT MIN(id) FROM product_images WHERE product_pricing_id = pp.id)'));
        })
        ->select('p.name as product_name','pp.id','p.has_varian', 'pp.sale_price','pp.stock' ,'pp.variasi_1 as variant1', 'pp.variasi_2 as variant2', 'pp.variasi_3 as variant3', 'pi.image_url')
        ->where('us.user_id', $userId)
        ->where('pp.store_id', $store_id)
        ->orderBy('pp.id')
        ->get();
        return $products;
    }

    public static function get_products_by_store($store_id){
        $products = DB::table('product_pricing as pp')
        ->join('products as p', 'pp.product_id', '=', 'p.id')
        ->join('units as u', 'p.unit_id', '=', 'u.id')
        ->select('p.name as product_name','pp.id','p.has_varian', 'pp.sale_price','pp.stock' ,'pp.variasi_1 as variant1', 'pp.variasi_2 as variant2', 'pp.variasi_3 as variant3', 'u.name as unit_name')
        ->where('pp.store_id', $store_id)
        ->orderBy('pp.id')
        ->get();
        return $products;
    }

    public static function getProductsWithVariant($id)
    {
        $product = DB::table('products as p')
            ->join('product_pricing as pp', 'p.id', '=', 'pp.product_id')
            ->select('p.name as product_name','pp.stock', 'pp.id as pricing_id', 'p.id as product_id', 'p.has_varian', 'pp.sale_price', 'pp.variasi_1 as variant1', 'pp.variasi_2 as variant2', 'pp.variasi_3 as variant3')
            ->where('pp.id', $id)
            ->first();
        return $product;
    }

    public static function searchProduct($name){
        $products = DB::table('product_pricing as pp')
            ->join('products as p', 'p.id', '=', 'pp.product_id')
            ->select('pp.id as product_pricing_id', 'p.name as product_name', 'pp.variasi_1', 'pp.variasi_2', 'pp.variasi_3', 'pp.purchase_price', 'pp.sale_price','pp.sku','pp.barcode')
            ->where('p.name', 'like', '%' . $name . '%')
            ->get();
        return $products;
    }

    public static function searchProduct_by_store($store_id, $name){
        $products = DB::table('product_pricing as pp')
            ->join('products as p', 'pp.product_id', '=', 'p.id')
            ->leftJoin('product_images as pi', function ($join) {
                $join->on('pi.product_pricing_id', '=', 'pp.id')
                    ->where('pi.id', '=', DB::raw('(SELECT MIN(id) FROM product_images WHERE product_pricing_id = pp.id)'));
            })
            ->select('p.name as product_name','pp.stock','pp.id','p.has_varian', 'pp.sale_price', 'pp.variasi_1 as variant1', 'pp.variasi_2 as variant2', 'pp.variasi_3 as variant3', 'pi.image_url')
            ->where(function ($query) use ($name) {
                $query->where('p.name', 'like', '%' . $name . '%')
                      ->orWhere('pp.sku', 'like', '%' . $name . '%')
                      ->orWhere('pp.barcode', 'like', '%' . $name . '%');
            })
            ->where('pp.store_id', $store_id)
            ->orderBy('pp.id')
            ->get();
        return $products;
    }

    public static function searchProduct_purchase($name, $store_id){
        $products = DB::table('product_pricing as pp')
            ->join('products as p', 'p.id', '=', 'pp.product_id')
            ->join('units as u', 'p.unit_id', '=', 'u.id')
            ->select('pp.id as product_pricing_id', 'p.name as product_name', 'pp.variasi_1', 'pp.variasi_2', 'pp.variasi_3', 'pp.purchase_price', 'pp.sale_price','pp.sku','pp.barcode', 'u.name as unit_name')
            ->where('p.name', 'like', '%' . $name . '%')
            ->where('pp.store_id', $store_id)
            ->get();
        return $products;
    }
    
    public static function searchProduct_stocktransfer($name, $store_id){
        $products = DB::table('product_pricing as pp')
            ->join('products as p', 'p.id', '=', 'pp.product_id')
            ->join('units as u', 'p.unit_id', '=', 'u.id')
            ->select('pp.id as product_pricing_id', 'p.name as product_name', 'pp.variasi_1', 'pp.variasi_2', 'pp.variasi_3', 'pp.purchase_price', 'pp.sale_price', 'pp.sku', 'pp.barcode', 'pp.stock', 'u.name as unit_name')
            ->where('p.name', 'like', '%' . $name . '%')
            ->where('pp.store_id', $store_id)
            ->get();
        return $products;
    }

}
