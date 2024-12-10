@extends('template.app')
@section('title', 'Detail Retur Penjualan')

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
<li class="breadcrumb-item"><a href="{{ route('sales-return.index') }}">Daftar Retur Penjualan</a></li>
<li class="breadcrumb-item active" aria-current="page">Detail Retur Penjualan</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Detail Retur Penjualan</h5>
        <div>
            <button class="btn btn-primary custom-btn-color" onclick="cetakInvoice()">Cetak Invoice</button>
        </div>
    </div>
    <div class="card-body">
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">No. Nota Retur:</div>
                    <div class="col-md-3">{{ $return->return_no }}</div>
                    <div class="col-md-3 detail-label">Nama Toko:</div>
                    <div class="col-md-3">{{ $store->store_name }}</div>
                </div>
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">Tanggal Retur:</div>
                    <div class="col-md-3">{{ $return->return_date }}</div>
                    <div class="col-md-3 detail-label">Nama Pembeli:</div>
                    <div class="col-md-3">{{ $customer->name }}</div>
                </div>
                <div class="row detail-row">
                    <div class="col-md-3 detail-label">No. Invoice Penjualan:</div>
                    <div class="col-md-3">{{ $salesTransaction->transaction_id }}</div>
                </div>
            </div>
        </div>

        <h6 class="mt-4 mb-3">Detail Item Retur</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Nama Item</th>
                        <th>Jumlah Terjual</th>
                        <th>Jumlah Retur</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returnItems as $item)
                        @if($item->quantity_returned > 0)
                        <tr>
                            <td>
                                {{ $item->product_name }}
                                <br>
                                <small class="text-muted">
                                    {{ $item->variasi_1 }}
                                    {{ $item->variasi_2 ? ', ' . $item->variasi_2 : '' }}
                                    {{ $item->variasi_3 ? ', ' . $item->variasi_3 : '' }}
                                </small>
                            </td>
                            <td>{{ $item->quantity_sold }}</td>
                            <td>{{ $item->quantity_returned }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->discount_item, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->price * $item->quantity_returned - $item->discount_item, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-hover table-totals">
                <tr>
                    <td colspan="6"><strong>Subtotal Retur</strong></td>
                    <td><strong>Rp {{ number_format($return->total_return, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td colspan="6"><strong>Diskon</strong></td>
                    <td><strong>Rp {{ number_format($return->discount_return, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td colspan="6"><strong>Pajak</strong></td>
                    <td><strong>Rp {{ number_format($return->tax_return, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td colspan="6"><strong>Total Retur:</strong></td>
                    <td><strong>Rp {{ number_format($return->total_return - $return->discount_return + $return->tax_return, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>
        
        @if($return->note)
        <h6 class="mt-4 mb-3">Catatan</h6>
        <div class="card">
            <div class="card-body">
                {{ $return->note }}
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function cetakInvoice() {
    var returnId = {{ $return->id }};
    var printUrl = '{{ route("sales-return.cetak-invoice", ":id") }}'.replace(':id', returnId);
    window.open(printUrl, '_blank');
}
</script>
@endsection