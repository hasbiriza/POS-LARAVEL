@extends('template.app')
@section('title', 'Stock Opname')
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
<li class="breadcrumb-item active" aria-current="page">Stock Opname</li>
@endsection

@section('content')
<div class="flex-grow-1">
    @include('components.toast-notification')
    <form id="stockOpnameForm" method="POST" action="{{ route('stockopname.store') }}">
    @csrf
    <div class="card mb-4">
        <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="opname_date" class="form-label">Tanggal Stock Opname</label>
                        <input type="date" class="form-control form-control" id="opname_date" name="opname_date" required>
                    </div>
                    <div class="col-md-3">
                        <label for="reference_no" class="form-label">No. Referensi</label>
                        <input type="text" class="form-control form-control" id="reference_no" name="reference_no" value="{{ $noreff }}" required readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="store" class="form-label">Toko</label>
                        <select class="form-select form-select" id="store" name="store" required>
                            <option value="">Pilih Toko</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="user" class="form-label">Petugas</label>
                        <select class="form-select form-select" id="user" name="user" required>
                            <option value="">Pilih Petugas</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
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

        $('#store').on('change', function() {
            $('#productSearch').val(null).trigger('change');
            $('#selectedProducts').empty();
        });

        $('#productSearch').on('select2:select', function (e) {
            var data = e.params.data.data;
            if ($('#selectedProducts table').length === 0) {
                var tableHtml = `
                    <table class="table table-bordered product-item mb-3">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th style="width: 120px;">Stok Tersedia</th>
                                <th style="width: 120px;">Stok Fisik</th>
                                <th style="width: 120px;">Selisih</th>
                                <th style="width: 150px;">Harga Satuan</th>
                                <th>Penyebab Selisih</th>
                                <th>Penyesuaian Stock</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8">
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
                        <input type="number" class="form-control available-stock" name="products[${data.product_pricing_id}][available_stock]" value="${data.stock}" readonly>
                    </td>
                    <td>
                        <input type="number" class="form-control physical-stock" name="products[${data.product_pricing_id}][physical_stock]" placeholder="Stok Fisik">
                    </td>
                    <td>
                        <input type="number" class="form-control stock-difference" name="products[${data.product_pricing_id}][stock_difference]" readonly>
                    </td>
                    <td>
                        <input type="number" class="form-control unit-price" name="products[${data.product_pricing_id}][unit_price]" placeholder="Harga Satuan" value="${data.purchase_price}">
                    </td>
                    <td>
                        <select class="form-select form-select" id="reason" name="products[${data.product_pricing_id}][difference_cause]" required>
                            <option value="" disabled selected>Pilih Penyebab Selisih</option>
                            @foreach($reason as $reason)
                                <option value="{{ $reason->id }}">{{ $reason->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control stock-adjustment" name="products[${data.product_pricing_id}][stock_adjustment]" readonly>
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
            });

            $(document).on('input', '.physical-stock', function() {
                var row = $(this).closest('tr');
                var availableStock = parseFloat(row.find('.available-stock').val()) || 0;
                var physicalStock = parseFloat(row.find('.physical-stock').val()) || 0;
                
                var difference = physicalStock - availableStock;
                
                row.find('.stock-difference').val(difference);
                row.find('.stock-adjustment').val(physicalStock);
            });
        });

        $('#stockOpnameForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data stock opname berhasil disimpan',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route("stockopname.add") }}';
                        }
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan! Silakan coba lagi.',
                    });
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
@endsection