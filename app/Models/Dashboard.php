<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dashboard extends Model
{
    use HasFactory;    

    public static function getAllStores()
    {
        return DB::table('stores')
            ->select('id', 'store_name')
            ->orderBy('store_name')
            ->get();
    }
    public static function getTotalSalesAll()
    {
        return DB::table('sales_transactions')
            ->selectRaw('SUM(total_amount - discount_amount + tax_amount) as total_sales')
            ->value('total_sales');
    }
    public static function getTotalPurchaseAll()
    {
        return DB::table('purchase_orders')
            ->selectRaw('SUM(total_amount - discount) as total_purchase')
            ->value('total_purchase');
    }

    public static function getTotalReturnAll()
    {
        return DB::table('return_sales_transactions')
            ->selectRaw('SUM(total_return - discount_return + tax_return) as total_return')
            ->value('total_return');
    }

    public static function getTotalExpenseAll()
    {
        return DB::table('expenses')
            ->selectRaw('SUM(total_amount) as total_expense')
            ->unionAll(DB::table('purchase_expenses')
                ->selectRaw('SUM(amount) as total_expense'))
            ->unionAll(DB::table('stock_transfer_expenses')
                ->selectRaw('SUM(amount) as total_expense'))
            ->sum('total_expense');
    }
    public static function getSalesChart()
    {
        $year = $year ?? date('Y');
        $months = range(1, 12);
        
        $stores = DB::table('stores')->get();
        
        $results = [];
        
            foreach ($stores as $store) {
                foreach ($months as $month) {
                    $totalSales = DB::table('sales_transactions')
                        ->where('store_id', $store->id)
                        ->whereYear('transaction_date', $year)
                        ->whereMonth('transaction_date', $month)
                        ->sum('total_amount');
        
                    $results[] = [
                        'store_name' => $store->store_name,
                        'month' => $month,
                        'total_sales' => $totalSales ?? 0
                    ];
                }
            }
        
        return $results;
    }
 
    public static function getSalesChartDaily($startDate, $endDate, $storeId = null)
    {
        $stores = DB::table('stores')->get();
        
        $results = [];
        
        $totalDaysInMonth = (int) now()->daysInMonth; // Jumlah hari dalam bulan berjalan
        
        foreach ($stores as $store) {
            // Ambil penjualan harian untuk setiap toko
            $dailySales = DB::table('sales_transactions')
                ->select(DB::raw('DATE(transaction_date) as date'), DB::raw('SUM(total_amount) as total_sales'))
                ->where('store_id', $store->id)
                ->whereBetween(DB::raw('DATE(transaction_date)'), [$startDate, $endDate])
                ->groupBy(DB::raw('DATE(transaction_date)'))
                ->get();
            
            // Format hasilnya agar bisa digunakan di chart
            $formattedSales = array_fill(0, $totalDaysInMonth, 0); // Sesuaikan dengan jumlah hari aktual
            
            foreach ($dailySales as $sale) {
                $dayIndex = (int) date('d', strtotime($sale->date)) - 1; // Indeks mulai dari 1 sampai jumlah hari di bulan tersebut
                $formattedSales[$dayIndex] = $sale->total_sales;
            }
            
            // Masukkan ke hasil final
            $results[$store->store_name] = $formattedSales;
        }
        
        return $results;
    }
    
    
    
 
}
