<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stores;
use App\Models\ExpenseCategories;
use App\Models\User;
use App\Models\Customers;
use App\Models\Bank;
use App\Models\Expense;
use App\Models\ExpenseItems;
use App\Models\ExpensePayment;

class BiayaPengeluaranController extends Controller
{
    public function index()
    {
        $stores = Stores::all();
        $expenseCategories = ExpenseCategories::all();
        $users = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'superadmin');
        })->get();
        $customers = Customers::all();
        $bank = Bank::all();
        $noreff = Expense::get_noreff()->noreff;      
        return view('biaya-add.index', compact('stores', 'expenseCategories', 'users', 'customers', 'bank', 'noreff'));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $totalAmount = $request->input('total_amount');
        $expenseData = [
            'store_id' => $request->input('store'),
            'payment_method' => $request->input('payment.method'),
            'total_amount' => $totalAmount,
            'description' => $request->input('note'),
            'remaining_amount' => $request->input('payment.method') === 'tempo' ? $totalAmount : 0,
            'status' => $request->input('payment.method') === 'tempo' ? 'belum lunas' : 'lunas',
        ];

        $expense = Expense::create($expenseData);

        foreach ($request->input('expenses') as $expenseItem) {
            $item = new ExpenseItems([
                'expense_id' => $expense->id,
                'expense_category_id' => $expenseItem['category'],
                'user_id' => $expenseItem['user'],
                'customer_id' => null,
                'amount' => $expenseItem['amount'],
            ]);
            $expense->expenseItems()->save($item);
        }

        $paymentMethod = $request->input('payment.method');
        
        if ($paymentMethod !== 'tempo') {
            $paymentData = [
                'expense_id' => $expense->id,
                'payment_date' => $request->input('payment.date'),
                'payment_method' => $paymentMethod,
                'amount_paid' => $totalAmount,
                'payment_note' => $request->input('payment.note'),
            ];

            if ($paymentMethod === 'bank_transfer') {
                $paymentData['bank_id'] = $request->input('payment.account');
            } else {
                $paymentData['bank_id'] = null;
            }

            ExpensePayment::create($paymentData);
        }

        return response()->json(['success' => true, 'expense' => $expense]);
    }
}
