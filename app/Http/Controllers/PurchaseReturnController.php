<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Suppliers;
use App\Models\Stores;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnDetail;
use App\Models\ProductPricing;

class PurchaseReturnController extends Controller
{
    public function index()
    {
        $purchaseReturns = PurchaseReturn::get_return_purchase_transactions();
        $suppliers = Suppliers::all();
        $stores = Stores::all();
        return view('purchase-return.index', compact('purchaseReturns', 'suppliers', 'stores'));
    }
    public function create()
    {
        $return_number = PurchaseReturn::get_return_purchase_transactions_id()->return_number;
        $stores = Stores::all();
        $suppliers = Suppliers::all();
        $purchase_transactions_id = Purchase::get_purchase_transactions_id();
        return view('purchase-return.create', compact('purchase_transactions_id', 'suppliers', 'stores', 'return_number'));
    }
    public function getInvoiceDetails($transactionId)
    {
        $transaction = Purchase::get_transaction_by_id($transactionId);
        $store       = Stores::find($transaction->store_id);
        $supplier    = Suppliers::find($transaction->supplier_id);
        $items       = PurchaseDetail::get_return_items_by_transaction_id($transactionId);
        return response()->json([
            'transaction' => $transaction,
            'store'       => $store,
            'supplier'    => $supplier,
            'items'       => $items
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required',
            'return_no' => 'required',
            'store_id' => 'required',
            'supplier_id' => 'required',
            'return_date' => 'required|date',
            'products' => 'required|array',
            'subtotal_return' => 'required|numeric',
            'total_return' => 'required|numeric',
            'discount_return' => 'required|numeric',
            'tax_return' => 'required|numeric',
            'note' => 'nullable|string',
        ]);

        $purchaseReturn = PurchaseReturn::create([
            'user_id' => auth()->user()->id,
            'return_no' => $request->return_no,
            'return_date' => $request->return_date,
            'purchase_order_id' => $request->invoice_no,
            'store_id' => $request->store_id,
            'supplier_id' => $request->supplier_id,
            'total_amount' => $request->subtotal_return,
            'discount' => $request->discount_return,
            'tax' => $request->tax_return,
            'note' => $request->note ?? null,
        ]);

        foreach ($request->products as $product) {
            PurchaseReturnDetail::create([
                'return_purchase_id' => $purchaseReturn->id,
                'purchase_order_detail_id' => $product['purchase_order_detail_id'],
                'product_pricing_id' => $product['product_pricing_id'],
                'quantity_purchased' => $product['qty'],
                'quantity_returned' => $product['return_qty'],
                'price' => $product['purchase_price'],
                'discount_item' => $product['discount'] ?? 0,
                'subtotal' => $product['return_total']
            ]);

            $productPricing = ProductPricing::find($product['product_pricing_id']);
            $productPricing->stock -= $product['return_qty'];
            $productPricing->save();
        }

        $purchase = Purchase::find($request->invoice_no);
        $purchase->isreturn = 'yes';
        $purchase->save();

        return response()->json(['success' => true, 'message' => 'Return pembelian berhasil disimpan']);
    }

    public function detail($id)
    {
        $return = PurchaseReturn::find($id);
        $purchaseTransaction = Purchase::get_transaction_by_id($return->purchase_order_id);
        $store = Stores::find($return->store_id);
        $supplier = Suppliers::find($return->supplier_id);  
        $returnItems = PurchaseReturnDetail::get_items_by_transaction_id($id);
        return view('purchase-return.detail', compact('return', 'returnItems', 'store', 'supplier', 'purchaseTransaction'));
    }
    public function cetakInvoice($id)
    {
        $return = PurchaseReturn::find($id);
        $purchaseTransaction = Purchase::get_transaction_by_id($return->purchase_order_id);
        $store = Stores::find($return->store_id);
        $supplier = Suppliers::find($return->supplier_id);  
        $returnItems = PurchaseReturnDetail::get_items_by_transaction_id($id);
        return view('purchase-return.cetak-invoice', compact('return', 'returnItems', 'store', 'supplier', 'purchaseTransaction'));
    }

    public function edit($id)
    {
        $return = PurchaseReturn::find($id);
        $purchaseTransaction = Purchase::get_transaction_by_id($return->purchase_order_id);
        $store = Stores::find($return->store_id);
        $supplier = Suppliers::find($return->supplier_id);  
        $returnItems = PurchaseReturnDetail::get_items_by_transaction_id($id);
        return view('purchase-return.edit', compact('return', 'returnItems', 'store', 'supplier', 'purchaseTransaction'));
    }

    public function update(Request $request, $id)
    {
        $return = PurchaseReturn::find($id);
        $return->return_date = $request->return_date;
        $return->note = $request->note;
        $return->total_amount = $request->subtotal_return;
        $return->discount = $request->discount_return;
        $return->save();

        foreach ($request->return_purchase_detail_id as $index => $itemId) {
            $returnItem = PurchaseReturnDetail::find($itemId);
            if ($returnItem) {
                $selisih_qty = $request->quantity_returned[$index] - $returnItem->quantity_returned;

                $productPricing = ProductPricing::find($returnItem->product_pricing_id);
                $productPricing->stock = $productPricing->stock - $selisih_qty;
                $productPricing->save();

                $returnItem->quantity_returned = $request->quantity_returned[$index];
                $returnItem->save();
            }
        }

        return redirect()->route('purchase-return.index')->with('success', 'Return penjualan berhasil diupdate');
    }

    public function destroy($id)
    {
        $return = PurchaseReturn::find($id);
        $returnItems = PurchaseReturnDetail::where('return_purchase_id', $id)->get();
        foreach ($returnItems as $item) {
            $productPricing = ProductPricing::find($item->product_pricing_id);
            $productPricing->stock = $productPricing->stock + $item->quantity_returned;
            $productPricing->save();
        }
        $return->delete();
        Purchase::where('id', $return->purchase_order_id)->update(['isreturn' => 'no']);
        return response()->json(['success' => true, 'message' => 'Return pembelian berhasil dihapus']);
    }

}
