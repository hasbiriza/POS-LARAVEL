<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LabaRugi extends Model
{
    use HasFactory;
    
    public static function get_penjualan_barang($storeId = null ,$start_date = null, $end_date = null)
    {
        $query = DB::table('sales_transactions')
            ->where('store_id', $storeId)
            ->select(
                DB::raw('SUM(total_amount - discount_amount + tax_amount) as total_penjualan'),
                DB::raw('SUM(tax_amount) as total_tax')
            );

        if ($start_date) {
            $query->whereDate('transaction_date', '>=', $start_date);
        }

        if ($end_date) {
            $query->whereDate('transaction_date', '<=', $end_date);
        }

        $result = $query->first();

        return [
            'total_penjualan' => $result->total_penjualan ?? 0,
            'total_tax' => $result->total_tax ?? 0
        ];

    }
    
    public static function get_return_penjualan($storeId = null, $start_date = null, $end_date = null)
    {
        $query = DB::table('return_sales_transactions')
            ->select(DB::raw('SUM(total_return - discount_return + tax_return) as total_return_penjualan'))
            ->where('store_id', $storeId);

        if ($start_date) {
            $query->whereDate('return_date', '>=', $start_date);
        }

        if ($end_date) {
            $query->whereDate('return_date', '<=', $end_date);
        }

        return $query->first()->total_return_penjualan ?? 0;

    }
    public static function get_pembelian($storeId = null, $start_date = null, $end_date = null)
    {
        $query = DB::table('purchase_orders')
            ->select(DB::raw('SUM(total_amount - discount) as total_pembelian'))
            ->where('store_id', $storeId);

        if ($start_date) {
            $query->whereDate('purchase_date', '>=', $start_date);
        }

        if ($end_date) {
            $query->whereDate('purchase_date', '<=', $end_date);
        }

        return $query->first()->total_pembelian ?? 0;
    }
    public static function get_retur_pembelian($storeId = null, $start_date = null, $end_date = null)
    {
        $query = DB::table('return_purchase')
            ->select(DB::raw('SUM(total_amount - discount) as total_return_pembelian'))
            ->where('store_id', $storeId);

        if ($start_date) {
            $query->whereDate('return_date', '>=', $start_date);
        }

        if ($end_date) {
            $query->whereDate('return_date', '<=', $end_date);
        }

        return $query->first()->total_return_pembelian ?? 0;
    }
    public static function get_stock_opname($storeId = null, $start_date = null, $end_date = null)
    {
        $query = DB::table('stock_opname_items as soi')
            ->join('stock_opname as so', 'soi.stock_opname_id', '=', 'so.id')
            ->join('stores as s', 'so.store_id', '=', 's.id')
            ->where('so.store_id', $storeId)
            ->select(
                's.store_name',
                DB::raw('SUM(soi.stock_difference * soi.unit_price) AS total_stock_difference_value')
            );

        if ($start_date) {
            $query->whereDate('so.created_at', '>=', $start_date);
        }

        if ($end_date) {
            $query->whereDate('so.created_at', '<=', $end_date);
        }

        $totalStockDifferenceValue = $query->groupBy('so.store_id', 's.store_name')
            ->first();
            
        return $totalStockDifferenceValue->total_stock_difference_value ?? 0;
    }
    
    public static function get_expense_category($storeId = null, $start_date = null, $end_date = null)
    {
        $expenseItemsQuery = DB::table('expense_items as ei')
            ->join('expenses as e', 'ei.expense_id', '=', 'e.id')
            ->join('expense_categories as ec', 'ei.expense_category_id', '=', 'ec.id')
            ->select(
                'ec.name as category_name',
                DB::raw('SUM(ei.amount) as total_amount'),
                DB::raw("'expense_items' as source_table")
            )
            ->where('e.store_id', $storeId);

        if ($start_date) {
            $expenseItemsQuery->whereDate('e.expense_date', '>=', $start_date);
        }

        if ($end_date) {
            $expenseItemsQuery->whereDate('e.expense_date', '<=', $end_date);
        }

        $expenseItemsQuery->groupBy('ei.expense_category_id', 'ec.name');

        $purchaseExpensesQuery = DB::table('purchase_orders as po')
            ->join('purchase_expenses as pe', 'po.id', '=', 'pe.purchase_order_id')
            ->join('expense_categories as ec', 'pe.expense_category_id', '=', 'ec.id')
            ->select(
                'ec.name as category_name',
                DB::raw('SUM(pe.amount) as total_amount'),
                DB::raw("'purchase_expenses' as source_table")
            )
            ->where('po.store_id', $storeId);

        if ($start_date) {
            $purchaseExpensesQuery->whereDate('po.purchase_date', '>=', $start_date);
        }

        if ($end_date) {
            $purchaseExpensesQuery->whereDate('po.purchase_date', '<=', $end_date);
        }

        $purchaseExpensesQuery->groupBy('pe.expense_category_id', 'ec.name');
            
        $stockTransferExpensesQuery = DB::table('stock_transfer_expenses as ste')
            ->join('stock_transfers as st', 'ste.stock_transfer_id', '=', 'st.id')
            ->join('expense_categories as ec', 'ste.expense_category_id', '=', 'ec.id')
            ->select(
                'ec.name as category_name',
                DB::raw('SUM(ste.amount) as total_amount'),
                DB::raw("'stock_transfer_expenses' as source_table")
            )
            ->where(function ($query) use ($storeId) {
                $query->where('st.from_store_id', $storeId);
            });

        if ($start_date) {
            $stockTransferExpensesQuery->whereDate('st.transfer_date', '>=', $start_date);
        }

        if ($end_date) {
            $stockTransferExpensesQuery->whereDate('st.transfer_date', '<=', $end_date);
        }

        $stockTransferExpensesQuery->groupBy('ste.expense_category_id', 'ec.name');
        
        $combinedQuery = $expenseItemsQuery->unionAll($stockTransferExpensesQuery)->unionAll($purchaseExpensesQuery)->get();
        
        return $combinedQuery;        
    }

}
