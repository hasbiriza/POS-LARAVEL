@extends('template.app')
@section('title', 'Detail Penjualan')

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
</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('sales-list.index') }}">Daftar Penjualan</a></li>
<li class="breadcrumb-item active" aria-current="page">Detail Penjualan</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Detail Penjualan</h5>
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
                    <div class="col-md-3 detail-label">Nama Pelanggan:</div>
                    <div class="col-md-3">{{ $customer->name }}</div>
                </div>
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">Tanggal Transaksi:</div>
                    <div class="col-md-3">{{ $transaction->transaction_date }}</div>
                    <div class="col-md-3 detail-label">Nama Toko:</div>
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
                    <div class="col-md-3 detail-label">Kasir:</div>
                    <div class="col-md-3">{{ $kasir->name }}</div>
                </div>
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">Status:</div>
                    <div class="col-md-3">
                        @if($transaction->status == 'lunas')
                            <span class="badge bg-success">Lunas</span>
                        @elseif($transaction->status == 'tempo')
                            <span class="badge bg-danger">Belum Lunas</span>
                        @else
                            {{ $transaction->status }}
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
                        <th>Unit</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr>
                        <td>
                            {{ $item->item_name }}
                            <br>
                            <small class="text-muted">
                                {{ $item->variasi_1 }}
                                {{ $item->variasi_2 ? ', ' . $item->variasi_2 : '' }}
                                {{ $item->variasi_3 ? ', ' . $item->variasi_3 : '' }}
                            </small>
                        </td>
                        <td>{{ $item->unit_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->sale_price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->quantity * $item->sale_price, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Diskon:</strong></td>
                        <td><strong>Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Pajak:</strong></td>
                        <td><strong>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Total:</strong></td>
                        <td><strong>Rp {{ number_format($transaction->total_amount - $transaction->discount_amount + $transaction->tax_amount, 0, ',', '.') }}</strong></td>
                    </tr>
                </tfoot>
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
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paymentMethods as $payment)
                    <tr>
                        <td>{{ $payment->payment_date }}</td>
                        <td>{{ $payment->payment_method }}</td>
                        <td>{{ $payment->bank_name }} - {{ $payment->bank_account_number }}</td>
                        <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total Pembayaran:</strong></td>
                        <td><strong>Rp {{ number_format($transaction->status == 'tempo' ? $paymentMethods->sum('amount') : $transaction->total_payment, 0, ',', '.') }}</strong></td>
                    </tr>
                    @php
                        $totalTransaksi = $transaction->total_amount - $transaction->discount_amount + $transaction->tax_amount;
                        $totalPembayaran = $transaction->status == 'tempo' ? $paymentMethods->sum('amount') : $transaction->total_payment;
                        $selisih = $totalPembayaran - $totalTransaksi;
                        $sisaPembayaranTempo = $transaction->remaining_payment;
                    @endphp
                    @if($selisih < 0)
                    <tr>
                        <td colspan="3" class="text-end"><strong>Sisa Pembayaran (Tempo):</strong></td>
                        <td><strong>Rp {{ number_format($sisaPembayaranTempo, 0, ',', '.') }}</strong></td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="3" class="text-end"><strong>Kembalian:</strong></td>
                        <td><strong>Rp {{ number_format($selisih, 0, ',', '.') }}</strong></td>
                    </tr>
                    @endif
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
function cetakNota() {
    var urlParts = window.location.pathname.split('/');
    var transactionId = urlParts[urlParts.length - 1];
    var printUrl = '{{ url("/print-nota") }}/' + transactionId;
    window.open(printUrl, '_blank');
}

function cetakInvoice() {
    var urlParts = window.location.pathname.split('/');
    var transactionId = urlParts[urlParts.length - 1];
    var printUrl = '{{ url("/print-invoice") }}/' + transactionId;
    window.open(printUrl, '_blank');
}
</script>
@endsection