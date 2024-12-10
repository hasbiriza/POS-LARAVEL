<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\Stores;
use App\Models\Customers;
use App\Models\SalesTransactions;
use App\Models\SalesTransactionItems;
use App\Models\ProductPricing;
use App\Models\PaymentMethod;

class SalesReturnController extends Controller
{
    public function index()
    {
        $salesReturns = SalesReturn::get_return_sales_transactions();
        $stores = Stores::all();
        $customers = Customers::all();
        return view('sales-return.index', compact('salesReturns', 'stores', 'customers'));
    }

    public function create()
    {
        $return_number = SalesReturn::get_return_sales_transactions_id()->return_number;
        $stores = Stores::all();
        $customers = Customers::all();
        $sales_transactions_id = SalesTransactions::get_sales_transactions_id();
        return view('sales-return.create', compact('stores', 'customers', 'sales_transactions_id', 'return_number'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required',
            'return_no' => 'required',
            'store_id' => 'required',
            'customer_id' => 'required',
            'return_date' => 'required|date',
            'products' => 'required|array',
            'subtotal_return' => 'required|numeric',
            'total_return' => 'required|numeric',
            'discount_return' => 'required|numeric',
            'tax_return' => 'required|numeric',
            'note' => 'nullable|string',
        ]);

        $salesReturn = SalesReturn::create([
            'return_no' => $request->return_no,
            'return_date' => $request->return_date,
            'sales_transaction_id' => $request->invoice_no,
            'store_id' => $request->store_id,
            'customer_id' => $request->customer_id,
            'total_return' => $request->subtotal_return,
            'discount_return' => $request->discount_return,
            'tax_return' => $request->tax_return,
            'note' => $request->note ?? null,
        ]);
        foreach ($request->products as $product) {
            SalesReturnItem::create([
                'return_sales_transaction_id' => $salesReturn->id,
                'sales_transaction_item_id' => $product['sales_transaction_items_id'],
                'product_pricing_id' => $product['product_pricing_id'],
                'quantity_sold' => $product['qty'],
                'quantity_returned' => $product['return_qty'],
                'price' => $product['sale_price'],
                'discount_item' => $product['discount'],
                'subtotal' => $product['return_qty'] * $product['sale_price']
            ]);

            $productPricing = ProductPricing::find($product['product_pricing_id']);
            $productPricing->stock += $product['return_qty'];
            $productPricing->save();
        }

        $transaction = SalesTransactions::find($request->invoice_no);
        $transaction->isreturn = 'yes';
        $transaction->save();
        return response()->json(['success' => true, 'message' => 'Return penjualan berhasil disimpan']);
    }

    public function getInvoiceDetails($transactionId)
    {

        $transaction = SalesTransactions::get_transaction_by_id($transactionId);
        $store       = Stores::find($transaction->store_id);
        $customer    = Customers::find($transaction->customer_id);
        $items       = SalesTransactionItems::get_items_by_transaction_id($transactionId);
        return response()->json([
            'transaction' => $transaction,
            'store'       => $store,
            'customer'    => $customer,
            'items'       => $items
        ]);
    }

    public function detail($id)
    {
        $return = SalesReturn::find($id);
        $salesTransaction = SalesTransactions::get_transaction_by_id($return->sales_transaction_id);
        $store = Stores::find($return->store_id);
        $customer = Customers::find($return->customer_id);  
        $returnItems = SalesReturnItem::get_items_by_transaction_id($id);
        return view('sales-return.detail', compact('return', 'returnItems', 'store', 'customer', 'salesTransaction'));
    }

    public function cetakInvoice($id)
    {
        $return = SalesReturn::find($id);
        $salesTransaction = SalesTransactions::get_transaction_by_id($return->sales_transaction_id);
        $store = Stores::find($return->store_id);
        $customer = Customers::find($return->customer_id);  
        $returnItems = SalesReturnItem::get_items_by_transaction_id($id);
        // dd($returnItems);
        return view('sales-return.cetak-invoice', compact('return', 'returnItems', 'store', 'customer', 'salesTransaction'));
    }

    public function edit($id)
    {
        $return = SalesReturn::find($id);
        $salesTransaction = SalesTransactions::get_transaction_by_id($return->sales_transaction_id);
        $store = Stores::find($return->store_id);
        $customer = Customers::find($return->customer_id);  
        $returnItems = SalesReturnItem::get_items_by_transaction_id($id);
        return view('sales-return.edit', compact('return', 'returnItems', 'store', 'customer', 'salesTransaction'));
    }

    public function update(Request $request, $id)
    {
        $return = SalesReturn::find($id);
        $return->return_date = $request->return_date;
        $return->note = $request->note;
        $return->total_return = $request->subtotal_return;
        $return->tax_return = $request->tax_return;
        $return->discount_return = $request->discount_return;
        $return->save();

        foreach ($request->return_sales_transaction_item_id as $index => $itemId) {
            $returnItem = SalesReturnItem::find($itemId);
            if ($returnItem) {
                $selisih_qty = $request->quantity_returned[$index] - $returnItem->quantity_returned;

                $productPricing = ProductPricing::find($returnItem->product_pricing_id);
                $productPricing->stock = $productPricing->stock + $selisih_qty;
                $productPricing->save();

                $returnItem->quantity_returned = $request->quantity_returned[$index];
                $returnItem->save();
            }
        }

        return redirect()->route('sales-return.index')->with('success', 'Return penjualan berhasil diupdate');
    }

    public function destroy($id)
    {
        $return = SalesReturn::find($id);
        $returnItems = SalesReturnItem::where('return_sales_transaction_id', $id)->get();
        foreach ($returnItems as $item) {
            $productPricing = ProductPricing::find($item->product_pricing_id);
            $productPricing->stock = $productPricing->stock - $item->quantity_returned;
            $productPricing->save();
        }
        $return->delete();
        SalesTransactions::where('id', $return->sales_transaction_id)->update(['isreturn' => 'no']);
        return response()->json(['success' => true, 'message' => 'Return penjualan berhasil dihapus']);
    }
}
