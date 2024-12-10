<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class ExpensePayment extends Model
{
    use HasFactory;
    protected $table = 'expense_payment';
    protected $fillable = [
        'expense_id',
        'payment_method',
        'bank_id',
        'amount_paid',
        'payment_date',
        'payment_note',
    ];

    public static function get_payment_method($id){
        $paymentMethods = DB::table('expense_payment as ep')
        ->select(
            'ep.*',
            'b.name as bank_name',
            'b.account_number as bank_account_number'
        )
        ->leftJoin('bank as b', 'b.id', '=', 'ep.bank_id')
        ->where('ep.expense_id', '=', $id)
        ->get();
        return $paymentMethods;
    }
}
