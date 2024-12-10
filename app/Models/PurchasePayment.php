<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class PurchasePayment extends Model
{
    use HasFactory;
    protected $table = 'purchase_payments';
    protected $fillable = [
        'purchase_order_id',
        'payment_date',
        'payment_method',
        'bank_id',
        'amount_paid',
        'payment_note',
    ];


    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public static function get_payment_method($id){
        $paymentMethods = DB::table('purchase_payments as pp')
        ->select(
            'pp.*',
            'b.name as bank_name',
            'b.account_number as bank_account_number'
        )
        ->leftJoin('bank as b', 'b.id', '=', 'pp.bank_id')
        ->where('pp.purchase_order_id', '=', $id)
        ->get();
        return $paymentMethods;
    }
}
