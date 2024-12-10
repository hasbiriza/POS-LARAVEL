<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StockOpname extends Model
{
    use HasFactory;
    protected $table = 'stock_opname';
    protected $fillable = [
        'store_id',
        'date',
        'user_id',
        'note',
    ];

    public static function get_noreff(){
        $noreff = DB::raw("CONCAT('SO-', DATE_FORMAT(CURDATE(), '%Y%m%d'), '-', LPAD(COALESCE(MAX(id), 0) + 1, 3, '0')) as noreff");
        return StockOpname::select($noreff)->first();
    }

    public static function get_transaction(){

        $stockOpnameData = DB::table('stock_opname AS so')
            ->select([
                DB::raw("CONCAT('SO-', DATE_FORMAT(CURDATE(), '%Y%m%d'), '-', LPAD(COALESCE(MAX(so.id), 0) + 1, 3, '0')) AS noreff"),
                'so.id',
                's.store_name AS toko',
                'so.date AS tanggal',
                DB::raw('COUNT(soi.id) AS total_item'),
                'so.note AS catatan'
            ])
            ->join('stores AS s', 'so.store_id', '=', 's.id')
            ->leftJoin('stock_opname_items AS soi', 'so.id', '=', 'soi.stock_opname_id')
            ->groupBy('so.id', 's.store_name', 'so.date', 'so.note')
            ->orderBy('so.date', 'desc')
            ->get();
        return $stockOpnameData;
    }

    public static function get_transaction_by_id($id)
    {
            return StockOpname::where('stock_opname.id', $id)
                ->join('stores', 'stock_opname.store_id', '=', 'stores.id')
                ->join('users', 'stock_opname.user_id', '=', 'users.id')
                ->select('stock_opname.*', 'stores.store_name', 'users.name as user_name', 
                         DB::raw("CONCAT('SO-', DATE_FORMAT(stock_opname.date, '%Y%m%d'), '-', LPAD(stock_opname.id, 3, '0')) as reference_no"))
                ->first();
    }

}
