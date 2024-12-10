@extends('template.app')
@section('title', 'Daftar Transfer Stok')
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
<li class="breadcrumb-item active" aria-current="page">Daftar Transfer Stok</li>
@endsection

@section('content')
<div class="flex-grow-1">

    @include('components.toast-notification')

    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="store" class="form-label">Toko Asal</label>
                        <select class="form-select" id="store" name="store">
                            <option value="">Semua Toko</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ request('store') == $store->id ? 'selected' : '' }}>{{ $store->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-stocktransfer table border-top table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>No. Transfer</th>
                        <th>Tanggal Transfer</th>
                        <th>Toko Asal</th>
                        <th>Toko Tujuan</th>
                        <th>Pembayaran</th>
                        <th>Status Bayar</th>
                        <th>Status</th>
                        <th>Penerima</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockTransfers as $transfer)
                    <tr>
                        <td></td>
                        <td>{{ $transfer->no_reff }}</td>
                        <td>{{ $transfer->tgl_transfer }}</td>
                        <td>{{ $transfer->from_store }}</td>
                        <td>{{ $transfer->to_store }}</td>
                        <td>{{ $transfer->payment_method }}</td>
                        <td>
                            @if($transfer->payment_status == 'lunas')
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-warning">Belum Lunas</span>
                            @endif
                        </td>
                        <td>
                            @if($transfer->status == 'diterima')
                                <span class="badge bg-success">{{ $transfer->status }}</span>
                            @elseif($transfer->status == 'dikirim')
                                <span class="badge bg-warning">{{ $transfer->status }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $transfer->status }}</span>
                            @endif
                        </td>
                        <td>{{ $transfer->penerima }}</td>
                        <td>{{ number_format($transfer->total_all, 0, ',', '.') }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-secondary dropdown-toggle custom-btn-color" type="button" id="dropdownMenuButton{{ $transfer->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    Aksi
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $transfer->id }}">
                                    <li><a class="dropdown-item" href="{{ route('stocktransfer-list.detail', $transfer->id) }}">Detail</a></li>
                                    <li><a class="dropdown-item" href="{{ route('stocktransfer-list.edit', $transfer->id) }}">Edit</a></li>
                                    @if($transfer->status == 'dikirim')
                                        <li><a class="dropdown-item btn-delete" href="{{ route('stocktransfer-list.delete', $transfer->id) }}">Hapus</a></li>
                                    @endif
                                    @if($transfer->payment_method == 'tempo')
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#lunasiModal{{ $transfer->id }}">Lunasi</a></li>
                                    @endif
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

@foreach($stockTransfers as $transfer)
    @if($transfer->payment_method == 'tempo')
        <div class="modal fade" id="lunasiModal{{ $transfer->id }}" tabindex="-1" aria-labelledby="lunasiModalLabel{{ $transfer->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="lunasiModalLabel{{ $transfer->id }}">Lunasi Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('stocktransfer-list.lunasi', $transfer->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="stock_transfer_id" value="{{ $transfer->id }}">
                            <div class="mb-3">
                                <label for="remaining_payment" class="form-label">Sisa Pembayaran</label>
                                <input type="number" class="form-control" id="remaining_payment" name="remaining_payment" value="{{ $transfer->remaining_payment }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="payment_amount" class="form-label">Jumlah Pembayaran</label>
                                <input type="number" class="form-control" id="payment_amount" name="payment_amount" required>
                            </div>
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="tunai">Tunai</option>
                                    <option value="bank_transfer">Transfer Bank</option>
                                </select>
                            </div>
                            <div class="mb-3" id="bank_account_div" style="display: none;">
                                <label for="bank_account" class="form-label">Akun Bank</label>
                                <select class="form-select" id="bank_account" name="bank_account">
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->name }} - {{ $bank->account_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="payment_note" class="form-label">Catatan Pembayaran</label>
                                <textarea class="form-control" id="payment_note" name="payment_note" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Lunasi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

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
    var table = $('.datatables-stocktransfer').DataTable({
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
                    text: '<i class="bx bx-plus me-1"></i> Transfer Stock',
                    className: 'btn btn-primary btn-sm custom-btn-color',
                    action: function(e, dt, node, config) {
                        window.location.href = '{{ route("biaya-kategori.create") }}';
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

    $('div.head-label').html('<h5 class="card-title mb-0">Daftar Transfer Stok</h5>');

});
</script>
<script>
    $(document).ready(function() {
        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            var deleteUrl = $(this).attr('href');
            
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
                        url: deleteUrl,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if(response.success) {
                                Swal.fire(
                                    'Terhapus!',
                                    'Data pembelian telah dihapus.',
                                    'success'
                                ).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat menghapus data.',
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menghapus data.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>
<script>
document.getElementById('payment_method').addEventListener('change', function() {
    var bankAccountDiv = document.getElementById('bank_account_div');
    if (this.value === 'bank_transfer') {
        bankAccountDiv.style.display = 'block';
    } else {
        bankAccountDiv.style.display = 'none';
    }
});
</script>
@endsection