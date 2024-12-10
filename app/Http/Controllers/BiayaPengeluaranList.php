<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\ExpenseItems;
use App\Models\ExpensePayment;
use App\Models\ExpenseCategories;
use App\Models\Stores;
use App\Models\Bank;
use App\Models\User;
use App\Models\Customers;
use App\Models\StockTransferExpense;
class BiayaPengeluaranList extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->roles->pluck('name')->first();
        $store_id = $user->getstores->first()->id ?? null;
        $purchase_expense = Expense::get_purchase_expense($store_id);
        $stocktransfer_expense = Expense::get_stocktransfer_expense($store_id);
        $other_expense = Expense::get_other_expense($store_id);
        $stores = Stores::all();
        $banks = Bank::all();
        $type = request()->query('type');
        if($type == 'purchase'){
            $title = 'Pembelian';
            $expenses = Expense::get_purchase_expense_detail($store_id);
        }elseif($type == 'stocktransfer'){
            $title = 'Transfer Barang';
            $expenses = Expense::get_stocktransfer_expense_detail($store_id);
        }elseif($type == 'other'){
            $title = 'Lainnya';
            $expenses = Expense::get_other_expense_detail($store_id);
        }
        if(!empty($type)){
            return view('biaya-list.indextype', compact('type','expenses','stores','title','banks'));
        }else{
            return view('biaya-list.index', compact('purchase_expense','stocktransfer_expense','other_expense','stores','banks'));
        }

    }
    public function detail($id)
    {
        $type = request()->query('type');
        if($type == 'purchase'){
            $transaction = Expense::get_transaction_type_by_id($id);
            $expenses = Expense::get_expense_type_by_id($id);
            $payments = Expense::get_expense_payment_type_by_id($id);
        }elseif($type == 'stocktransfer'){
            $transaction = StockTransferExpense::get_transaction_by_id($id);
            $expenses = StockTransferExpense::get_expense_detail($id);
            $payments = StockTransferExpense::get_expense_payment($id);
        }elseif($type == 'other'){
            $transaction = ExpenseItems::get_transaction_by_id($id);
            $expenses = ExpenseItems::get_expense_detail($id);
            $payments = ExpenseItems::get_expense_payment($id);
        }
        
        return view('biaya-list.detailtype', compact('transaction', 'expenses', 'payments'));

    }
    public function confirmPayment(Request $request, $id)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer',
            'bank_id' => 'required_if:payment_method,bank_transfer',
            'payment_note' => 'nullable|string',
        ]);

        ExpensePayment::create([
            'expense_id' => $id,
            'amount_paid' => $request->payment_amount,
            'payment_method' => $request->payment_method,
            'bank_id' => $request->payment_method === 'bank_transfer' ? $request->bank_id : null,
            'payment_note' => $request->payment_note,
            'payment_date' => now(),
        ]);

        $expense = Expense::find($id);
        $expense->remaining_amount -= $request->payment_amount;
        if ($expense->remaining_amount <= 0) {
            $expense->status = 'lunas';
            $expense->remaining_amount = 0;
        }
        $expense->save();
        return response()->json(['success' => true, 'message' => 'Pembayaran berhasil dikonfirmasi']);
    }
    
    public function edit($id){
        $stores = Stores::all();
        $expenseCategories = ExpenseCategories::all();
        $users = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'superadmin');
        })->get();
        $customers = Customers::all();
        $bank = Bank::all();

        $expense      = Expense::get_transaction_by_id($id);
        $expenseDetail= ExpenseItems::get_items_by_transaction_id($id);
        $payments     = ExpensePayment::get_payment_method($id);
        return view('biaya-list.edit', compact('stores', 'expenseCategories', 'users', 'customers', 'bank','expense','expenseDetail','payments'));
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::find($id);
        $expense->update([
            'store_id' => $request->store,
            'expense_date' => $request->expense_date,
            'description' => $request->note,
            'total_amount' => $request->total_amount
        ]);
        $existingItems = $expense->expenseItems()->pluck('id')->toArray();
        $updatedItems = [];

        foreach ($request->expenses as $item) {
            if (isset($item['id'])) {
                ExpenseItems::where('id', $item['id'])->update([
                    'expense_category_id' => $item['category'],
                    'user_id' => $item['user'],
                    'customer_id' => $item['customer'],
                    'amount' => $item['amount'],
                    'note' => $item['note'] ?? ''
                ]);
                $updatedItems[] = $item['id'];
            } else {
                $newItem = ExpenseItems::create([
                    'expense_id' => $expense->id,
                    'expense_category_id' => $item['category'],
                    'user_id' => $item['user'],
                    'customer_id' => $item['customer'],
                    'amount' => $item['amount'],
                    'note' => $item['note'] ?? ''
                ]);
                $updatedItems[] = $newItem->id;
            }
        }

        $itemsToDelete = array_diff($existingItems, $updatedItems);
        ExpenseItems::whereIn('id', $itemsToDelete)->delete();

        ExpensePayment::where('expense_id', $expense->id)->delete();
        foreach ($request->payments as $payment) {
            ExpensePayment::create([
                'expense_id' => $expense->id,
                'payment_date' => $payment['date'],
                'payment_method' => $payment['method'],
                'amount_paid' => $payment['amount'],
                'payment_note' => $payment['note'],
                'bank_id' => $payment['method'] === 'bank_transfer' ? $payment['account'] : null,
            ]);
        }

        $totalExpense = collect($request->expenses)->sum('amount');
        $totalPaid = collect($request->payments)->sum('amount');
        $expense->total_amount = $totalExpense;
        $expense->remaining_amount = $totalExpense - $totalPaid;
        $expense->status = $expense->remaining_amount <= 0 ? 'lunas' : 'belum lunas';
        $expense->save();
        return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
    }

    public function destroy($id)
    {
        $expense = Expense::find($id);
        $expense->delete();
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
