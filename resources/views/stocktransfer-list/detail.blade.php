@extends('template.app')
@section('title', 'Detail Transfer Stok')

@section('css')
<style>
    .card {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }
    .card-title {
        color: #495057;
        font-weight: bold;
    }
    .detail-row {
        margin-bottom: 15px;
    }
    .detail-label {
        font-weight: bold;
        color: #6c757d;
    }
    .badge {
        font-size: 0.9em;
        padding: 5px 10px;
    }
    .table {
        margin-bottom: 0;
    }
    .table th {
        background-color: #f8f9fa;
    }
    .total-section {
        background-color: #e9ecef;
        padding: 15px;
        border-radius: 8px;
        margin-top: 20px;
    }
    .btn-print {
        margin-right: 10px;
    }
    .table-totals td {
        text-align: right;
    }
    .table-totals td:last-child {
        width: 150px;
    }
    .text-right {
        text-align: right;
    }
</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('sales-return.index') }}">Daftar Retur Penjualan</a></li>
<li class="breadcrumb-item active" aria-current="page">Detail Retur Penjualan</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Detail Transfer Stok</h5>
        <!-- <div>
            <button class="btn btn-primary" onclick="cetakInvoice()">Cetak Invoice</button>
        </div> -->
    </div>
    <div class="card-body">
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">No. Referensi:</div>
                    <div class="col-md-3">{{ $transaction->transaction_id }}</div>
                    <div class="col-md-3 detail-label">Toko Asal:</div>
                    <div class="col-md-3">{{ $transaction->from_store_name }}</div>
                </div>
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">Tanggal Transfer:</div>
                    <div class="col-md-3">{{ date('d-m-Y', strtotime($transaction->transfer_date)) }}</div>
                    <div class="col-md-3 detail-label">Toko Tujuan:</div>
                    <div class="col-md-3">{{ $transaction->to_store_name }}</div>
                </div>
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">Status Pengiriman:</div>
                    <div class="col-md-3">{{ ucfirst($transaction->shipping_status) }}</div>
                    <div class="col-md-3 detail-label">Pengirim:</div>
                    <div class="col-md-3">{{ $transaction->user_name }}</div>
                </div>
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">Metode Pembayaran:</div>
                    <div class="col-md-3">{{ ucfirst($transaction->payment_method) }}</div>
                    <div class="col-md-3 detail-label">Status Pembayaran:</div>
                    <div class="col-md-3">{{ ucfirst($transaction->payment_status) }}</div>
                </div>
            </div>
        </div>

        <h6 class="mt-4 mb-3">Detail Produk</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>SKU</th>
                        <th>Barcode</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            {{ $product->product_name }}
                            @if($product->variasi_1 || $product->variasi_2 || $product->variasi_3)
                            <br>
                            <small class="text-muted">
                                {{ $product->variasi_1 }}
                                {{ $product->variasi_2 ? ', ' . $product->variasi_2 : '' }}
                                {{ $product->variasi_3 ? ', ' . $product->variasi_3 : '' }}
                            </small>
                            @endif
                        </td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->barcode }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ $product->unit_name }}</td>
                        <td class="text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($product->price * $product->quantity, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
       
        <h6 class="mt-4 mb-3">Biaya Tambahan</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Kategori Biaya</th>
                        <th>Deskripsi</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                    <tr>
                        <td>{{ $expense->name }}</td>
                        <td>{{ $expense->description }}</td>
                        <td class="text-right">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-hover table-totals">
                <tr>
                    <td colspan="3"><strong>Total Biaya Tambahan</strong></td>
                    <td><strong>Rp {{ number_format($expenses->sum('amount'), 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        <h6 class="mt-4 mb-3">Detail Pembayaran</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <th>Bank</th>
                        <th>Tanggal Pembayaran</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detail_payment as $payment)
                    <tr>
                        <td>{{ $payment->payment_method }}</td>
                        <td>{{ $payment->bank_name ?? '-' }}</td>
                        <td>{{ $payment->payment_date }}</td>
                        <td class="text-right">Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    @php
                        $total_bayar = $detail_payment->sum('amount_paid');
                        $total_expense = $expenses->sum('amount');
                        $sisa = $total_bayar - $total_expense;
                    @endphp
                    @if($sisa > 0)
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Kembalian</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($sisa, 0, ',', '.') }}</strong></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection