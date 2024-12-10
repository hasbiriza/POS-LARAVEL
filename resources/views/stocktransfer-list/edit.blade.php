@extends('template.app')
@section('title', 'Edit Transfer Stok')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet">
<style type="text/css">
    .select2-container {
        z-index: 9999;
    }
</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Edit Transfer Stok</li>
@endsection

@section('content')
<div class="flex-grow-1">
    @include('components.toast-notification')
    <form id="stockTransferForm" method="POST" action="{{ route('stocktransfer-list.update', $stockTransfer->id) }}">
    @csrf
    @method('PUT')
    <div class="card mb-4">
        <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="reference_no" class="form-label">No. Referensi</label>
                        <input type="text" class="form-control form-control" id="reference_no" name="reference_no" value="{{ $stockTransfer->transaction_id }}" required readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="transfer_date" class="form-label">Tanggal Transfer</label>
                        <input type="date" class="form-control form-control" id="transfer_date" name="transfer_date" value="{{ $stockTransfer->transfer_date }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status Transfer</label>
                        <select class="form-select form-select" id="status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="dikirim" {{ $stockTransfer->shipping_status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="diterima" {{ $stockTransfer->shipping_status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="from_store" class="form-label">Dari Toko</label>
                        <select class="form-select form-select" id="from_store" name="from_store" required>
                            <option value="">Pilih Toko Asal</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ $stockTransfer->from_store_id == $store->id ? 'selected' : '' }}>{{ $store->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="to_store" class="form-label">Ke Toko</label>
                        <select class="form-select form-select" id="to_store" name="to_store" required>
                            <option value="">Pilih Toko Tujuan</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ $stockTransfer->to_store_id == $store->id ? 'selected' : '' }}>{{ $store->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4" id="receiverContainer">
                        <label for="receiver" class="form-label">Penerima</label>
                        <select class="form-select form-select" id="receiver" name="receiver">
                            <option value="">Pilih Penerima</option>
                        </select>
                    </div>
                </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="mb-4">
                <label for="productSearch" class="form-label">Cari Produk</label>
                <x-select2 class="form-control select2" id="productSearch" name="product" placeholder="Cari produk..."></x-select2>
            </div>
            <div id="selectedProducts" class="mb-4">
                <table class="table table-bordered product-item mb-3">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Qty Transfer</th>
                            <th>Harga Beli</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        @foreach($stockTransferDetail as $detail)
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="products[{{ $detail->product_pricing_id }}][name]" value="{{ $detail->product_name }} {{ $detail->variasi_1 }} {{ $detail->variasi_2 }} {{ $detail->variasi_3 }}" readonly>
                                <input type="hidden" name="products[{{ $detail->product_pricing_id }}][barcode]" value="{{ $detail->barcode }}">
                                <input type="hidden" name="products[{{ $detail->product_pricing_id }}][id]" value="{{ $detail->product_pricing_id }}">
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="number" class="form-control qty" name="products[{{ $detail->product_pricing_id }}][qty]" value="{{ $detail->quantity }}" min="1">
                                    <span class="input-group-text">{{ $detail->unit_name }}</span>
                                </div>
                            </td>
                            <td>
                                <input type="number" class="form-control purchase_price" name="products[{{ $detail->product_pricing_id }}][purchase_price]" value="{{ $detail->purchase_price }}" min="0" readonly>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-product">X</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mb-4">
                <h5 style="display: inline-block; margin-right: 10px;">Biaya Lainnya</h5>
                <table class="table table-bordered expense-item mt-3">
                    <thead>
                        <tr>
                            <th>Kategori Biaya</th>
                            <th>Jumlah</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $expense)
                        <tr>
                            <td>
                                <select name="expense_category_id[]" class="form-select expense-category">
                                    @foreach($expenseCategories as $category)
                                        <option value="{{ $category->id }}" {{ $expense->expense_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="expense_amount[]" value="{{ $expense->amount }}" class="form-control expense-amount" min="0"></td>
                            <td><input type="text" name="expense_note[]" value="{{ $expense->description }}" class="form-control expense-note"></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-expense">X</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mb-4">
                <h5 class="card-title mb-3">Pembayaran</h5>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered" id="paymentTable">
                            <thead>
                                <tr>
                                    <th>Tanggal Bayar</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Akun Bank</th>
                                    <th>Jumlah Bayar</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                <tr>
                                    <td><input type="date" class="form-control payment-date" name="payment[date][]" value="{{ $payment->payment_date }}" required></td>
                                    <td>
                                        <select class="form-select payment-method" name="payment[method][]" required>
                                            <option value="">Pilih metode pembayaran</option>
                                            <option value="cash" {{ $payment->payment_method == 'cash' ? 'selected' : '' }}>Tunai</option>
                                            <option value="bank_transfer" {{ $payment->payment_method == 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                            <option value="tempo" {{ $payment->payment_method == 'tempo' ? 'selected' : '' }}>Tempo</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select payment-account" name="payment[account][]" {{ $payment->payment_method != 'bank_transfer' ? 'disabled' : '' }}>
                                            <option value="">Pilih Akun Bank</option>
                                            @foreach($banks as $bank)
                                                <option value="{{ $bank->id }}" {{ $payment->bank_id == $bank->id ? 'selected' : '' }}>{{ $bank->name }} - {{ $bank->account_number }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control payment-amount" name="payment[amount][]" value="{{ $payment->amount_paid }}" {{ $payment->payment_method == 'tempo' ? 'readonly' : '' }}></td>
                                    <td><input type="text" class="form-control payment-note" name="payment[note][]" value="{{ $payment->payment_note }}"></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total Bayar:</strong></td>
                                    <td><input type="number" class="form-control" id="totalPayment" value="{{ $payments->sum('amount_paid') }}" readonly></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total Biaya Lain:</strong></td>
                                    @php $grandTotal = $stockTransfer->total_amount + $expenses->sum('amount'); @endphp
                                    <td><input type="number" class="form-control" id="grandTotal" name="grandTotal" value="{{ $grandTotal }}" readonly></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Sisa Pembayaran:</strong></td>
                                    <td><input type="number" class="form-control" id="change_payment" name="change_payment" value="{{ $grandTotal - $payments->sum('amount_paid') }}" readonly></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary float-end custom-btn-color">Simpan</button>
        </div>
    </div>
    </form>
</div>

@endsection

@section('js')
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
<script src="{{ asset('assets/js/ui-toasts.js') }}"></script>
<script>

$(document).ready(function() {
    $('#productSearch').select2({
        placeholder: 'Cari produk...',
        ajax: {
            url: '{{ route("stock-transfer.search") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    name: params.term,
                    store_id: $('#from_store').val()
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.product_name + ' ' + (item.variasi_1 || '') + ' ' + (item.variasi_2 || '') + ' ' + (item.variasi_3 || ''),
                            id: item.product_pricing_id,
                            data: item
                        }
                    })
                };
            },
            cache: true
        }
    });

    $('#productSearch').on('select2:select', function (e) {
        var data = e.params.data.data;
        var productId = data.product_pricing_id;
        
        if ($('#productTableBody').find('input[name="products[' + productId + '][name]"]').length > 0) {
            $('#productSearch').val(null).trigger('change');
            return;
        }
        
        var productHtml = `
            <tr>
                <td>
                    <input type="text" class="form-control" name="products[${data.product_pricing_id}][name]" value="${data.product_name} ${data.variasi_1 || ''} ${data.variasi_2 || ''} ${data.variasi_3 || ''}" readonly>
                    <input type="hidden" name="products[${data.product_pricing_id}][barcode]" value="${data.barcode}">
                    <input type="hidden" name="products[${data.product_pricing_id}][id]" value="${data.product_pricing_id}">
                </td>
                <td>
                    <div class="input-group">
                        <input type="number" class="form-control qty" name="products[${data.product_pricing_id}][qty]" placeholder="QTY" style="width: 70px;" value="1" min="1">
                        <span class="input-group-text">${data.unit_name}</span>
                    </div>
                </td>
                <td>
                    <input type="number" class="form-control purchase_price" name="products[${data.product_pricing_id}][purchase_price]" placeholder="Harga" value="${data.purchase_price || 0}">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-product">X</button>
                </td>
            </tr>
        `;
        $('#productTableBody').append(productHtml);
        
        $('#productSearch').val(null).trigger('change');
    });

    $(document).on('click', '.remove-product', function() {
        $(this).closest('tr').remove();
    });

    $('#stockTransferForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: '{{ route("stocktransfer-list.update", $stockTransfer->id) }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if(response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data pemindahan stok berhasil diperbarui',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '{{ route("stock-transfer.index") }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat memperbarui data pemindahan stok'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan: ' + xhr.responseText
                });
            }
        });
    });
});

</script>
<script>
$(document).ready(function() {
    var statusSelect = $('#status');
    var toStoreSelect = $('#to_store');
    var receiverSelect = $('#receiver');
    var receiverContainer = $('#receiverContainer');

    statusSelect.on('change', function() {
        if ($(this).val() === 'diterima') {
            var toStoreId = toStoreSelect.val();
            if (toStoreId) {
                $.ajax({
                    url: '{{ route("get-users-by-store") }}',
                    type: 'GET',
                    data: { to_store: toStoreId },
                    success: function(response) {
                        receiverSelect.empty();
                        receiverSelect.append('<option value="">Pilih Penerima</option>');
                        $.each(response.users, function(key, value) {
                            receiverSelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                        // receiverContainer.show();
                    },
                    error: function(xhr) {
                        console.error('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            }
        } else {
            // receiverContainer.hide();
            receiverSelect.empty();
            receiverSelect.append('<option value="">Pilih Penerima</option>');
        }
    });

    toStoreSelect.on('change', function() {
        if (statusSelect.val() === 'diterima') {
            statusSelect.trigger('change');
        }
    });
});
</script>
@endsection
