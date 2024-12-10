<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Suppliers;
use App\Models\Purchase;
use App\Models\PurchaseExpense;
use App\Models\PurchasePayment;
use App\Models\Bank;
use App\Models\Stores;
use App\Models\PurchaseDetail;
use App\Models\expenseCategories;
use Illuminate\Support\Facades\DB;
use App\Models\ProductPricing;
use App\Models\PurchaseReturnDetail;
use Illuminate\Support\Facades\Log;
class PurchaseListController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->roles->pluck('name')->first();
        $userStores = $user->getstores->pluck('id')->toArray();
        $stores      = $user->getstores;
        $suppliers = Suppliers::all();
        $banks = Bank::all();
        $purchaseTransactions = Purchase::get_transaction();
        return view('purchase-list.index', compact('suppliers', 'stores', 'purchaseTransactions', 'banks'));
    }
    public function confirmPaymentTempo(Request $request)
    {
        
        $paymentData = [
            'purchase_order_id' => $request->purchase_id,
            'payment_method' => $request->payment_method,
            'amount_paid' => $request->payment_amount,
            'bank_id' => $request->bank_account,
            'payment_note' => $request->payment_note,
            'payment_date' => date('Y-m-d')
        ];        
        PurchasePayment::create($paymentData);
        Purchase::update_remaining_payment($request->purchase_id, $request->payment_amount);
        return response()->json(['success' => true, 'message' => 'Pembayaran berhasil dikonfirmasi']);



    }
    public function detail($id)
    {
        $transaction = Purchase::get_transaction_by_id($id);
        $store = Stores::find($transaction->store_id);
        $supplier = Suppliers::find($transaction->supplier_id);
        $products = PurchaseDetail::get_items_by_transaction_id($id);
        $detail_payment = PurchasePayment::get_payment_method($id); 
        $expenses = PurchaseExpense::get_expenses_by_transaction_id($id);
        return view('purchase-list.detail', compact('transaction', 'store', 'supplier', 'products', 'detail_payment', 'expenses'));
    }

    public function edit($id)
    {
     
        $user = auth()->user();
        $role = $user->roles->pluck('name')->first();
        $userStores = $user->getstores->pluck('id')->toArray();
        $stores      = $user->getstores;
        $suppliers = Suppliers::all();
        $expenseCategories = expenseCategories::all();
        $banks = Bank::all();
        $purchase = Purchase::get_transaction_by_id($id);
        $purchaseDetail = PurchaseDetail::get_items_by_transaction_id($id);
        $purchaseExpense = PurchaseExpense::get_expenses_by_transaction_id($id);
        $purchasePayment = PurchasePayment::get_payment_method($id);
        // dd($purchasePayment);
        return view('purchase-list.edit', compact('suppliers', 'stores', 'expenseCategories', 'banks', 'purchase', 'purchaseDetail', 'purchaseExpense', 'purchasePayment'));
    }
    public function update(Request $request, $id)
    {
        // dd($request->all());
            // Update purchase_order
            $purchase = Purchase::findOrFail($id);
            $cek_status = $purchase->payment_status;
            $cek_status_db = $purchase->status;
            $purchase->update([
                'supplier_id' => $request->supplier,
                'store_id' => $request->store,
                'purchase_date' => $request->purchase_date,
                'status' => $request->status,
                'discount' => $request->total_discount,
                'total_amount' => $request->subtotal,
            ]);

            // Update purchase_order_detail
            $existingDetails = PurchaseDetail::where('purchase_order_id', $id)->get()->keyBy('product_pricing_id');
            foreach ($request->products as $productId => $product) {
                if (isset($existingDetails[$productId])) {
                    $existingDetails[$productId]->update([
                        'quantity' => $product['qty'],
                        'purchase_price' => $product['purchase_price'],
                        'diskon' => $product['discount'],
                        'remaining_quantity' => $product['qty']
                    ]);
                    $existingDetails->forget($productId);
                } else {
                    PurchaseDetail::create([
                        'purchase_order_id' => $id,
                        'product_pricing_id' => $productId,
                        'quantity' => $product['qty'],
                        'remaining_quantity' => $product['qty'],
                        'purchase_price' => $product['purchase_price'],
                        'diskon' => $product['discount'],
                    ]);
                }

                if ($request->status === 'diterima') {
                    if ($cek_status_db !== 'diterima') {
                        $productPricing = ProductPricing::find($productId);
                        if ($productPricing) {
                            $productPricing->stock += $product['qty'];
                            $productPricing->save();
                        }
                    }
                }

            }
            if ($existingDetails->isNotEmpty()) {
                PurchaseDetail::destroy($existingDetails->pluck('id'));
            }
            // Update purchase_expenses
            $existingExpenses = PurchaseExpense::where('purchase_order_id', $id)->get()->keyBy('expense_category_id');
            if ($request->has('expense_category_id')) {
                foreach ($request->expense_category_id as $key => $categoryId) {
                    if (isset($existingExpenses[$categoryId])) {
                        $existingExpenses[$categoryId]->update([
                            'amount' => $request->expense_amount[$key],
                            'note' => $request->expense_note[$key],
                        ]);
                        $existingExpenses->forget($categoryId);
                    } else {
                        PurchaseExpense::create([
                            'purchase_order_id' => $id,
                            'expense_category_id' => $categoryId,
                            'amount' => $request->expense_amount[$key],
                            'note' => $request->expense_note[$key],
                        ]);
                    }
                }
            }

            if ($existingExpenses->isNotEmpty()) {
                PurchaseExpense::destroy($existingExpenses->pluck('id'));
            }
            
            // Update purchase_payment

            $existingPayments = PurchasePayment::where('purchase_order_id', $id)->get();
            if($existingPayments->isNotEmpty()){
                foreach ($request->payment['method'] as $key => $method) {
                    $paymentData = [
                        'payment_method' => $method,
                        'bank_id' => $method === 'bank_transfer' ? ($request->payment['account'][$key] ?? null) : null,
                        'amount_paid' => $request->payment['amount'][$key],
                        'payment_note' => $request->payment['note'][$key] ?? null,
                        'payment_date' => $request->payment['date'][$key]
                    ];
                    
                    if (isset($existingPayments[$key])) {
                        $existingPayments[$key]->update($paymentData);
                    }
                }
            }

            $total_expense = isset($request->expense_amount) ? array_sum($request->expense_amount) : 0;
            $total_purchase = $request->total_amount - $request->total_discount + $total_expense;
            $total_payment = isset($request->payment['amount']) ? array_sum($request->payment['amount']) : 0;

            if($cek_status == 'belum lunas'){
                if (!$existingPayments->count()) {
                    $purchase->remaining_payment = $total_purchase;
                } else {
                    $purchase->remaining_payment = $request->change_payment;
                }
                if($purchase->remaining_payment <= 0){
                    $purchase->remaining_payment = 0;
                    $purchase->payment_status = 'lunas';
                }
                $purchase->save();
            }

        return redirect()->back()->with('success', 'Pembelian berhasil diperbarui');
    }

    public function printNota($id)
    {
        $transaction = Purchase::get_transaction_by_id($id);
        $store = Stores::find($transaction->store_id);
        $supplier = Suppliers::find($transaction->supplier_id);
        $products = PurchaseDetail::get_items_by_transaction_id($id);
        $detail_payment = PurchasePayment::get_payment_method($id); 
        $expenses = PurchaseExpense::get_expenses_by_transaction_id($id);
        // dd($expenses);
        return view('purchase-list.nota', compact('transaction', 'store', 'supplier', 'products', 'detail_payment', 'expenses'));
    }
    public function printInvoice($id)
    {
        $transaction = Purchase::get_transaction_by_id($id);
        $store = Stores::find($transaction->store_id);
        $supplier = Suppliers::find($transaction->supplier_id);
        $products = PurchaseDetail::get_items_by_transaction_id($id);
        $detail_payment = PurchasePayment::get_payment_method($id); 
        $expenses = PurchaseExpense::get_expenses_by_transaction_id($id);
        return view('purchase-list.invoice', compact('transaction', 'store', 'supplier', 'products', 'detail_payment', 'expenses'));
    }
    public function destroy($id)
    {

        $purchaseDetails = PurchaseDetail::where('purchase_order_id', $id)->get();
        foreach ($purchaseDetails as $detail) {
            $quantity = $detail->quantity;
            $returnedQuantity = PurchaseReturnDetail::where('purchase_order_detail_id', $detail->id)
                                                    ->value('quantity_returned');
            $remainingQuantity = $quantity - $returnedQuantity;
            $productPricing = ProductPricing::find($detail->product_pricing_id);
            
            if ($productPricing) {
                $productPricing->stock -= $remainingQuantity;
                $productPricing->save();
            }
        }
        Purchase::destroy($id);
        
        return response()->json(['success' => true, 'message' => 'Pembelian berhasil dihapus']);
    }

}
