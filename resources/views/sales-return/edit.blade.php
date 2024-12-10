@extends('template.app')
@section('title', 'Edit Retur Penjualan')

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
<li class="breadcrumb-item active" aria-current="page">Edit Retur Penjualan</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Edit Retur Penjualan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('sales-return.update', $return->id) }}" method="POST">
            @csrf
            @method('PUT')
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
                        <div class="col-md-3">
                            <input type="date" class="form-control" name="return_date" value="{{ $return->return_date }}" required>
                        </div>
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
                        <tr>
                            <td>
                                <input type="hidden" name="return_sales_transaction_item_id[]" value="{{ $item->return_sales_transaction_item_id }}">
                                {{ $item->product_name }}
                                <br>
                                <small class="text-muted">
                                    {{ $item->variasi_1 }}
                                    {{ $item->variasi_2 ? ', ' . $item->variasi_2 : '' }}
                                    {{ $item->variasi_3 ? ', ' . $item->variasi_3 : '' }}
                                </small>
                            </td>
                            <td>{{ $item->quantity_sold }}</td>
                            <td>
                                <input type="number" class="form-control quantity-returned" name="quantity_returned[]" value="{{ $item->quantity_returned }}" min="0" max="{{ $item->quantity_sold }}" data-price="{{ $item->price }}" data-discount="{{ $item->discount_item }}">
                            </td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->discount_item, 0, ',', '.') }}</td>
                            <td class="total-price">Rp {{ number_format($item->price * $item->quantity_returned - $item->discount_item, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover table-totals">
                    <tr>
                        <td colspan="6"><strong>Subtotal Retur</strong></td>
                        <td>
                            <strong id="subtotal">Rp {{ number_format($return->total_return, 0, ',', '.') }}</strong>
                            <input type="hidden" name="subtotal_return" id="subtotal_return" value="{{ $return->total_return }}">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6"><strong>Diskon</strong></td>
                        <td><input type="number" class="form-control" name="discount_return" id="discount_return" value="{{ $return->discount_return }}"></td>
                    </tr>
                    <tr>
                        <td colspan="6"><strong>Pajak</strong></td>
                        <td><input type="number" class="form-control" name="tax_return" id="tax_return" value="{{ $return->tax_return }}"></td>
                    </tr>
                    <tr>
                        <td colspan="6"><strong>Total Retur:</strong></td>
                        <td><strong id="total_return">Rp {{ number_format($return->total_return - $return->discount_return + $return->tax_return, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
            
            <div class="form-group mt-4">
                <label for="note">Catatan</label>
                <textarea class="form-control" id="note" name="note" rows="3">{{ $return->note }}</textarea>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary custom-btn-color">Simpan Perubahan</button>
                <a href="{{ route('sales-return.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        function updateTotal() {
            let subtotal = 0;
            $('.quantity-returned').each(function(index) {
                let price = parseFloat($(this).data('price'));
                let discount = parseFloat($(this).data('discount'));
                let quantity = parseFloat($(this).val());
                let totalPrice = (price * quantity) - discount;
                subtotal += totalPrice;
                
                // Update total price for each item
                $(this).closest('tr').find('.total-price').text('Rp ' + totalPrice.toLocaleString('id-ID'));
            });

            let discount = parseFloat($('#discount_return').val()) || 0;
            let tax = parseFloat($('#tax_return').val()) || 0;
            let total = subtotal - discount + tax;

            $('#subtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
            $('#subtotal_return').val(subtotal);
            $('#total_return').text('Rp ' + total.toLocaleString('id-ID'));
        }

        $('.quantity-returned, #discount_return, #tax_return').on('input', updateTotal);

        updateTotal();
    });
</script>
@endpush
