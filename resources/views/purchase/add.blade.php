@extends('template.app')
@section('title', 'Tambah Pembelian')
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
<li class="breadcrumb-item active" aria-current="page">Tambah Pembelian</li>
@endsection

@section('content')
<div class="flex-grow-1">
    @include('components.toast-notification')
    <form id="purchaseForm" method="POST">
    @csrf
    <div class="card mb-4">
        <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="supplier" class="form-label">Supplier</label>
                        <select class="form-select form-select" id="supplier" name="supplier" required>
                            <option value="">Pilih Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="reference_no" class="form-label">No. Referensi</label>
                        <input type="text" class="form-control form-control" id="reference_no" name="reference_no" value="{{ $noreff }}" required readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="purchase_date" class="form-label">Tanggal Pembelian</label>
                        <input type="date" class="form-control form-control" id="purchase_date" name="purchase_date" required>
                    </div>
                    <div class="col-md-4">
                        <label for="store" class="form-label">Toko</label>
                        <select class="form-select form-select" id="store" name="store" required>
                            <option value="">Pilih Toko</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status Pembelian</label>
                        <select class="form-select form-select" id="status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="dipesan">Dipesan</option>
                            <option value="diterima">Diterima</option>
                        </select>
                    </div>
                </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
                <div class="mb-3">
                    <label for="productSearch" class="form-label">Cari Produk</label>
                    <x-select2 class="form-control select2" id="productSearch" name="product" placeholder="Cari produk..."></x-select2>
                </div>
            <div id="selectedProducts"></div>
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
                url: '{{ route("purchase.search") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        name: params.term,
                        store_id: $('#store').val()
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
            if ($('#selectedProducts table').length === 0) {
                var tableHtml = `
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
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end">Subtotal:</td>
                                <td><input type="number" class="form-control" id="subtotalAll" name="subtotal" readonly></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end">Diskon:</td>
                                <td><input type="number" class="form-control" id="discountAll" value="0" name="total_discount"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end">Total:</td>
                                <td><input type="number" class="form-control" id="totalAll" readonly name="total_amount"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                `;
                $('#selectedProducts').append(tableHtml);
            }
            var productHtml = `
                <tr>
                    <td>
                        <input type="text" class="form-control" name="products[${data.product_pricing_id}][name]" value="${data.product_name} ${data.variasi_1 || ''} ${data.variasi_2 || ''} ${data.variasi_3 || ''}" readonly>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" class="form-control qty" name="products[${data.product_pricing_id}][qty]" placeholder="QTY" style="width: 70px;">
                            <span class="input-group-text">${data.unit_name}</span>
                        </div>
                    </td>
                    <td style="width: 150px;">
                        <input type="number" class="form-control price" name="products[${data.product_pricing_id}][purchase_price]" placeholder="Harga Beli" value="${data.purchase_price}">
                    </td>
                    <td style="width: 150px;">
                        <input type="number" class="form-control discount" name="products[${data.product_pricing_id}][discount]" placeholder="Diskon" value="0" readonly>
                    </td>
                    <td>
                        <input type="number" class="form-control total" name="products[${data.product_pricing_id}][total]" placeholder="Total Harga" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger remove-product">X</button>
                    </td>
                </tr>
            `;
            $('#productTableBody').append(productHtml);
            
            $('#productTableBody').on('input', '.qty, .price, .discount', function() {
                var row = $(this).closest('tr');
                calculateTotal(row);
                calculateAllTotals();
            });

            $('#totalSection').show();
            
            function calculateTotal(row) {
                var qty = parseFloat(row.find('.qty').val()) || 0;
                var price = parseFloat(row.find('.price').val()) || 0;
                var discount = parseFloat(row.find('.discount').val()) || 0;
                
                var total = (qty * price) - discount;
                row.find('.total').val(total.toFixed());
            }
            $('#discountAll').on('input', function() {
                calculateAllTotals();
            });

            function calculateAllTotals() {
                var subtotalAll = 0;
                var discountAll = parseFloat($('#discountAll').val()) || 0;

                $('.total').each(function() {
                    subtotalAll += parseFloat($(this).val()) || 0;
                });

                $('#productTableBody tr').each(function() {
                    var qty = parseFloat($(this).find('.qty').val()) || 0;
                    var price = parseFloat($(this).find('.price').val()) || 0;
                });

                $('#subtotalAll').val(subtotalAll.toFixed());
                $('#totalAll').val((subtotalAll - discountAll).toFixed());
                updateTotalDisplay();
                resetPaymentAmount();
            }

            $(document).on('click', '.remove-product', function() {
                $(this).closest('tr').remove();
                if ($('#productTableBody tr').length === 0) {
                    $('#selectedProducts table').remove();
                    $('#totalSection').hide();
                }
                calculateAllTotals();
            });
        });
    });
  
    var subtotalAll = 0;
    var discountAll = parseFloat($('#discountAll').val()) || 0;

    $('.total').each(function() {
        subtotalAll += parseFloat($(this).val()) || 0;
    });

    $('#subtotalAll').val(subtotalAll.toFixed());
    $('#totalAll').val((subtotalAll - discountAll).toFixed());
    

    var otherCostIndex = 0;
    
    function addOtherCost() {
        var newRow = `
            <tr>
                <td>
                    <select class="form-select form-select" name="other_costs[${otherCostIndex}][category]" required>
                        <option value="">Pilih Kategori Biaya</option>
                        @foreach($expenseCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" class="form-control" name="other_costs[${otherCostIndex}][note]" placeholder="Catatan"></td>
                <td><input type="number" class="form-control other-cost-amount" name="other_costs[${otherCostIndex}][amount]" placeholder="Jumlah" required></td>
                <td><button type="button" class="btn btn-danger remove-other-cost">Hapus</button></td>
            </tr>
        `;
        $('#otherCostsBody').append(newRow);
        otherCostIndex++;
        calculateOtherCosts();
    }

    $(document).on('click', '.remove-other-cost', function() {
        $(this).closest('tr').remove();
        calculateOtherCosts();
    });

    $(document).on('input', '.other-cost-amount', function() {
        calculateOtherCosts();
    });

    function calculateOtherCosts() {
        var totalOtherCosts = 0;
        $('.other-cost-amount').each(function() {
            totalOtherCosts += parseFloat($(this).val()) || 0;
        });
        $('#totalOtherCosts').val(totalOtherCosts.toFixed());
        updateTotalDisplay();
        resetPaymentAmount();
    }

    function updateTotalDisplay() {
        var totalAll = parseFloat($('#totalAll').val()) || 0;
        var totalOtherCosts = parseFloat($('#totalOtherCosts').val()) || 0;
        var grandTotal = totalAll + totalOtherCosts;
        $('#totalOtherCostsDisplayAndTotalAll').text('Rp ' + grandTotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
    }
    
    function resetPaymentAmount() {
        var grandTotal = parseFloat($('#totalOtherCostsDisplayAndTotalAll').text().replace(/[^0-9.-]+/g,"")) || 0;
        $('.payment-amount').val(grandTotal.toFixed(2));
        updatePaymentDisplay();
    }

    $('#selectedProducts').after(`
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <h5 class="card-title mb-0 me-3">Biaya Lain-lain</h5>
                    <button type="button" class="btn btn-primary custom-btn-color" onclick="addOtherCost()"><i class="bx bx-plus"></i> Tambah Biaya</button>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <table class="table table-bordered" id="otherCostsTable">
                            <thead>
                                <tr>
                                    <th>Kategori Biaya</th>
                                    <th>Catatan</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="otherCostsBody">
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                    <td><input type="number" class="form-control" id="totalOtherCosts" readonly></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h2 id="totalOtherCostsDisplayAndTotalAll">Rp 0</h2>
                                <h6 class="card-title fst-italic">Total Pembelian Barang + Total Biaya</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
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
                                <tr>
                                    <td><input type="date" class="form-control payment-date" name="payment[date]" required></td>
                                    <td>
                                        <select class="form-select payment-method" name="payment[method]" required>
                                            <option value="">Pilih metode pembayaran</option>
                                            <option value="cash">Tunai</option>
                                            <option value="bank_transfer">Transfer Bank</option>
                                            <option value="tempo">Tempo</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select payment-account" name="payment[account]" required disabled>
                                            <option value="">Pilih Akun Bank</option>
                                            @foreach($banks as $bank)
                                                <option value="{{ $bank->id }}">{{ $bank->name }} - {{ $bank->account_number }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control payment-amount" name="payment[amount]" required></td>
                                    <td><input type="text" class="form-control payment-note" name="payment[note]"></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total Bayar:</strong></td>
                                    <td><input type="number" class="form-control" id="totalPayment" readonly></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total Pembelian + Biaya Lain:</strong></td>
                                    <td><input type="number" class="form-control" id="grandTotal" name="grandTotal" readonly></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Kembalian:</strong></td>
                                    <td><input type="number" class="form-control" id="change_payment" name="change_payment" readonly></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    `);

    $(document).ready(function() {
        updateTotalDisplay();
        updatePaymentDisplay();

        $('.payment-method').on('change', function() {
            if ($(this).val() === 'bank_transfer') {
                $('.payment-account').prop('disabled', false);
            } else {
                $('.payment-account').prop('disabled', true);
            }
            
            if ($(this).val() === 'tempo') {
                $('.payment-amount').val(0).prop('readonly', true);
            } else {
                $('.payment-amount').prop('readonly', false);
            }
            
            updatePaymentDisplay();
        });
    });

    $('#totalAll, #totalOtherCosts').on('change', function() {
        updateTotalDisplay();
        updatePaymentDisplay();
    });

    $(document).on('input', '.payment-amount', function() {
        updatePaymentDisplay();
    });

    function updatePaymentDisplay() {
        var totalPayment = parseFloat($('.payment-amount').val()) || 0;
        $('#totalPayment').val(totalPayment.toFixed(2));

        var grandTotal = parseFloat($('#totalOtherCostsDisplayAndTotalAll').text().replace(/[^0-9.-]+/g,"")) || 0;
        $('#grandTotal').val(grandTotal.toFixed(2));

        var kembalian = Math.max(totalPayment - grandTotal, 0);
        $('#change_payment').val(kembalian.toFixed(2));
    }
</script>
<script>
    $('#purchaseForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
    $.ajax({
        url: '{{ route("purchase.store") }}',
        type: 'POST',
        data: formData,
        success: function(response) {
            if(response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data pembelian berhasil disimpan',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = '{{ route("purchase.add") }}';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat menyimpan data pembelian'
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
</script>
@endsection