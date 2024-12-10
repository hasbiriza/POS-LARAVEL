<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StockReport extends Model
{
    use HasFactory;

    public static function get_report_filter($store_id) {
        $query = DB::table('products as p')
            ->join('product_pricing as pp', 'p.id', '=', 'pp.product_id')
            ->join('stores as s', 'pp.store_id', '=', 's.id')
            ->leftJoin('purchase_order_detail as pod', 'pp.id', '=', 'pod.product_pricing_id')
            ->leftJoin('sales_transaction_items as sti', 'pp.id', '=', 'sti.product_pricing_id')
            ->leftJoin('stock_transfer_items as st_out', 'pp.id', '=', 'st_out.product_pricing_id')
            ->leftJoin('stock_transfers as sto_out', function($join) {
                $join->on('st_out.stock_transfer_id', '=', 'sto_out.id')
                    ->whereColumn('sto_out.from_store_id', 's.id');
            })
            ->leftJoin('stock_transfer_items as st_in', 'pp.id', '=', 'st_in.product_pricing_id')
            ->leftJoin('stock_transfers as sto_in', function($join) {
                $join->on('st_in.stock_transfer_id', '=', 'sto_in.id')
                    ->whereColumn('sto_in.to_store_id', 's.id');
            })
            ->leftJoin('stock_opname_items as soi', 'pp.id', '=', 'soi.product_pricing_id')
            ->where('s.id', $store_id);
    
        $products = $query->select(
                'p.name as product_name',
                'pp.variasi_1',
                'pp.variasi_2',
                'pp.variasi_3',
                'pp.sku',
                'pp.barcode',
                's.store_name',
                'pp.stock as initial_stock',
                DB::raw('COALESCE(SUM(CASE WHEN pod.purchase_order_id IS NOT NULL THEN pod.quantity ELSE 0 END), 0) as purchased_stock'),
                DB::raw('COALESCE(SUM(CASE WHEN sti.transaction_id IS NOT NULL THEN sti.quantity ELSE 0 END), 0) as sold_stock'),
                DB::raw('COALESCE(SUM(CASE WHEN sto_out.from_store_id = s.id THEN st_out.quantity ELSE 0 END), 0) as transfer_out_stock'),
                DB::raw('COALESCE(SUM(CASE WHEN sto_in.to_store_id = s.id THEN st_in.quantity ELSE 0 END), 0) as transfer_in_stock'),
                DB::raw('COALESCE(SUM(CASE WHEN soi.stock_opname_id IS NOT NULL THEN soi.stock_difference ELSE 0 END), 0) as stock_difference'),
                'pp.sale_price as unit_price'
            )
            ->groupBy('pp.sku', 'pp.barcode', 's.store_name', 'pp.stock', 'pp.sale_price', 'p.name', 'pp.variasi_1', 'pp.variasi_2', 'pp.variasi_3')
            ->orderBy('p.name')
            ->get();
        
        return $products;
    }
    
}
