<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockTransfer;
use App\Models\Stores;
use App\Models\expenseCategories;
use App\Models\Bank;
use App\Models\StockTransferItem;
use App\Models\StockTransferExpense;
use App\Models\StockTransferPayment;
use App\Models\ProductPricing;

class StockTransferListController extends Controller
{
    public function index(){
        $user = auth()->user();
        $storeIds = $user->getstores->pluck('id')->toArray();
        $stockTransfers = StockTransfer::get_transaction($storeIds);
        $stores = Stores::all();
        $banks = Bank::all();   
        return view('stocktransfer-list.index', compact('stockTransfers', 'stores','banks'));
    }

    public function detail($id){
        $transaction = StockTransfer::get_transaction_by_id($id);
        $products = StockTransferItem::get_items_by_transaction_id($id);
        $detail_payment = StockTransferPayment::get_payment_method($id); 
        $expenses = StockTransferExpense::get_expenses_by_transaction_id($id);
        $stores = Stores::all();
        return view('stocktransfer-list.detail', compact('transaction', 'products', 'detail_payment', 'expenses', 'stores'));
    }

    public function edit($id){
        $stores             = Stores::all();
        $expenseCategories  = expenseCategories::all();
        $banks              = Bank::all();
        $stockTransfer      = StockTransfer::get_transaction_by_id($id);
        $stockTransferDetail= StockTransferItem::get_items_by_transaction_id($id);
        $expenses           = StockTransferExpense::get_expenses_by_transaction_id($id);
        $payments           = StockTransferPayment::get_payment_method($id);
        return view('stocktransfer-list.edit', compact('stockTransfer', 'stockTransferDetail', 'expenses', 'payments', 'stores','expenseCategories','banks'));
    }

    public function update(Request $request, $id)
    {
        $stockTransfer = StockTransfer::find($id);
        if($request->status == 'diterima'){
            $stockTransfer->update([
                'shipping_status' => $request->status,
                'received_by' => $request->receiver
            ]);       
            if ($request->has('products')) {
                foreach ($request->products as $productId => $productData) {
                    $stockTransferItem = StockTransferItem::where('stock_transfer_id', $id)
                        ->where('product_pricing_id', $productId)
                        ->first();
                    
                    if ($stockTransferItem && $stockTransferItem->quantity != $productData['qty']) {
                        $stockTransferItem->update([
                            'quantity_received' => $productData['qty']
                        ]);
                    }else{
                        $stockTransferItem->update([
                            'quantity_received' => $stockTransferItem->quantity
                        ]);
                    }
                    $productPricing = ProductPricing::where('store_id', $stockTransfer->to_store_id)
                        ->where('barcode', $productData['barcode'])
                        ->first();
                    if ($productPricing) {
                        $productPricing->stock += $productData['qty'];
                        $productPricing->save();
                    }else{
                        $originalPricing = ProductPricing::find($productData['id']);
                        $newPricing = $originalPricing->replicate();
                        $newPricing->store_id = $stockTransfer->to_store_id;
                        $newPricing->stock = $productData['qty'];
                        $newPricing->save();
                    }
                }
            }
            
            if ($request->has('expense_category_id')) {
                foreach ($request->expense_category_id as $key => $categoryId) {
                    $expense = StockTransferExpense::where('stock_transfer_id', $id)
                        ->where('expense_category_id', $categoryId)
                        ->first();
                    
                    if ($expense) {
                        $expense->update([
                            'amount' => $request->expense_amount[$key],
                            'description' => $request->expense_note[$key] ?? null
                        ]);
                    }
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Status berhasil diupdate']);
    }

    public function destroy($id)
    {
        $stockTransfer = StockTransfer::find($id);
        $items = StockTransferItem::get_item_to_delete($id);
        if ($stockTransfer->shipping_status == 'diterima'){
            // foreach ($items as $item) {
            //     ProductPricing::where('store_id', $stockTransfer->from_store_id)
            //         ->where('barcode', $item->barcode)
            //         ->increment('stock', $item->quantity);

            //     ProductPricing::where('store_id', $stockTransfer->to_store_id)
            //         ->where('barcode', $item->barcode)
            //         ->decrement('stock', $item->quantity);
            // }
        }elseif($stockTransfer->shipping_status == 'dikirim'){
            foreach ($items as $item) {
                ProductPricing::where('store_id', $stockTransfer->from_store_id)
                    ->where('barcode', $item->barcode)
                    ->increment('stock', $item->quantity);
            }
        }
        StockTransfer::where('id', $id)->delete();
    
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);

    }

    public function lunasi(Request $request, $id)
    {

        $request->validate([
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:tunai,bank_transfer',
            'bank_account' => 'required_if:payment_method,bank_transfer',
            'payment_note' => 'nullable|string',
        ]);

        StockTransferPayment::create([
            'stock_transfer_id' => $id,
            'amount_paid' => $request->payment_amount,
            'payment_method' => $request->payment_method === 'tunai' ? 'cash' : 'bank_transfer',
            'bank_id' => $request->payment_method === 'bank_transfer' ? $request->bank_account : null,
            'payment_note' => $request->payment_note,
            'payment_date' => date('Y-m-d'),
        ]);
        $stockTransfer = StockTransfer::find($id);
        $stockTransfer->remaining_payment -= $request->payment_amount;
        if ($stockTransfer->remaining_payment <= 0) {
            $stockTransfer->payment_status = 'lunas';
            $stockTransfer->remaining_payment = 0;
        }
        $stockTransfer->save();
        return redirect()->back()->with('success', 'Pembayaran berhasil');
    }

}
