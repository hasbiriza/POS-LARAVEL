@extends('template.app')
@section('title', 'Detail Stock Opname')

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
<li class="breadcrumb-item"><a href="{{ route('stockopname.index') }}">Daftar Stock Opname</a></li>
<li class="breadcrumb-item active" aria-current="page">Detail Stock Opname</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Detail Stock Opname</h5>
    </div>
    <div class="card-body">
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">No. Referensi:</div>
                    <div class="col-md-3">{{ $stockOpname->reference_no }}</div>
                    <div class="col-md-3 detail-label">Toko:</div>
                    <div class="col-md-3">{{ $stockOpname->store_name }}</div>
                </div>
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">Tanggal Stock Opname:</div>
                    <div class="col-md-3">{{ date('d-m-Y', strtotime($stockOpname->date)) }}</div>
                    <div class="col-md-3 detail-label">Petugas:</div>
                    <div class="col-md-3">{{ $stockOpname->user_name }}</div>
                </div>
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">Catatan:</div>
                    <div class="col-md-9">{{ $stockOpname->note ?? '-' }}</div>
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
                        <th>Stok Sistem</th>
                        <th>Stok Fisik</th>
                        <th>Selisih</th>
                        <th>Harga Satuan</th>
                        <th>Total Kerugian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detail as $item)
                    <tr>
                        <td>
                            {{ $item->product_name }}
                            @if($item->variasi_1 || $item->variasi_2 || $item->variasi_3)
                            <br>
                            <small class="text-muted">
                                {{ $item->variasi_1 }}
                                {{ $item->variasi_2 ? ', ' . $item->variasi_2 : '' }}
                                {{ $item->variasi_3 ? ', ' . $item->variasi_3 : '' }}
                            </small>
                            @endif
                        </td>
                        <td>{{ $item->sku }}</td>
                        <td>{{ $item->barcode }}</td>
                        <td>{{ $item->system_stock }}</td>
                        <td>{{ $item->physical_stock }}</td>
                        <td>{{ $item->stock_difference }}</td>
                        <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item->stock_difference * $item->unit_price, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-hover table-totals">
                <tr>
                    <td colspan="3"><strong>Total Kerugian</strong></td>
                    <td><strong>Rp {{ number_format($detail->sum(function($item) { return $item->stock_difference * $item->unit_price; }), 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>
    </div>
</div>

@endsection