@extends('template.app')
@section('title', 'Daftar Biaya Pengeluaran')
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
<li class="breadcrumb-item active" aria-current="page">Daftar Biaya Pengeluaran {{ $title }}</li>
@endsection

@section('content')
<div class="flex-grow-1">

    @include('components.toast-notification')

    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="store" class="form-label">Toko</label>
                        <select class="form-select" id="store" name="store">
                            <option value="">Semua Toko</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="lunas">Lunas</option>
                            <option value="belum_lunas">Belum Lunas</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date">
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
            <table class="datatables-expenses table border-top table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID Transaksi</th>
                        <th>Tanggal Transaksi</th>
                        <th>Nama Toko</th>
                        <th>Metode Pembayaran</th>
                        <th>Status</th>
                        <th>Total Biaya</th>
                        <th>Total Bayar</th>
                        <th>Sisa Bayar</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                        <tr>
                            <td></td>
                            <td>{{ $expense->transaction_id }}</td>
                            <td>{{ $expense->transaction_date }}</td>
                            <td>{{ $expense->store_name }}</td>
                            <td></td>
                            <td>
                                @if($expense->payment_status == 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-danger">Belum Lunas</span>
                                @endif
                            </td>
                            <td>Rp. {{ number_format($expense->total_expense_amount, 0, ',', '.') }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                @if($type == 'other')
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $expense->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $expense->id }}">
                                        <li><a class="dropdown-item" href="{{ route('biaya-pengeluaran-list.detail', ['type' => $type, 'id' => $expense->id]) }}">Detail</a></li>
                                        <li><a class="dropdown-item" href="{{ route('biaya-pengeluaran-list.edit', $expense->id) }}">Edit</a></li>
                                        <li><a class="dropdown-item btn-delete" href="{{ route('biaya-pengeluaran-list.delete', $expense->id) }}">Hapus</a></li>
                                        @if($expense->payment_status != 'lunas')
                                            <li><button class="dropdown-item btn-lunasi" data-expense-id="{{ $expense->id }}" data-remaining-amount="{{ $expense->remaining_amount }}">Lunasi</button></li>
                                        @endif
                                    </ul>
                                </div>
                                @else
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $expense->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $expense->id }}">
                                        <li><a class="dropdown-item" href="{{ route('biaya-pengeluaran-list.detail', ['type' => $type, 'id' => $expense->id]) }}">Detail</a></li>
                                    </ul>
                                </div>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Pelunasan -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Pelunasan Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    @csrf
                    <input type="hidden" id="expenseId" name="expense_id">
                    <div class="mb-3">
                        <label for="remainingAmount" class="form-label">Sisa Pembayaran</label>
                        <input type="text" class="form-control" id="remainingAmount" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="paymentMethod" class="form-label">Metode Pembayaran</label>
                        <select class="form-select" id="paymentMethod" name="payment_method" required>
                            <option value="">Pilih metode pembayaran</option>
                            <option value="cash">Tunai</option>
                            <option value="bank_transfer">Transfer Bank</option>
                        </select>
                    </div>
                    <div id="bankDetails" style="display: none;">
                        <div class="mb-3">
                            <label for="bankId" class="form-label">Bank</label>
                            <select class="form-select" id="bankId" name="bank_id">
                                <option value="">Pilih bank</option>
                                @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}">{{ $bank->name }} - {{ $bank->account_number }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="paymentAmount" class="form-label">Jumlah Pembayaran</label>
                        <input type="number" class="form-control" id="paymentAmount" name="payment_amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="paymentNote" class="form-label">Catatan Pembayaran</label>
                        <textarea class="form-control" id="paymentNote" name="payment_note"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="confirmPayment">Konfirmasi Pembayaran</button>
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
        $('.datatables-expenses').DataTable({
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
                    text: '<i class="bx bx-plus me-1"></i> Biaya Pengeluaran',
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

        $('div.head-label').html('<h5 class="card-title mb-0">Daftar Biaya Pengeluaran {{ $type }}</h5>');

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
                                    'Data biaya pengeluaran telah dihapus.',
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
    
    $('.btn-lunasi').on('click', function() {
            var expenseId = $(this).data('expense-id');
            var remainingAmount = $(this).data('remaining-amount');
            $('#expenseId').val(expenseId);
            $('#remainingAmount').val(remainingAmount);
            $('#paymentModal').modal('show');
        });

        $('#paymentMethod').on('change', function() {
            if ($(this).val() === 'bank_transfer') {
                $('#bankDetails').show();
                $('#bankId').prop('required', true);
            } else {
                $('#bankDetails').hide();
                $('#bankId').prop('required', false);
            }
        });

        $('#confirmPayment').click(function() {
            var form = $('#paymentForm');
            var formData = new FormData(form[0]);
            var expenseId = $('#expenseId').val();
            $.ajax({
                url: '{{ route('biaya-pengeluaran-list.confirm-payment', ['id' => ':id']) }}'.replace(':id', expenseId),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.success) {
                        $('#paymentModal').modal('hide');
                        Swal.fire('Sukses', 'Pembayaran berhasil dikonfirmasi', 'success').then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire('Error', 'Terjadi kesalahan saat mengkonfirmasi pembayaran', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan saat mengkonfirmasi pembayaran', 'error');
                }
            });
        });
</script>

@endsection