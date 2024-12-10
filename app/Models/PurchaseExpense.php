<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class PurchaseExpense extends Model
{
    use HasFactory;
    protected $table = 'purchase_expenses';
    protected $fillable = [
        'purchase_order_id',
        'expense_category_id',
        'amount',
        'note',
    ];
    public static function get_expenses_by_transaction_id($id)
    {
        return DB::table('purchase_expenses')
            ->join('expense_categories', 'purchase_expenses.expense_category_id', '=', 'expense_categories.id')
            ->where('purchase_expenses.purchase_order_id', $id)
            ->select('expense_categories.name', 'purchase_expenses.amount', 'purchase_expenses.note', 'purchase_expenses.expense_category_id')
            ->get();
    }
}
