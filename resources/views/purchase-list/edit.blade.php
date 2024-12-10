@extends('template.app')
@section('title', 'Edit Pembelian')
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
<li class="breadcrumb-item active" aria-current="page">Edit Pembelian</li>
@endsection

@section('content')
<div class="flex-grow-1">
    @include('components.toast-notification')
    <form id="purchaseForm" method="POST" action="{{ route('purchase-list.update', $purchase->id) }}">
    @csrf
    @method('PUT')
    <div class="card mb-4">
        <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="supplier" class="form-label">Supplier</label>
                        <select class="form-select form-select" id="supplier" name="supplier" required>
                            <option value="">Pilih Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="reference_no" class="form-label">No. Referensi</label>
                        <input type="text" class="form-control form-control" id="reference_no" name="reference_no" value="{{ $purchase->reference_no }}" required readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="purchase_date" class="form-label">Tanggal Pembelian</label>
                        <input type="date" class="form-control form-control" id="purchase_date" name="purchase_date" value="{{ $purchase->purchase_date }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="store" class="form-label">Toko</label>
                        <select class="form-select form-select" id="store" name="store" required>
                            <option value="">Pilih Toko</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ $purchase->store_id == $store->id ? 'selected' : '' }}>{{ $store->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status Pembelian</label>
                        <select class="form-select form-select" id="status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="dipesan" {{ $purchase->status == 'dipesan' ? 'selected' : '' }}>Dipesan</option>
                            <option value="diterima" {{ $purchase->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
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
                            <th>QTY</th>
                            <th>Harga Beli</th>
                            <th>Diskon</th>
                            <th>Total Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        @foreach($purchaseDetail as $detail)
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="products[{{ $detail->product_pricing_id }}][name]" value="{{ $detail->product_name }} {{ $detail->variasi_1 }} {{ $detail->variasi_2 }} {{ $detail->variasi_3 }}" readonly>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="number" class="form-control qty" name="products[{{ $detail->product_pricing_id }}][qty]" value="{{ $detail->quantity }}" min="1">
                                    <span class="input-group-text">{{ $detail->unit_name }}</span>
                                </div>
                            </td>
                            <td>
                                <input type="number" class="form-control price" name="products[{{ $detail->product_pricing_id }}][purchase_price]" value="{{ $detail->purchase_price }}" min="0">
                            </td>
                            <td>
                                <input type="number" class="form-control discount" name="products[{{ $detail->product_pricing_id }}][discount]" value="{{ $detail->diskon }}" min="0">
                            </td>
                            <td>
                                <input type="number" class="form-control total" name="products[{{ $detail->product_pricing_id }}][total]" value="{{ $detail->quantity * $detail->purchase_price - $detail->diskon }}" readonly>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-product">X</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end">Subtotal:</td>
                            <td><input type="number" class="form-control" id="subtotalAll" name="subtotal" value="{{ $purchaseDetail->sum(function($detail) { return $detail->purchase_price * $detail->quantity - $detail->diskon; }) }}" readonly></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end">Diskon:</td>
                            <td><input type="number" class="form-control" id="discountAll" value="{{ $purchase->discount }}" name="total_discount"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end">Total:</td>
                            <td><input type="number" class="form-control" id="totalAll" value="{{ $purchase->total_amount - $purchase->discount }}" readonly name="total_amount"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="mb-4">
                <h5 style="display: inline-block; margin-right: 10px;">Biaya Lainnya</h5>
                <button type="button" class="btn btn-primary btn-sm custom-btn-color" id="add-expense" style="display: inline-block;">Tambah Biaya</button>
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
                        @foreach($purchaseExpense as $expense)
                        <tr>
                            <td>
                                <select name="expense_category_id[]" class="form-select expense-category">
                                    @foreach($expenseCategories as $category)
                                        <option value="{{ $category->id }}" {{ $expense->expense_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="expense_amount[]" value="{{ $expense->amount }}" class="form-control expense-amount" min="0"></td>
                            <td><input type="text" name="expense_note[]" value="{{ $expense->note }}" class="form-control expense-note"></td>
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
                                @foreach($purchasePayment as $payment)
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
                                    <td><input type="number" class="form-control" id="totalPayment" value="{{ $purchasePayment->sum('amount_paid') }}" readonly></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total Pembelian + Biaya Lain:</strong></td>
                                    @php $grandTotal = $purchase->total_amount - $purchase->discount + $purchaseExpense->sum('amount'); @endphp
                                    <td><input type="number" class="form-control" id="grandTotal" name="grandTotal" value="{{ $grandTotal }}" readonly></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Sisa Pembayaran:</strong></td>
                                    <td><input type="number" class="form-control" id="change_payment" name="change_payment" value="{{ $grandTotal - $purchasePayment->sum('amount_paid') }}" readonly></td>
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
    $('#add-expense').click(function() {
        var newRow = `
            <tr>
                <td>
                    <select name="expense_category_id[]" class="form-select expense-category">
                        @foreach($expenseCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="expense_amount[]" class="form-control expense-amount" min="0"></td>
                <td><input type="text" name="expense_note[]" class="form-control expense-note"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-expense">X</button></td>
            </tr>
        `;
        $('.expense-item tbody').append(newRow);
        calculateGrandTotal();
    });

    $(document).on('click', '.remove-expense', function() {
        $(this).closest('tr').remove();
        calculateGrandTotal();
    });

    $('#productSearch').select2({
            placeholder: 'Cari produk...',
            ajax: {
                url: '{{ route("purchase.search") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        name: params.term
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
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" class="form-control qty" name="products[${data.product_pricing_id}][qty]" placeholder="QTY" style="width: 70px;" value="1" min="1">
                            <span class="input-group-text">${data.unit_name}</span>
                        </div>
                    </td>
                    <td style="width: 150px;">
                        <input type="number" class="form-control price" name="products[${data.product_pricing_id}][purchase_price]" placeholder="Harga Beli" value="${data.purchase_price}" min="0">
                    </td>
                    <td style="width: 150px;">
                        <input type="number" class="form-control discount" name="products[${data.product_pricing_id}][discount]" placeholder="Diskon" value="0" min="0">
                    </td>
                    <td>
                        <input type="number" class="form-control total" name="products[${data.product_pricing_id}][total]" placeholder="Total Harga" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-product">X</button>
                    </td>
                </tr>
            `;
            $('#productTableBody').append(productHtml);
            
            calculateTotal($('#productTableBody tr:last'));
            calculateAllTotals();
            calculateGrandTotal();
            
            $('#productSearch').val(null).trigger('change');
        });

        $(document).on('input', '.qty, .price, .discount, .expense-amount, .payment-amount', function() {
            var row = $(this).closest('tr');
            calculateTotal(row);
            calculateAllTotals();
            calculateGrandTotal();
            updateTotalPayment();
        });

        $('#discountAll').on('input', function() {
            calculateAllTotals();
            calculateGrandTotal();
        });

        function calculateTotal(row) {
            var qty = parseFloat(row.find('.qty').val()) || 0;
            var price = parseFloat(row.find('.price').val()) || 0;
            var discount = parseFloat(row.find('.discount').val()) || 0;
            
            var total = (qty * price) - discount;
            row.find('.total').val(total.toFixed(0));
        }

        function calculateAllTotals() {
            var subtotalAll = 0;
            var discountAll = parseFloat($('#discountAll').val()) || 0;

            $('.total').each(function() {
                subtotalAll += parseFloat($(this).val()) || 0;
            });

            $('#subtotalAll').val(subtotalAll.toFixed(0));
            $('#totalAll').val((subtotalAll - discountAll).toFixed(0));
        }

        function calculateGrandTotal() {
            var totalAll = parseFloat($('#totalAll').val()) || 0;
            var expenseTotal = 0;
            
            $('.expense-amount').each(function() {
                expenseTotal += parseFloat($(this).val()) || 0;
            });

            var grandTotal = totalAll + expenseTotal;
            $('#grandTotal').val(grandTotal.toFixed(0));

            updateTotalPayment();
        }

        function updateTotalPayment() {
            var totalPayment = 0;
            $('.payment-amount').each(function() {
                totalPayment += parseFloat($(this).val()) || 0;
            });
            $('#totalPayment').val(totalPayment.toFixed(0));

            var grandTotal = parseFloat($('#grandTotal').val()) || 0;
            var changePayment = grandTotal - totalPayment;
            $('#change_payment').val(changePayment.toFixed(0));
        }

        $(document).on('click', '.remove-product', function() {
            $(this).closest('tr').remove();
            calculateAllTotals();
            calculateGrandTotal();
        });

        $('.payment-method').on('change', function() {
            var method = $(this).val();
            var row = $(this).closest('tr');
            var accountSelect = row.find('.payment-account');
            var amountInput = row.find('.payment-amount');

            if (method === 'bank_transfer') {
                accountSelect.prop('disabled', false);
                amountInput.prop('readonly', false);
            } else if (method === 'tempo') {
                accountSelect.prop('disabled', true);
                amountInput.val(0);
                amountInput.prop('readonly', true);
            } else {
                accountSelect.prop('disabled', true);
                amountInput.prop('readonly', false);
            }

            calculateGrandTotal();
        });

        
});

</script>
@endsection