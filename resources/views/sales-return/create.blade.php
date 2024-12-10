@extends('template.app')
@section('title', 'Buat Retur Penjualan')
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
<li class="breadcrumb-item"><a href="{{ route('sales-return.index') }}">Daftar Retur Penjualan</a></li>
<li class="breadcrumb-item active" aria-current="page">Buat Retur Penjualan</li>
@endsection

@section('content')
<div class="flex-grow-1">

    @include('components.toast-notification')
    <form id="returnForm" method="POST" action="{{ route('sales-return.store') }}">
    @csrf
    <div class="card mb-4">
        <div class="card-body">
                <div class="row g-3">
                <div class="col-md-12">
                        <label for="invoice_no" class="form-label">No. Invoice Penjualan</label>
                        <x-select2 id="invoice_no" name="invoice_no" onchange="getInvoiceDetails(this.value)" required>
                            <option value="">Pilih Invoice</option>
                            @foreach($sales_transactions_id as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </x-select2>
                    </div>
                    <div class="col-md-3">
                        <label for="return_no" class="form-label">No. Nota Retur</label>
                        <input type="text" class="form-control" id="return_no" name="return_no" value="{{ $return_number }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="store" class="form-label">Toko</label>
                        <input type="hidden" class="form-control" id="store_id" name="store_id" readonly>
                        <input type="text" class="form-control" id="store" name="store" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="customer" class="form-label">Pelanggan</label>
                        <input type="text" class="form-control" id="customer" name="customer" readonly>
                        <input type="hidden" class="form-control" id="customer_id" name="customer_id" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="return_date" class="form-label">Tanggal Retur</label>
                        <input type="date" class="form-control" id="return_date" name="return_date" required>
                    </div>
                </div>
        </div>
    </div>

    <div id="invoiceDetailsCard" class="card" style="display: none;">
        <div class="card-body">
            <div class="table-responsive">
                <table id="invoiceItemsTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty Jual</th>
                            <th>Qty Kembali</th>
                            <th>Diskon</th>
                            <th>Total Jual</th>
                            <th>Total Retur</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="text-end">Subtotal</td>
                            <td><input type="number" class="form-control" id="subtotalReturn" name="subtotal_return" readonly></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-end">Diskon</td>
                            <td><input type="number" class="form-control" id="discountReturn" name="discount_return" value="0" oninput="calculateTotalReturn()"></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-end">Pajak</td>
                            <td><input type="number" class="form-control" id="taxReturn" name="tax_return" value="0" oninput="calculateTotalReturn()"></td>
                            <td colspan="2"></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-end">Total</td>
                            <td><input type="number" class="form-control" id="totalReturn" name="total_return" readonly></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="mt-3">
                <div class="form-group">
                    <label for="note">Catatan:</label>
                    <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary custom-btn-color" form="returnForm">Simpan Retur</button>
            </div>
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
function getInvoiceDetails(invoiceId) {
    if (!invoiceId) {
        $('#invoiceDetailsCard').hide();
        return;
    }

    $.ajax({
        url: "{{ route('sales-return.get-invoice-details', ':transactionId') }}".replace(':transactionId', invoiceId),
        method: 'GET',
        success: function(response) {
            let tableBody = $('#invoiceItemsTable tbody');
            tableBody.empty();

            response.items.forEach(function(item) {
                let variasi = [item.variasi_1, item.variasi_2, item.variasi_3].filter(Boolean).join(', ');
                let productName = item.item_name + (variasi ? ` (${variasi})` : '');
                tableBody.append(`
                    <tr>
                        <td>
                            <input type="hidden" name="products[${item.product_pricing_id}][sales_transaction_items_id]" value="${item.sales_transaction_items_id}">
                            <input type="hidden" name="products[${item.product_pricing_id}][product_pricing_id]" value="${item.product_pricing_id}">
                            <input type="text" class="form-control" name="products[${item.product_pricing_id}][name]" value="${productName}" readonly style="width: 250px;">
                        </td>
                        <td>
                            <input type="number" class="form-control price" name="products[${item.product_pricing_id}][sale_price]" value="${item.sale_price}" readonly style="width: 100px;">
                        </td>
                        <td>
                            <div class="input-group" style="width: 150px;">
                                <input type="number" class="form-control qty" name="products[${item.product_pricing_id}][qty]" value="${item.quantity}" readonly>
                                <span class="input-group-text">${item.unit_name}</span>
                            </div>
                        </td>
                        <td>
                            <input type="number" class="form-control return_qty" name="products[${item.product_pricing_id}][return_qty]" placeholder="Jml Retur" value="0" min="0" max="${item.quantity}" oninput="calculateReturnTotal(this)" style="width: 80px;">
                        </td>
                        <td>
                            <input type="number" class="form-control discount" name="products[${item.product_pricing_id}][discount]" value="${item.discount_item}" oninput="calculateReturnTotal(this)" style="width: 80px;">
                        </td>
                        <td>
                            <input type="number" class="form-control total" name="products[${item.product_pricing_id}][total]" value="${item.quantity * item.sale_price}" readonly style="width: 100px;">
                        </td>
                        <td>
                            <input type="number" class="form-control return_total" name="products[${item.product_pricing_id}][return_total]" readonly style="width: 100px;">
                        </td>
                    </tr>
                `);
            });
            $('#invoiceDetailsCard').show();
            $('#store').val(response.store.store_name);
            $('#store_id').val(response.store.id);
            $('#customer').val(response.customer.name);
            $('#customer_id').val(response.customer.id);
            $('#taxReturn').val(response.transaction.tax_amount);
            $('#discountReturn').val(response.transaction.discount_amount);
        },
        error: function(xhr, status, error) {
            console.error(error);
            Swal.fire('Error', 'Gagal mengambil detail invoice', 'error');
        }
    });
}

function calculateReturnTotal(input) {
    let row = $(input).closest('tr');
    let price = parseFloat(row.find('.price').val());
    let returnQty = parseFloat(row.find('.return_qty').val()) || 0;
    let discount = parseFloat(row.find('.discount').val()) || 0;
    let returnTotal = (price * returnQty) - discount;
    row.find('.return_total').val(returnTotal.toFixed(0));
    
    updateTotalReturn();
}

function updateTotalReturn() {
    let subtotal = 0;
    $('.return_total').each(function() {
        subtotal += parseFloat($(this).val()) || 0;
    });
    $('#subtotalReturn').val(subtotal.toFixed(0));
    calculateTotalReturn();
}

function calculateTotalReturn() {
    let subtotal = parseFloat($('#subtotalReturn').val()) || 0;
    let discount = parseFloat($('#discountReturn').val()) || 0;
    let tax = parseFloat($('#taxReturn').val()) || 0;
    let total = subtotal - discount + tax;
    $('#totalReturn').val(total.toFixed(0));
}

$(document).on('input', '.return_qty, .discount', function() {
    calculateReturnTotal(this);
});

$('#returnForm').on('submit', function(e) {
    e.preventDefault();
    if (!validateForm()) {
        return;
    }
    let formData = $(this).serialize();
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                Swal.fire('Sukses', response.message, 'success').then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('sales-return.index') }}";
                    }
                });
            } else {
                Swal.fire('Error', 'Terjadi kesalahan saat menyimpan retur', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            Swal.fire('Error', 'Terjadi kesalahan saat menyimpan retur', 'error');
        }
    });
});

function validateForm() {
    let isValid = true;

    if (!$('#invoice_no').val()) {
        Swal.fire('Error', 'Pilih No. Invoice Penjualan', 'error');
        isValid = false;
    }

    if (!$('#return_date').val()) {
        Swal.fire('Error', 'Masukkan Tanggal Retur', 'error');
        isValid = false;
    }

    let hasReturnedItem = false;
    $('.return_qty').each(function() {
        if (parseFloat($(this).val()) > 0) {
            hasReturnedItem = true;
            return false;
        }
    });

    if (!hasReturnedItem) {
        Swal.fire('Error', 'Minimal satu item harus diretur', 'error');
        isValid = false;
    }

    return isValid;
}
</script>

@endsection