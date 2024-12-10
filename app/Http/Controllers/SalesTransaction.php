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
use Illuminate\Pagination\LengthAwarePaginator;
use Gloudemans\Shoppingcart\Facades\Cart;

class SalesTransaction extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->roles->pluck('name')->first();
        $store_id = $user->getstores->first()->id ?? null;
        if ($role == 'kasir') {
            $products = Product::user_products_default($user->id, $store_id);
        } else {
            dd("Tidak ada akses");
        }
        $perPage = 15;
        $currentPage = request()->get('page', 1);

        $products = new LengthAwarePaginator(
            $products->forPage($currentPage, $perPage),
            $products->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        $pajak      = Pajak::where('status', 1)->get();
        $stores      = $user->getstores;
        $customers  = Customers::all();
        $banks      = Bank::all();
        $id = '0';
        return view('sales-transaction.index', compact('products', 'customers', 'pajak', 'stores', 'banks', 'id'));
    }

    public function byStore($id)
    {
        $user = auth()->user();
        $role = $user->roles->pluck('name')->first();
        $userStores = $user->getstores->pluck('id')->toArray();

        if ($role == 'kasir') {
            if (!in_array($id, $userStores)) {
                die("Tidak ada akses ke toko ini");
            }
            $products = Product::user_products_default($user->id, $id);
        } else {
            die("Tidak ada akses");
        }
        $perPage = 15;
        $currentPage = request()->get('page', 1);

        $products = new LengthAwarePaginator(
            $products->forPage($currentPage, $perPage),
            $products->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        $pajak      = Pajak::where('status', 1)->get();
        $stores      = $user->getstores;
        $customers  = Customers::all();
        $banks      = Bank::all();
        return view('sales-transaction.index', compact('products', 'customers', 'pajak', 'stores', 'id', 'banks'));
    }

    public function getLatestTransaction($storeId)
    {
        $latestTransaction = SalesTransactions::where('store_id', $storeId)->latest('id')->first();
        return response()->json(['latest_id' => $latestTransaction ? $latestTransaction->id : 0]);
    }

    public function getCartQuantity($productId)
    {
        $cartItems = Cart::content();
        $quantity = $cartItems->firstWhere('id', $productId)->qty ?? 0;
        return response()->json(['quantity' => $quantity]);
    }

    public function searchProductsByStore(Request $request, $storeId)
    {
        $products = Product::searchProduct_by_store($storeId, $request->name);
        return response()->json(['products' => $products]);
    }

    public function addToCart($id)
    {
        $product = Product::getProductsWithVariant($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }
        Cart::add([
            'id' => $product->pricing_id,
            'name' => $product->product_name,
            'qty' => 1,
            'price' => $product->sale_price,
            'options' => [
                'variant1' => $product->variant1,
                'variant2' => $product->variant2,
                'variant3' => $product->variant3,
                'discount' => 0,
                'stock' => $product->stock
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'sale_price' => $product->sale_price
        ]);
    }

    public function getCartQuantityByBarcode($barcode)
    {
        $pricing = ProductPricing::where('barcode', $barcode)->first();
        $productId = $pricing->id;
        $cartItems = Cart::content();
        $quantity = $cartItems->firstWhere('id', $productId)->qty ?? 0;
        return response()->json(['quantity' => $quantity, 'stock' => $pricing->stock]);
    }

    public function addToCartByBarcode($barcode)
    {
        $pricing = ProductPricing::where('barcode', $barcode)->first();
        if (!$pricing) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        $product = $pricing->product;
        Cart::add([
            'id' => $pricing->id,
            'name' => $product->name,
            'qty' => 1,
            'price' => $pricing->sale_price,
            'options' => [
                'variant1' => $pricing->variasi_1,
                'variant2' => $pricing->variasi_2,
                'variant3' => $pricing->variasi_3,
                'discount' => 0,
                'stock' => $pricing->stock
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'sale_price' => $pricing->sale_price
        ]);
    }
    public function cartContent()
    {
        $cartItems = Cart::content();
        $total = Cart::subtotal();
        $count = Cart::count();
        $total = str_replace(',', '', $total);
        $total = (float)$total;
        $formattedTotal = number_format((float)$total, 0, ',', '.');
        $cartItemsHtml = view('sales-transaction.cart-items', compact('cartItems'))->render();

        return response()->json([
            'cartItemsHtml' => $cartItemsHtml,
            'total' => $formattedTotal,
            'count' => $count
        ]);
    }

    public function updateCart($rowId, Request $request)
    {
        $qty = $request->qty;
        Cart::update($rowId, $qty);
        return response()->json(['success' => true, 'message' => 'Keranjang berhasil diperbarui']);
    }

    public function removeFromCart($rowId)
    {
        Cart::remove($rowId);
        return response()->json(['success' => true, 'message' => 'Produk berhasil dihapus dari keranjang']);
    }

    public function destroyCart()
    {
        Cart::destroy();
        return redirect()->route('sales-transaction.index');
    }

    public function deleteAllCart()
    {
        Cart::destroy();
        return response()->json(['success' => true, 'message' => 'Keranjang berhasil dihapus']);
    }

    public function confirmPayment(Request $request)
    {

        $totalAmount = Cart::content()->sum(function ($item) {
            return $item->price * $item->qty;
        });

        $transactionData = [
            'transaction_date' => now(),
            'store_id' => $request->store_id,
            'customer_id' => $request->customer_id,
            'total_amount' => $totalAmount,
            'discount_amount' => $request->discount_amount ?? 0,
            'tax_amount' => $request->tax_amount ?? 0,
            'payment_method' => $request->payment_method,
            'user_id' => auth()->id(),
            'total_payment' => $request->amount_paid,
            'change_payment' => $request->change_payment,
            'note' => '',
        ];
        if ($request->payment_method == 'tempo') {
            $transactionData['remaining_payment'] = $request->total_amount;
            $transactionData['status'] = 'tempo';
        } else {
            $transactionData['status'] = 'lunas';
        }
        $transaction = SalesTransactions::create($transactionData);
        foreach (Cart::content() as $item) {
            SalesTransactionItems::create([
                'transaction_id' => $transaction->id,
                'product_pricing_id' => $item->id,
                'quantity' => $item->qty,
                'price' => $item->price,
                'subtotal' => $item->price * $item->qty,
            ]);

            // Kurangi stok produk
            $productPricing = ProductPricing::find($item->id);
            if ($productPricing) {
                $productPricing->stock -= $item->qty;
                $productPricing->save();
            }
        }
        if ($request->payment_method !== 'tempo') {
            $paymentMethodData = [
                'transaction_id' => $transaction->id,
                'payment_method' => $request->payment_method,
                'amount' => $request->amount_paid,
                'payment_date' => now()
            ];

            if ($request->payment_method === 'bank_transfer') {
                $paymentMethodData['bank_id'] = $request->bank_account;
            }

            PaymentMethod::create($paymentMethodData);
        }
        Cart::destroy();
        $notaUrl = route('print-nota', ['transactionId' => $transaction->id]);
        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dikonfirmasi',
            'transaction_id' => $transaction->id,
            'nota_url' => $notaUrl
        ]);
    }

    public function printNota($transactionId)
    {

        $transaction = SalesTransactions::find($transactionId);
        $store = Stores::find($transaction->store_id);
        $customer = Customers::find($transaction->customer_id);
        $kasir = User::find($transaction->user_id);
        $items = SalesTransactionItems::get_items_by_transaction_id($transactionId);
        return view('sales-transaction.nota', compact('transaction', 'store', 'customer', 'items', 'kasir'));
    }
}
