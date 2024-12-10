<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stores;
use App\Models\ProductPricing;
use App\Models\Bank;
use App\Models\ExpenseCategories;
use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Models\StockTransferExpense;
use App\Models\StockTransferPayment;
use Illuminate\Support\Facades\DB;

class StockTransferController extends Controller
{
    public function index()
    {   
        $userId = auth()->id();
        $stores = Stores::whereHas('users', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
        $store_to = Stores::all();
        $banks = Bank::all();  
        $expenseCategories  = ExpenseCategories::all();
        $noreff = StockTransfer::get_noreff()->noreff;      
        return view('stocktransfer-add.index', compact('stores', 'banks', 'expenseCategories', 'noreff', 'store_to'));

    }

    public function search(Request $request)
    {
        $products = Product::searchProduct_stocktransfer($request->name, $request->store_id);
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'transfer_date' => 'required|date',
            'reference_no' => 'required|string',
            'status' => 'required|in:dikirim,diterima',
            'from_store' => 'required|exists:stores,id',
            'to_store' => 'required|exists:stores,id|different:from_store',
            'products' => 'required|array',
            'products.*.qty' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'other_costs' => 'nullable|array',
            'other_costs.*.amount' => 'required_with:other_costs|numeric|min:0',
            'payment.date' => 'required|date',
            'payment.method' => 'required|in:cash,bank_transfer,tempo',
            'payment.amount' => 'required|numeric|min:0',
            'grand_total_all' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'total_other_costs' => 'required|numeric|min:0',
            'receiver' => 'required_if:status,diterima|exists:users,id|nullable',
        ]);
        $request->total_amount = 0;
        $request->discount = 0;
        $stockTransfer = StockTransfer::create(
            [
                'user_id' => auth()->id(),
                'from_store_id' => $request->from_store,
                'to_store_id' => $request->to_store,
                'shipping_status' => $request->status,
                'transfer_date' => $request->transfer_date,
                'payment_method' => $request->payment['method'],
                'payment_status' => $request->payment['method'] == 'tempo' ? 'belum_lunas' : 'lunas',
                'total_amount' => $request->total_amount,
                'discount' => $request->discount ?? null,
                'remaining_payment' => $request->payment['method'] == 'tempo' ? $request->total_other_costs : 0,
                'received_by' => $request->status === 'diterima' ? $request->receiver : null,
                'received_at' => $request->status === 'diterima' ? now() : null,
            ]
        );

        foreach ($request->products as $product) {
            $stockTransferItem = StockTransferItem::create([
                'stock_transfer_id' => $stockTransfer->id,
                'product_pricing_id' => $product['id'],
                'quantity' => $product['qty'],
                'price' => $product['price'],
            ]);
            if ($request->status === 'diterima') {
                $productPricing = ProductPricing::where('store_id', $request->to_store)
                    ->where('barcode', $product['barcode'])
                    ->first();
                if (!$productPricing) {
                    $originalPricing = ProductPricing::find($product['id']);
                    $newPricing = $originalPricing->replicate();
                    $newPricing->store_id = $request->to_store;
                    $newPricing->stock = $product['qty'];
                    $newPricing->save();
                }else{
                    $productPricing->stock += $product['qty'];
                    $productPricing->save();
                }

                $productPricingFromStore = ProductPricing::where('store_id', $request->from_store)
                    ->where('id', $product['id'])
                    ->first();
                $productPricingFromStore->stock -= $product['qty'];
                $productPricingFromStore->save();
            }elseif($request->status === 'dikirim'){
                $productPricingFromStore = ProductPricing::where('store_id', $request->from_store)
                    ->where('id', $product['id'])
                    ->first();
                $productPricingFromStore->stock -= $product['qty'];
                $productPricingFromStore->save();
            }
        }

        if ($request->has('other_costs')) {
            foreach ($request->other_costs as $other_cost) {
                StockTransferExpense::create([
                    'stock_transfer_id' => $stockTransfer->id,
                    'expense_category_id' => $other_cost['category'],
                    'amount' => $other_cost['amount'],
                    'description' => $other_cost['note'] ?? null,
                ]);
            }
        }

        if ($request->payment['method'] !== 'tempo') {
            $paymentData = [
                'stock_transfer_id' => $stockTransfer->id,
                'payment_date' => $request->payment['date'],
                'payment_method' => $request->payment['method'],
                'amount_paid' => $request->payment['amount'],
            ];

            if ($request->payment['method'] === 'bank_transfer') {
                $paymentData['bank_id'] = $request->payment['account'];
            }

            StockTransferPayment::create($paymentData);
        }
        
        return response()->json(['success' => true]);
    }

    public function getUsersByStore(Request $request)
    {
        $users = DB::table('store_user')
            ->join('users', 'store_user.user_id', '=', 'users.id')
            ->where('store_user.store_id', $request->to_store)
            ->select('users.id', 'users.name')
            ->get();
        return response()->json(['users' => $users]);
    }
}
