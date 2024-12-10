<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class PaymentMethod extends Model
{
    use HasFactory;
    protected $table = 'payment_methods';
    protected $fillable = [
        'transaction_id',
        'payment_method',
        'bank_id',
        'amount',
        'payment_date',
    ];

    public static function get_payment_method($transaction_id){
        $paymentMethods = DB::table('payment_methods as pm')
        ->leftJoin('bank as b', 'pm.bank_id', '=', 'b.id')
        ->where('pm.transaction_id', $transaction_id)
        ->select('pm.*', 'b.name as bank_name', 'b.account_number as bank_account_number')
        ->get();
        return $paymentMethods;
    }
    
    public static function get_bank_by_transaction_id($transaction_id){
        $bank = DB::table('payment_methods as pm')
        ->leftJoin('bank as b', 'pm.bank_id', '=', 'b.id')
        ->where('pm.transaction_id', $transaction_id)
        ->select('pm.*', 'b.name as bank_name', 'b.account_number as bank_account_number')
        ->first();
        return $bank;
    }
}
