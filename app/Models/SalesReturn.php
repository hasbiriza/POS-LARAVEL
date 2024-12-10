<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SalesReturn extends Model
{
    use HasFactory;
    protected $table = 'return_sales_transactions';
    protected $fillable = [
        'return_no',
        'return_date',
        'sales_transaction_id',
        'store_id',
        'customer_id',
        'total_return',
        'discount_return',
        'tax_return',
        'note',
    ];

    public static function get_return_sales_transactions_id(){
        $return_number = DB::raw("CONCAT('NR-', DATE_FORMAT(CURDATE(), '%Y%m%d'), '-', LPAD(COALESCE(MAX(id), 0) + 1, 3, '0')) as return_number");
        return SalesReturn::select($return_number)->first();
    }

    public static function get_return_sales_transactions(){
    return DB::table('return_sales_transactions')
        ->join('stores', 'return_sales_transactions.store_id', '=', 'stores.id')
        ->join('customers', 'return_sales_transactions.customer_id', '=', 'customers.id')
        ->join('sales_transactions', 'return_sales_transactions.sales_transaction_id', '=', 'sales_transactions.id')
        ->select(
            'return_sales_transactions.*',
            'stores.store_name',
            'customers.name as customer_name',
            DB::raw("CONCAT('INV-', DATE_FORMAT(sales_transactions.transaction_date, '%Y%m%d'), '-', LPAD(sales_transactions.id, 3, '0')) as invoice_no")
        )
        ->orderBy('return_sales_transactions.id', 'desc')
        ->get();
    }
 
}
