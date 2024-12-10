@extends('template.app')
@section('title', 'Produk')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet">
<!-- Library Ekspor data Table Button -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">

<style type="text/css">
    .select2-container {
        z-index: 9999;
    }
</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Produk</li>
@endsection

@section('content')
<div class="flex-grow-1">


    @include('components.toast-notification')


    <!-- <h4 class="py-3 mb-2">Daftar Produk</h4> -->
    <!-- <nav aria-label="breadcrumb">
      <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
          <a href="{{ route('dashboard') }}">Beranda</a>
        </li>
        <li class="breadcrumb-item">
            <a href="#" class="text-primary">Produk</a>
        </li>
      </ol>
    </nav> -->
    <div class="card">

    <!-- <form action="/import" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Import</button>
    </form>

    <br>
    <a href="/download-template">
        <button>Download Product Template</button>
    </a> -->


        <div class="card-datatable table-responsive">
            <table class="datatables-product table border-top table-striped">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Memiliki Varian</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Satuan</th>
                        <th>Stok</th>
                        <th>Merek</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->has_varian == 'Y' ? 'Ya' : 'Tidak' }}</td>
                        <td>Rp. {{ $product->purchase_price }}</td>
                        <td>Rp. {{ $product->sale_price }}</td>
                        <td>{{ $product->unit_name }}</td>
                        <td>{{ $product->total_stock }}</td>
                        <td>{{ $product->brand_name }}</td>
                        <td>{{ $product->categories }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-primary btn-sm dropdown-toggle custom-btn-color" type="button" id="dropdownMenuButton{{ $product->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    Aksi
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $product->id }}">
                                    <li><a class="dropdown-item show-product" href="#" data-product-id="{{ $product->id }}"> <i class="bx bx-detail text-primary"></i> Detail</a></li>
                                    <li><a class="dropdown-item" href="{{ route('products.edit', $product->id) }}"><i class="bx bx-edit text-success"></i> Edit</a></li>
                                    <li><a class="dropdown-item delete-button" href="#" data-product-id="{{ $product->id }}"><i class="bx bx-trash text-danger"></i> Hapus</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 85vh; overflow-y: auto;">
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
<script src="{{ asset('assets/js/ui-toasts.js') }}"></script>

<!-- Library Ekspor data Table Button -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>

<script>
    $(document).ready(function() {
        $('.datatables-product').DataTable({
            scrollX: true,
            dom: '<"card-header"<"head-label text-center"><"dt-action-buttons text-end"B>><"d-flex justify-content-between align-items-center row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 10,
            lengthMenu: [10, 25, 50, 75, 100],
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="bx bxs-file-export"></i> Excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="bx bxs-file-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm'
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="bx bx-file"></i> CSV',
                    className: 'btn btn-warning btn-sm'
                },
                {
                    extend: 'print',
                    text: '<i class="bx bx-printer"></i> Print',
                    className: 'btn btn-info btn-sm'
                },
                {
                text: '<i class="bx bx-plus me-1"></i> Produk',
                className: 'create-new btn btn-primary btn-sm custom-btn-color',
                action: function(e, dt, node, config) {
                    window.location.href = '{{ route("products.create") }}';
                }
             }
        ],

        initComplete: function() {
                var buttonGroup = $('<div class="btn-group" role="group" aria-label="Button Group"></div>');
                $('.dt-action-buttons .btn').each(function() {
                    $(this).appendTo(buttonGroup);
                });
                $('.dt-action-buttons').html(buttonGroup);
            }
        });

        $('div.head-label').html('<h5 class="card-title mb-0">Daftar Produk</h5>');

        $('.show-product').on('click', function(e) {
            e.preventDefault();
            var productId = $(this).data('product-id');
            $.ajax({
                url: '{{ route("products.show", ":id") }}'.replace(':id', productId),
                type: 'GET',
                success: function(response) {
                    $('#productDetailModal .modal-body').html(response);
                    $('#productDetailModal').modal('show');
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'Terjadi kesalahan saat mengambil detail produk.',
                        'error'
                    );
                }
            });
        });

        $('.delete-button').on('click', function() {
            var productId = $(this).data('product-id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: '/products/' + productId + '/delete',
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire(
                                'Terhapus!',
                                'Produk telah dihapus.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'Terjadi kesalahan saat menghapus produk.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>

@endsection