<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseResetController extends Controller
{
    public function index()
    {
        return view('reset-database.index');
    }

    public function reset(Request $request)
    {
        try {
            // Menjalankan query untuk menghapus semua data dari semua tabel
            // Pastikan untuk menyesuaikan dengan nama tabel yang ada di database Anda
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            // Ganti nama_tabel1, nama_tabel2 dengan nama tabel yang sesuai
            DB::table('brands')->truncate();
            DB::table('bank')->truncate();
            DB::table('categories')->truncate();
            DB::table('customers')->truncate();
            DB::table('expenses')->truncate();
            DB::table('expense_categories')->truncate();
            DB::table('expense_items')->truncate();
            DB::table('expense_payment')->truncate();
            DB::table('payment_methods')->truncate();
            DB::table('products')->truncate();
            DB::table('product_category')->truncate();
            DB::table('product_images')->truncate();
            DB::table('product_pricing')->truncate();
            DB::table('purchase_expenses')->truncate();
            DB::table('purchase_orders')->truncate();
            DB::table('purchase_order_detail')->truncate();
            DB::table('purchase_payments')->truncate();
            DB::table('return_purchase')->truncate();
            DB::table('return_purchase_detail')->truncate();
            DB::table('return_sales_transactions')->truncate();
            DB::table('return_sales_transaction_items')->truncate();
            DB::table('sales_transactions')->truncate();
            DB::table('sales_transaction_items')->truncate();
            DB::table('stock_opname')->truncate();
            DB::table('stock_opname_items')->truncate();
            DB::table('stock_opname_reason')->truncate();
            DB::table('stock_transfers')->truncate();
            DB::table('stock_transfer_expenses')->truncate();
            DB::table('stock_transfer_items')->truncate();
            DB::table('stock_transfer_payments')->truncate();
            DB::table('suppliers')->truncate();
            DB::table('units')->truncate();
            DB::table('stores')->truncate();

                       

            // Tambahkan tabel lain yang perlu direset

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Log untuk debugging
            Log::info('Database has been reset successfully.');

            return redirect()->route('reset.database')->with('success', 'Database berhasil direset!');
        } catch (\Exception $e) {
            // Log error jika terjadi kesalahan
            Log::error('Failed to reset database: ' . $e->getMessage());

            return redirect()->route('reset.database')->with('error', 'Gagal mereset database! Detail: ' . $e->getMessage());
        }
    }
}
