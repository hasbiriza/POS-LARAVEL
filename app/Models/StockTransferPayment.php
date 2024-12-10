<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class StockTransferPayment extends Model
{
    use HasFactory;
    protected $table = 'stock_transfer_payments';
    protected $fillable = [
        'stock_transfer_id',
        'payment_method',
        'bank_id',
        'amount_paid',
        'payment_date',
        'payment_notes'
    ];

    public static function get_payment_method($id){
        $paymentMethods = DB::table('stock_transfer_payments as stp')
        ->select(
            'stp.*',
            'b.name as bank_name',
            'b.account_number as bank_account_number'
        )
        ->leftJoin('bank as b', 'b.id', '=', 'stp.bank_id')
        ->where('stp.stock_transfer_id', '=', $id)
        ->get();
        return $paymentMethods;
    }
}
