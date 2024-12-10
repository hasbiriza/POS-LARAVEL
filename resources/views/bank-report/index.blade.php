@extends('template.app')
@section('title', 'Laporan Bank')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">

<!-- Library Ekspor data Table Button -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">

</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Laporan Bank</li>
@endsection

@section('content')
<div class="flex-grow-1">
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="store" class="form-label">Nama Toko</label>
                        <select class="form-select" id="store" name="store">
                            <option value="">Semua Toko</option>
                            @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ request('store') == $store->id ? 'selected' : '' }}>{{ $store->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2 custom-btn-color">Filter</button>
                        <!-- <button type="button" id="exportExcel" class="btn btn-success">Export Excel</button> -->
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
                        <th>Tanggal</th>
                        <th>ID Transaksi</th>
                        <th>Jenis Transaksi</th>
                        <th>Metode Pembayaran</th>
                        <th>Nama Bank</th>
                        <th>Nomor Rekening</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                        <th>Saldo</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($bank_report as $report)
                    <tr>
                        <td></td>
                        <td>{{ date('Y-m-d', strtotime($report->payment_date)) }}</td>
                        <td>{{ $report->transaction_id }}</td>
                        <td>{{ $report->transaction_type }}</td>
                        <td>{{ $report->payment_method }}</td>
                        <td>{{ $report->bank_name ?? '-' }}</td>
                        <td>{{ $report->account_number ?? '-' }}</td>
                        <td>{{ $report->type == 'Debit' ? number_format($report->payment_amount, 0, ',', '.') : '' }}</td>
                        <td>{{ $report->type == 'Kredit' ? number_format($report->payment_amount, 0, ',', '.') : '' }}</td>
                        <td>
                            @php
                                $balance = $loop->index == 0 ? 0 : $balance;
                                $balance += $report->type == 'Kredit' ? $report->payment_amount : -$report->payment_amount;
                            @endphp
                            {{ number_format($balance, 0, ',', '.') }}
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
        var table = $('.datatables-purchases').DataTable({
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
            ],
            initComplete: function() {
                var buttonGroup = $('<div class="btn-group" role="group" aria-label="Button Group"></div>');
                $('.dt-action-buttons .btn').each(function() {
                    $(this).appendTo(buttonGroup);
                });
                $('.dt-action-buttons').html(buttonGroup);
            }
        });

        $('div.head-label').html('<h5 class="card-title mb-0">Laporan Bank</h5>');

        $('#exportExcel').on('click', function() {
            var store_id = $('#store').val();
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            window.location.href = "{{ route('bank-report.export-excel') }}?store_id=" + store_id + "&start_date=" + start_date + "&end_date=" + end_date;
        });

        table.buttons().container().appendTo($('.dt-action-buttons'));
    });
</script>
@endsection