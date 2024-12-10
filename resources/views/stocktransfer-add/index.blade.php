@extends('template.app')
@section('title', 'Tambah Pemindahan Stok')
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
<li class="breadcrumb-item active" aria-current="page">Tambah Pemindahan Stok</li>
@endsection

@section('content')
<div class="flex-grow-1">
    @include('components.toast-notification')
    <form id="stockTransferForm" method="POST">
    @csrf
    <div class="card mb-4">
        <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="transfer_date" class="form-label">Tanggal Pemindahan</label>
                        <input type="date" class="form-control form-control" id="transfer_date" name="transfer_date" required>
                    </div>
                    <div class="col-md-4">
                        <label for="reference_no" class="form-label">No. Referensi</label>
                        <input type="text" class="form-control form-control" id="reference_no" name="reference_no" value="{{ $noreff }}" required readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status Pemindahan</label>
                        <select class="form-select form-select" id="status" name="status" required>
                            <option value="">Pilih Status</option>
                            <option value="dikirim">Dikirim</option>
                            <option value="diterima">Diterima</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="from_store" class="form-label">Dari Toko</label>
                        <select class="form-select form-select" id="from_store" name="from_store" required>
                            <option value="">Pilih Toko Asal</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="to_store" class="form-label">Ke Toko</label>
                        <select class="form-select form-select" id="to_store" name="to_store" required>
                            <option value="">Pilih Toko Tujuan</option>
                            @foreach($store_to as $store)
                                <option value="{{ $store->id }}">{{ $store->store_name }}</option>
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
            if ($('#selectedProducts table').length === 0) {
                var tableHtml = `
                    <table class="table table-bordered product-item mb-3">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th style="width: 150px;">Stok Tersedia</th>
                                <th style="width: 200px;">QTY Transfer</th>
                                <th style="width: 200px;">Harga Beli</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                <td><input type="number" class="form-control" id="totalAll" readonly name="total_amount"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <textarea class="form-control" id="totalNote" name="total_note" placeholder="Catatan"></textarea>
                                </td>
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
                        <input type="hidden" name="products[${data.product_pricing_id}][id]" value="${data.product_pricing_id}">
                        <input type="hidden" name="products[${data.product_pricing_id}][barcode]" value="${data.barcode}">
                    </td>
                    <td>
                        <input type="number" class="form-control available-stock" value="${data.stock}" readonly>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="number" class="form-control qty" name="products[${data.product_pricing_id}][qty]" placeholder="QTY" style="width: 70px;" max="${data.stock}">
                            <span class="input-group-text">${data.unit_name}</span>
                        </div>
                    </td>
                    <td>
                        <input type="number" class="form-control price" name="products[${data.product_pricing_id}][price]" placeholder="Harga Beli" value="${data.purchase_price}">
                    </td>
                    <td>
                        <input type="number" class="form-control subtotal" name="products[${data.product_pricing_id}][subtotal]" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger remove-product">X</button>
                    </td>
                </tr>
            `;
            $('#productTableBody').append(productHtml);
            
            $(document).on('click', '.remove-product', function() {
                $(this).closest('tr').remove();
                if ($('#productTableBody tr').length === 0) {
                    $('#selectedProducts table').remove();
                }
                calculateTotal();
            });

            $(document).on('input', '.qty, .price', function() {
                var row = $(this).closest('tr');
                var qty = parseFloat(row.find('.qty').val()) || 0;
                var price = parseFloat(row.find('.price').val()) || 0;
                var availableStock = parseFloat(row.find('.available-stock').val()) || 0;
                
                if (qty > availableStock) {
                    alert('Jumlah transfer melebihi stok tersedia!');
                    row.find('.qty').val(availableStock);
                    qty = availableStock;
                }
                
                var subtotal = qty * price;
                row.find('.subtotal').val(subtotal.toFixed(2));
                calculateTotal();
            });

            function calculateTotal() {
                var total = 0;
                $('.subtotal').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });
                $('#totalAll').val(total.toFixed(2));
                updateGrandTotal();
            }
        });

        $('#from_store').on('change', function() {
            $('#productSearch').val(null).trigger('change');
            $('#selectedProducts').empty();
        });
    });

    $('#selectedProducts').after(`
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <h5 class="card-title mb-0 me-3">Biaya Lain-lain</h5>
                    <button type="button" class="btn btn-primary custom-btn-color" onclick="addOtherCost()"><i class="bx bx-plus"></i> Tambah Biaya</button>
                </div>
                <div class="row">
                    <div class="col-md-12">
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
                                    <td><input type="number" class="form-control" id="totalOtherCosts" name="total_other_costs" readonly></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
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
                                    <td><input type="text" class="form-control payment-note" name="payment[note]"></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total Bayar:</strong></td>
                                    <td><input type="number" class="form-control" id="totalPayment" name="payment[amount]"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong style="font-weight: 700;">Total Biaya Lain:</strong></td>
                                    <td><input type="number" class="form-control" id="grandTotal" readonly name="grand_total_all"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Kembalian:</strong></td>
                                    <td><input type="number" class="form-control" id="remainingTotal" name="remaining_payment" readonly></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    `);
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
                <td><button type="button" class="btn btn-danger remove-other-cost">X</button></td>
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
        $('#totalOtherCosts').val(totalOtherCosts.toFixed(2));
        updateGrandTotal();
    }

    function updateGrandTotal() {
        var totalOtherCosts = parseFloat($('#totalOtherCosts').val()) || 0;
        $('#grandTotal').val(totalOtherCosts.toFixed(2));
        updateRemainingTotal();
    }

    $('.payment-method').on('change', function() {
        var method = $(this).val();
        if (method === 'bank_transfer') {
            $('.payment-account').prop('disabled', false);
        } else {
            $('.payment-account').prop('disabled', true);
        }
        if (method === 'tempo') {
            $('#totalPayment').val(0).prop('readonly', true);
        } else {
            $('#totalPayment').prop('readonly', false);
        }
        updateRemainingTotal();
    });

    $('#totalPayment').on('input', function() {
        updateRemainingTotal();
    });

    function updateRemainingTotal() {
        var grandTotal = parseFloat($('#grandTotal').val()) || 0;
        var paymentMethod = $('.payment-method').val();
        var paymentAmount = 0;
        
        if (paymentMethod === 'tempo') {
            paymentAmount = 0;
            $('#totalPayment').val(0);
        } else {
            paymentAmount = parseFloat($('#totalPayment').val()) || 0;
        }
        
        var remainingTotal = paymentAmount - grandTotal;
        $('#remainingTotal').val(remainingTotal.toFixed(2));
    }

    $('#stockTransferForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: '{{ route("stock-transfer.store") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if(response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data pemindahan stok berhasil disimpan',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '{{ route("stock-transfer.index") }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat menyimpan data pemindahan stok'
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
<script>
$(document).ready(function() {
    var statusSelect = $('#status');
    var toStoreSelect = $('#to_store');
    var receiverSelect = $('#receiver');

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
                        $('#receiverGroup').show();
                    },
                    error: function(xhr) {
                        console.error('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            }
        } else {
            $('#receiverGroup').hide();
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