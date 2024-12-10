@extends('template.app')
@section('title', 'Pajak')
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
<li class="breadcrumb-item active" aria-current="page">Pajak</li>
@endsection

@section('content')
<div class="flex-grow-1">

    @include('components.toast-notification')

    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-pajak table border-top table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Nilai Diskon</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pajak as $p)
                    <tr>
                        <td></td>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->description }}</td>
                        <td>{{ number_format($p->discount_value, 2) }}%</td>
                        <td>
                            @if($p->status == 1)
                            <span class="badge bg-label-success">Aktif</span>
                            @else
                            <span class="badge bg-label-danger">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('pajak.edit', $p->id) }}" class="btn btn-icon btn-outline-primary edit-button btn-sm custom-btn-color">
                                <span class="bx bx-edit"></span>
                            </a>
                            <button class="btn btn-icon btn-outline-danger delete-button btn-sm" data-pajak-id="{{ $p->id }}">
                                <span class="bx bx-trash"></span>
                            </button>
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
        $('.datatables-pajak').DataTable({
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
                text: '<i class="bx bx-plus me-1"></i> Pajak',
                className: 'create-new btn btn-primary btn-sm custom-btn-color',
                action: function(e, dt, node, config) {
                    window.location.href = '{{ route("pajak.create") }}';
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

        $('div.head-label').html('<h5 class="card-title mb-0">Daftar Pajak</h5>');


        $(document).on('click', '.delete-button', function() {
            var pajakId = $(this).data('pajak-id');
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data akan terhapus dan tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#696cff',
                cancelButtonColor: '#8592a3',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: '/pajak/' + pajakId,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                'Data berhasil dihapus.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'Gagal menghapus data.',
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