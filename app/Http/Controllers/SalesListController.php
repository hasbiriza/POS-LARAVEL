<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stores;
use App\Models\User;
use App\Models\ProductPricing;
use App\Models\ProductImages;
use App\Models\Customers;
use App\Models\Pajak;
use App\Models\Bank;
use App\Models\SalesTransactions;
use App\Models\SalesTransactionItems;
use App\Models\PaymentMethod;

class SalesListController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->roles->pluck('name')->first();
        $userStores = $user->getstores->pluck('id')->toArray();
        $stores      = $user->getstores;
        $customers  = Customers::all();
        $kasirs = User::getKasirsByUserStores($userStores);
        $salesTransactions = SalesTransactions::get_transaction();
        $banks = Bank::all();
        return view('sales-list.index', compact('salesTransactions', 'banks', 'stores', 'customers', 'kasirs'));
    }

    public function confirmPaymentTempo(Request $request)
    {
        $paymentData = [
            'transaction_id' => $request->transaction_id,
            'payment_method' => $request->payment_method,
            'amount' => $request->payment_amount,
            'bank_id' => $request->bank_account
        ];        
        PaymentMethod::create($paymentData);
        SalesTransactions::update_remaining_payment($request->transaction_id, $request->payment_amount);
        return response()->json(['success' => true, 'message' => 'Pembayaran berhasil dikonfirmasi']);
    }

    public function detail($transactionId)
    {
        $transaction = SalesTransactions::get_transaction_by_id($transactionId);
        $store = Stores::find($transaction->store_id);
        $customer = Customers::find($transaction->customer_id);
        $kasir = User::find($transaction->user_id);
        $items = SalesTransactionItems::get_items_by_transaction_id($transactionId);
        $paymentMethods = PaymentMethod::get_payment_method($transactionId);
        return view('sales-list.detail', compact('transaction', 'store', 'customer', 'kasir', 'items', 'paymentMethods'));
    }

    public function printInvoice($transactionId)
    {
        $transaction = SalesTransactions::get_transaction_by_id($transactionId);
        $store = Stores::find($transaction->store_id);
        $customer = Customers::find($transaction->customer_id);
        $kasir = User::find($transaction->user_id);
        $items = SalesTransactionItems::get_items_by_transaction_id($transactionId);
        $paymentMethods = PaymentMethod::get_payment_method($transactionId);
        return view('sales-list.print-invoice', compact('transaction', 'store', 'customer', 'kasir', 'items', 'paymentMethods'));
    }

    public function edit($transactionId)
    {
        $transaction = SalesTransactions::get_transaction_by_id($transactionId);
        $store = Stores::find($transaction->store_id);
        $customer = Customers::find($transaction->customer_id);
        $kasir = User::find($transaction->user_id);
        $items = SalesTransactionItems::get_items_by_transaction_id($transactionId);
        $paymentMethods = PaymentMethod::get_payment_method($transactionId);
        $products = Product::get_products_by_store($store->id);
        return view('sales-list.edit', compact('transaction', 'store', 'customer', 'kasir', 'items', 'paymentMethods', 'products'));

    }

    public function update(Request $request, $transactionId)
    {
// dd($request->all());

        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'nullable|exists:sales_transaction_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.product_pricing_id' => 'required|exists:product_pricing,id',
            'items.*.price' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'payment_methods' => 'required|array',
            'payment_methods.*.id' => 'required|exists:payment_methods,id',
            'payment_methods.*.amount' => 'required|numeric|min:0'
        ]);

        $existingItems = SalesTransactionItems::where('transaction_id', $transactionId)->get();
        $updatedItemIds = [];

        foreach ($request->items as $item) {
            $transactionItem = SalesTransactionItems::where('transaction_id', $transactionId)
                ->where('product_pricing_id', $item['product_pricing_id'])
                ->first();
            if (!$transactionItem) {
                $transactionItem = new SalesTransactionItems();
                $transactionItem->transaction_id = $transactionId;
                $transactionItem->product_pricing_id = $item['product_pricing_id'];
                $transactionItem->quantity = $item['quantity'];
                $transactionItem->price = $item['price'];
                $transactionItem->subtotal = $item['quantity'] * $item['price'];
                $transactionItem->save();

                $productPricing = ProductPricing::find($item['product_pricing_id']);
                $productPricing->stock -= $item['quantity'];
                $productPricing->save();
            } else {
                $oldQuantity = $transactionItem->quantity;
                $newQuantity = $item['quantity'];
                $transactionItem->quantity = $newQuantity;
                $transactionItem->price = $item['price'];
                $transactionItem->subtotal = $newQuantity * $item['price'];
                $transactionItem->save();

                $productPricing = ProductPricing::find($item['product_pricing_id']);
                $stockDifference = $newQuantity - $oldQuantity;
                $productPricing->stock -= $stockDifference;
                $productPricing->save();
            }
            $updatedItemIds[] = $transactionItem->id;
        }

        foreach ($existingItems as $existingItem) {
            if (!in_array($existingItem->id, $updatedItemIds)) {
                $productPricing = ProductPricing::find($existingItem->product_pricing_id);
                $productPricing->stock += $existingItem->quantity;
                $productPricing->save();

                $existingItem->delete();
            }
        }

        $subtotal = SalesTransactionItems::where('transaction_id', $transactionId)->sum('subtotal');
        $discountAmount = $request->discount_amount;
        $taxAmount = $request->tax_amount;
        $totalAmount = $subtotal - $discountAmount + $taxAmount;
        $totalPayment = 0;
        foreach ($request->payment_methods as $paymentMethod) {
            $totalPayment += $paymentMethod['amount'];
        }

        $updateData = [
            'total_amount' => $subtotal,
            'discount_amount' => $discountAmount,
            'tax_amount' => $taxAmount,
            'total_payment' => $totalPayment
        ];

        if ($totalPayment > $totalAmount) {
            $updateData['change_payment'] = $totalPayment - $totalAmount;
            $updateData['remaining_payment'] = 0;
            $updateData['status'] = 'lunas';
        }elseif($totalPayment < $totalAmount){
            $updateData['change_payment'] = 0;
            $updateData['remaining_payment'] = $totalAmount - $totalPayment;
            $updateData['status'] = 'tempo';
        } else {
            $updateData['change_payment'] = 0;
            $updateData['remaining_payment'] = 0;
            $updateData['status'] = 'lunas';
        }

        SalesTransactions::where('id', $transactionId)->update($updateData);

        foreach ($request->payment_methods as $paymentMethod) {
            $payment = PaymentMethod::find($paymentMethod['id']);
            $payment->amount = $paymentMethod['amount'];
            $payment->save();
        }

        return redirect()->route('sales-list.edit', $transactionId)->with('success', 'Transaksi berhasil diperbarui');
    }

    public function destroy($transactionId)
    {
        $x = SalesTransactions::delete_transaction($transactionId);
// dd($x);
        return response()->json(['success' => true, 'message' => 'Transaksi berhasil dihapus']);
    }
}
