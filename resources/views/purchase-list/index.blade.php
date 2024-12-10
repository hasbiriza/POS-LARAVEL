@extends('template.app')
@section('title', 'Daftar Pembelian')
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
<li class="breadcrumb-item active" aria-current="page">Daftar Pembelian</li>
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
                        <label for="supplier" class="form-label">Supplier</label>
                        <select class="form-select" id="supplier" name="supplier">
                            <option value="">Semua Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="payment_method" class="form-label">Metode Pembayaran</label>
                        <select class="form-select" id="payment_method" name="payment_method">
                            <option value="">Semua Metode</option>
                            <option value="cash">Cash</option>
                            <option value="transfer">Transfer</option>
                            <option value="tempo">Tempo</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status Pembayaran</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="lunas">Lunas</option>
                            <option value="tempo">Tempo</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status Pembelian</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="lunas">Diterima</option>
                            <option value="tempo">Dipesan</option>
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
            <table class="datatables-purchases table border-top table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>No. Referensi</th>
                        <th>Tanggal Pembelian</th>
                        <th>Nama Supplier</th>
                        <th>Metode Pembayaran</th>
                        <th>Status Pembayaran</th>
                        <th>Status Pembelian</th>
                        <th>Total Pembelian</th>
                        <th>Sisa Pembayaran</th>
                        <th>Nama Toko</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseTransactions as $transaction)
                        <tr>
                            <td></td>
                            <td>{{ $transaction->reference_no }}</td>
                            <td>Tanggal Pembelian</td>
                            <td>{{ $transaction->supplier_name }}</td>
                            <!-- <td>{{ $transaction->payment_method }}</td> -->
                            <td>
                            @php
                            $paymentMethodBadges = [
                            'cash' => '<span class="badge bg-success" style="width: 100px;">Tunai</span>',
                            'bank_transfer' => '<span class="badge bg-primary" style="width: 100px;">Transfer</span>',
                            'tempo' => '<span class="badge bg-warning" style="width: 100px;">Tempo</span>'
                            ];
                            @endphp
                            {!! $paymentMethodBadges[$transaction->payment_method] ?? '<span class="badge bg-secondary" style="width: 100px;">Lainnya</span>' !!}
                        </td>
                            <!-- <td>
                                @if($transaction->payment_status == 'lunas')
                                    <span class="badge bg-success">Lunas
                                        @if($transaction->isreturn == 'yes')
                                            <i class="bx bx-undo text-default ms-1" title="Retur" style="background-color: red; padding: 2px; border-radius: 50%;"></i>
                                        @endif
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        {{ $transaction->payment_status }}
                                        @if($transaction->isreturn == 'yes')
                                            <i class="bx bx-undo text-danger ms-1" title="Retur"></i>
                                        @endif
                                    </span>
                                @endif
                            </td> -->

                            <td>
                                <!-- Badge group (payment_status dan retur) -->
                                <div class="d-inline-flex align-items-center">
                                    <!-- Badge payment_status -->
                                    @if($transaction->payment_status == 'lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-warning">{{ $transaction->payment_status }}</span>
                                    @endif

                                    <!-- Badge retur (terpisah dan sejajar) -->
                                    @if($transaction->isreturn == 'yes')
                                        <span class="badge bg-danger ms-2 d-flex align-items-center p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Retur">
                                            <i class="bx bx-undo" style="font-size: 1rem; padding: 3px;"></i> 
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>DIPESAN / DITERIMA ?</td>
                            <td>Rp. {{ number_format($transaction->total_purchase, 0, ',', '.') }}</td>
                            <td>Rp. {{ number_format($transaction->remaining_payment, 0, ',', '.') }}</td>
                            <td>{{ $transaction->store_name }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle custom-btn-color" type="button" id="dropdownMenuButton{{ $transaction->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $transaction->id }}">
                                        <li><a class="dropdown-item" href="{{ route('purchase-list.detail', $transaction->id) }}"> <i class="bx bx-detail text-primary"></i> Detail</a></li>
                                        <li><a class="dropdown-item" href="{{ route('purchase-list.edit', $transaction->id) }}"> <i class="bx bx-edit text-success"></i> Edit</a></li>
                                        <li><a class="dropdown-item btn-delete" href="{{ route('purchase-list.delete', $transaction->id) }}"> <i class="bx bx-trash text-danger"></i> Hapus</a></li>
                                        @if($transaction->payment_status == 'belum lunas')
                                            <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#paymentModal" data-purchase-id="{{ $transaction->id }}" data-remaining-payment="{{ $transaction->remaining_payment }}"> <i class="bx bx-money text-warning"></i> Lunasi</button></li>
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
                    <input type="hidden" id="purchaseId" name="purchase_id">
                    <div class="mb-3">
                        <label for="remainingPayment" class="form-label">Sisa Pembayaran</label>
                        <input type="text" class="form-control" id="remainingPayment" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="paymentAmount" class="form-label">Jumlah Pembayaran</label>
                        <input type="number" class="form-control" id="paymentAmount" name="payment_amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="paymentNote" class="form-label">Catatan Pembayaran</label>
                        <textarea class="form-control" id="paymentNote" name="payment_note"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="paymentMethod" class="form-label">Metode Pembayaran</label>
                        <select class="form-select" id="paymentMethod" name="payment_method" required>
                            <option value="cash">Tunai</option>
                            <option value="bank_transfer">Transfer Bank</option>
                        </select>
                    </div>
                    <div class="mb-3" id="bankAccountDiv" style="display: none;">
                        <label for="bankAccount" class="form-label">Pilih Bank</label>
                        <select class="form-select" id="bankAccount" name="bank_account">
                            <option value="" selected>Pilih Bank</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->name }} - {{ $bank->account_number }}</option>
                            @endforeach
                        </select>
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
        $('.datatables-purchases').DataTable({
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
                    text: '<i class="bx bx-plus me-1"></i> Pembelian',
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

        $('div.head-label').html('<h5 class="card-title mb-0">Daftar Pembelian</h5>');

        $('#paymentModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var purchaseId = button.data('purchase-id');
            var remainingPayment = button.data('remaining-payment');
            var modal = $(this);
            modal.find('#purchaseId').val(purchaseId);
            modal.find('#remainingPayment').val(remainingPayment.toLocaleString('id-ID'));
        });

        $('#paymentMethod').change(function() {
            if ($(this).val() === 'bank_transfer') {
                $('#bankAccountDiv').show();
            } else {
                $('#bankAccountDiv').hide();
            }
        });

        $('#confirmPayment').click(function() {
            var form = $('#paymentForm');
            var formData = new FormData(form[0]);
            
            $.ajax({
                url: '{{ route('purchase-list.confirm-payment-tempo') }}',
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


@endsection