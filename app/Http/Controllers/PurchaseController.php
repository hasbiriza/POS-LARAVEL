<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Suppliers;
use App\Models\Stores;
use App\Models\Product;
use App\Models\Bank;
use App\Models\ExpenseCategories;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchaseExpense;
use App\Models\PurchasePayment;
use App\Models\ProductPricing;
class PurchaseController extends Controller
{
    public function add()
    {
        $user = auth()->user();
        $role = $user->roles->pluck('name')->first();
        $userStores = $user->getstores->pluck('id')->toArray();
        $stores      = $user->getstores;
        $suppliers = Suppliers::all();
        $expenseCategories = ExpenseCategories::all();
        $banks = Bank::all();
        $noreff = Purchase::get_noreff()->noreff;
        return view('purchase.add', compact('suppliers', 'stores', 'expenseCategories', 'banks', 'noreff'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'supplier' => 'required',
            'reference_no' => 'required',
            'purchase_date' => 'required|date',
            'store' => 'required',
            'status' => 'required',
            'total_amount' => 'required|numeric|min:0',
            'grandTotal' => 'required|numeric|min:0',
            'total_discount' => 'required|numeric|min:0',
            'products' => 'required|array',
            'products.*.qty' => 'required|numeric|min:1',
            'products.*.purchase_price' => 'required|numeric|min:0',
            'products.*.discount' => 'nullable|numeric|min:0',
            'payment.date' => 'required|date',
            'payment.method' => 'required',
            'payment.amount' => 'required|numeric|min:0',
            'change_payment' => 'required|numeric|min:0',
        ]);

        $purchase = Purchase::create([
            'supplier_id' => $request->supplier,
            'reference_no' => $request->reference_no,
            'purchase_date' => $request->purchase_date,
            'store_id' => $request->store,
            'payment_method' => $request->payment['method'],
            'total_amount' => $request->subtotal,
            'discount' => $request->total_discount,
            'status' => $request->status,
            'payment_status' => $request->payment['method'] === 'tempo' ? 'belum lunas' : 'lunas',
            'remaining_payment' => $request->payment['method'] === 'tempo' ? $request->grandTotal : 0,
        ]);

        // Simpan detail produk dan update stok
        foreach ($request->products as $productId => $productData) {
            PurchaseDetail::create([
                'purchase_order_id' => $purchase->id,
                'product_pricing_id' => $productId,
                'quantity' => $productData['qty'],
                'purchase_price' => $productData['purchase_price'],
                'diskon' => $productData['discount'],
                'remaining_quantity' => $productData['qty'],
                'purchase_date' => $request->purchase_date
            ]);

            // Update stok di tabel product_pricing jika status diterima
            if ($request->status === 'diterima') {
                $productPricing = ProductPricing::find($productId);
                if ($productPricing) {
                    $productPricing->stock += $productData['qty'];
                    $productPricing->save();
                }
            }
        }

        // Simpan biaya tambahan
        if ($request->has('other_costs')) {
            foreach ($request->other_costs as $cost) {
                PurchaseExpense::create([
                    'purchase_order_id' => $purchase->id,
                    'expense_category_id' => $cost['category'],
                    'note' => $cost['note'],
                    'amount' => $cost['amount']
                ]);
            }
        }

        // Simpan pembayaran
        if ($request->payment['method'] !== 'tempo') {
            $paymentData = [
                'purchase_order_id' => $purchase->id,
                'payment_date' => $request->payment['date'],
                'payment_method' => $request->payment['method'],
                'amount_paid' => $request->payment['amount'],
                'payment_note' => $request->payment['note']
            ];

            if ($request->payment['method'] === 'bank_transfer') {
                $paymentData['bank_id'] = $request->payment['account'];
            }

            PurchasePayment::create($paymentData);
        }

        return response()->json(['success' => true]);
    }

    public function search(Request $request)
    {
        $products = Product::searchProduct_purchase($request->name, $request->store_id);
        return response()->json($products);
    }
}
