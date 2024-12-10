@extends('template.app')
@section('title', 'Daftar Retur Penjualan')
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
<li class="breadcrumb-item active" aria-current="page">Daftar Retur Penjualan</li>
@endsection

@section('content')
<div class="flex-grow-1">

    @include('components.toast-notification')

    <div class="card mb-4">
        <div class="card-body">
            <!-- <div class="row mb-3">
                <div class="col-12">
                    <a href="{{ route('sales-return.create') }}" class="btn btn-danger"><i class="bx bx-plus"></i> Buat Retur</a>
                </div>
            </div> -->
            <form id="filterForm" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="return_no" class="form-label">No. Nota Retur</label>
                        <input type="text" class="form-control" id="return_no" name="return_no">
                    </div>
                    <div class="col-md-3">
                        <label for="return_date" class="form-label">Tanggal Retur</label>
                        <input type="date" class="form-control" id="return_date" name="return_date">
                    </div>
                    <div class="col-md-3">
                        <label for="invoice_no" class="form-label">No. Invoice Penjualan</label>
                        <input type="text" class="form-control" id="invoice_no" name="invoice_no">
                    </div>
                    <div class="col-md-3">
                        <label for="store" class="form-label">Nama Toko</label>
                        <select class="form-select" id="store" name="store">
                            <option value="">Semua Toko</option>
                            @foreach($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="customer" class="form-label">Nama Pembeli</label>
                        <select class="form-select" id="customer" name="customer">
                            <option value="">Semua Pembeli</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary custom-btn-color">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-sales table border-top table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>No. Nota Retur</th>
                        <th>Tanggal Retur</th>
                        <th>No. Invoice Penjualan</th>
                        <th>Nama Toko</th>
                        <th>Nama Pembeli</th>
                        <th>Total Retur</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesReturns as $return)
                    <tr>
                        <td></td>
                        <td>{{ $return->return_no }}</td>
                        <td>{{ $return->return_date }}</td>
                        <td>{{ $return->invoice_no }}</td>
                        <td>{{ $return->store_name }}</td>
                        <td>{{ $return->customer_name }}</td>
                        <td>{{ number_format($return->total_return - $return->discount_return + $return->tax_return, 0, ',', '.') }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-secondary dropdown-toggle custom-btn-color" type="button" id="dropdownMenuButton{{ $return->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    Aksi
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $return->id }}">
                                    <li><a class="dropdown-item" href="{{ route('sales-return.detail', $return->id) }}">Detail</a></li>
                                    <li><a class="dropdown-item" href="{{ route('sales-return.edit', $return->id) }}">Edit</a></li>
                                    <li><a class="dropdown-item delete-return" href="#" data-id="{{ $return->id }}">Hapus</a></li>
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
        $('.datatables-sales').DataTable({
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function(row) {
                            var data = row.data();
                            return 'Detail ' + data[1];
                        }
                    }),
                    type: 'column',
                    renderer: function(api, rowIdx, columns) {
                        var data = $.map(columns, function(col, i) {
                            return col.title !== '' ?
                                '<tr data-dt-row="' +
                                col.rowIndex +
                                '" data-dt-column="' +
                                col.columnIndex +
                                '">' +
                                '<td>' +
                                col.title +
                                ':' +
                                '</td> ' +
                                '<td>' +
                                col.data +
                                '</td>' +
                                '</tr>' :
                                '';
                        }).join('');

                        return data ? $('<table class="table"/><tbody />').append(data) : false;
                    }
                }
            },
            columnDefs: [{
                className: 'control',
                orderable: false,
                targets: 0
            }],
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
                    text: '<i class="bx bx-plus me-1"></i> Retur Penjualan',
                    className: 'btn btn-primary btn-sm custom-btn-color',
                    action: function(e, dt, node, config) {
                        window.location.href = '{{ route("sales-return.create") }}';
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

        $('div.head-label').html('<h5 class="card-title mb-0">Daftar Retur Penjualan</h5>');

        $('.delete-return').on('click', function(e) {
            e.preventDefault();
            var returnId = $(this).data('id');

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
                        url: "{{ route('sales-return.delete', '') }}/" + returnId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Terhapus!',
                                'Retur penjualan telah dihapus.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'Terjadi kesalahan saat menghapus retur penjualan.',
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