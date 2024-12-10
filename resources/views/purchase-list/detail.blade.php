@extends('template.app')
@section('title', 'Detail Pembelian')

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
</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('purchase-list.index') }}">Daftar Pembelian</a></li>
<li class="breadcrumb-item active" aria-current="page">Detail Pembelian</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Detail Pembelian</h5>
        <div>
            <button class="btn btn-primary btn-print custom-btn-color" onclick="cetakNota()">Cetak Nota</button>
            <button class="btn btn-secondary" onclick="cetakInvoice()">Cetak Invoice</button>
        </div>
    </div>
    <div class="card-body">
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">ID Transaksi:</div>
                    <div class="col-md-3">{{ $transaction->transaction_id }}</div>
                    <div class="col-md-3 detail-label">Nama Supplier:</div>
                    <div class="col-md-3">{{ $supplier->store_name }}</div>
                </div>
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">Tanggal Transaksi:</div>
                    <div class="col-md-3">{{ $transaction->purchase_date }}</div>
                    <div class="col-md-3 detail-label">Nama Gudang:</div>
                    <div class="col-md-3">{{ $store->store_name }}</div>
                </div>
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">Metode Pembayaran:</div>
                    <div class="col-md-3">
                        @if($transaction->payment_method == 'cash')
                            <span class="badge bg-success">Cash</span>
                        @elseif($transaction->payment_method == 'bank_transfer')
                            <span class="badge bg-info">Transfer</span>
                        @elseif($transaction->payment_method == 'tempo')
                            <span class="badge bg-warning">Tempo</span>
                        @else
                            <span class="badge bg-secondary">{{ $transaction->payment_method }}</span>
                        @endif
                    </div>
                </div>
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">Status:</div>
                    <div class="col-md-3">
                        @if($transaction->payment_status == 'lunas')
                            <span class="badge bg-success">Lunas</span>
                        @elseif($transaction->payment_status == 'tempo')
                            <span class="badge bg-danger">Belum Lunas</span>
                        @else
                            {{ $transaction->payment_status }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <h6 class="mt-4 mb-3">Detail Item</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Nama Item</th>
                        <th>SKU</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            {{ $product->product_name }}
                            <br>
                            <small class="text-muted">
                                {{ $product->variasi_1 }}
                                {{ $product->variasi_2 ? ', ' . $product->variasi_2 : '' }}
                                {{ $product->variasi_3 ? ', ' . $product->variasi_3 : '' }}
                            </small>
                        </td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($product->diskon, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($product->quantity * $product->purchase_price - $product->diskon, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-hover table-totals">
                <tr>
                    <td colspan="5"><strong>Sub Total:</strong></td>
                    <td><strong>Rp {{ number_format($products->sum(function($product) { return $product->quantity * $product->purchase_price - $product->diskon; }), 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td colspan="5"><strong>Diskon:</strong></td>
                    <td><strong>Rp {{ number_format($transaction->discount, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td colspan="5"><strong>Grand Total:</strong></td>
                    <td><strong>Rp {{ number_format($transaction->total_amount - $transaction->discount, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>
        
        <h6 class="mt-4 mb-3">Biaya Lain-lain</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Kategori Biaya</th>
                        <th>Jumlah</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                    <tr>
                        <td>{{ $expense->name }}</td>
                        <td>Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                        <td>{{ $expense->note }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-hover table-totals">
                <tr>
                    <td><strong>Total Biaya Lain-lain:</strong></td>
                    <td><strong>Rp {{ number_format($expenses->sum('amount'), 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        <h6 class="mt-4 mb-3">Pembayaran</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Tanggal Pembayaran</th>
                        <th>Metode Pembayaran</th>
                        <th>Bank</th>
                        <th>Catatan</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detail_payment as $payment)
                    <tr>
                        <td>{{ $payment->payment_date }}</td>
                        <td>{{ $payment->payment_method }}</td>
                        <td>{{ $payment->bank_name }} - {{ $payment->bank_account_number }}</td>
                        <td>{{ $payment->payment_note }}</td>
                        <td>Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-hover table-totals">
                <tr>
                    <td><strong>Total Pembayaran:</strong></td>
                    <td><strong>Rp {{ number_format($detail_payment->sum('amount_paid'), 0, ',', '.') }}</strong></td>
                </tr>
                @if($transaction->remaining_payment > 0)
                <tr>
                    <td><strong>Sisa Pembayaran:</strong></td>
                    <td><strong>Rp {{ number_format($transaction->remaining_payment, 0, ',', '.') }}</strong></td>
                </tr>
                @else
                <tr>
                    <td><strong>Kembalian:</strong></td>
                    <td><strong>Rp {{ number_format($detail_payment->sum('amount_paid') - ($transaction->total_amount - $transaction->discount + $expenses->sum('amount')), 0, ',', '.') }}</strong></td>
                </tr>
                @endif
            </table>
        </div>
    </div>
</div>

<script>
function cetakNota() {
    var urlParts = window.location.pathname.split('/');
    var transactionId = urlParts[urlParts.length - 1];
    var printUrl = '{{ route("print-nota-pembelian", ":id") }}'.replace(':id', transactionId);
    window.open(printUrl, '_blank');
}

function cetakInvoice() {
    var urlParts = window.location.pathname.split('/');
    var transactionId = urlParts[urlParts.length - 1];
    var printUrl = '{{ route("print-invoice-pembelian", ":id") }}'.replace(':id', transactionId);
    window.open(printUrl, '_blank');
}
</script>
@endsection