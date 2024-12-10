@extends('template.app')
@section('title', 'Detail Biaya Pengeluaran')

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
<li class="breadcrumb-item"><a href="{{ route('biaya-pengeluaran.index') }}">Daftar Biaya Pengeluaran</a></li>
<li class="breadcrumb-item active" aria-current="page">Detail Biaya Pengeluaran</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Detail Biaya Pengeluaran</h5>
    </div>
    <div class="card-body">
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">No. Referensi:</div>
                    <div class="col-md-3">{{ $transaction->transaction_id }}</div>
                    <div class="col-md-3 detail-label">Toko:</div>
                    <div class="col-md-3">{{ $transaction->store_name }}</div>
                </div>
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">Tanggal Transaksi:</div>
                    <div class="col-md-3">{{ date('d-m-Y', strtotime($transaction->transaction_date)) }}</div>
                </div>
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">Status Pembayaran:</div>
                    <div class="col-md-3">
                        @if($transaction->payment_status == 'lunas')
                            <span class="badge bg-success text-white">Lunas</span>
                        @else
                            <span class="badge bg-warning">{{ ucfirst($transaction->payment_status) }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <h6 class="mt-4 mb-3">Detail Item Biaya</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                    <tr>
                        <td>{{ $expense->category_name }}</td>
                        <td class="text-right">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-hover table-totals">
                <tr>
                    <td colspan="1"><strong>Total Biaya</strong></td>
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
                    @foreach($payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_method }}</td>
                        <td>{{ $payment->bank_name ?? '-' }}</td>
                        <td>{{ date('d-m-Y', strtotime($payment->created_at)) }}</td>
                        <td class="text-right">Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection