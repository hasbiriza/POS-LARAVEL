@extends('template.app')
@section('title', 'Laporan Laba Rugi')
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
    .table-dashed {
        border-collapse: separate;
        border-spacing: 0;
    }
    .table-dashed th,
    .table-dashed td {
        border: 1px dashed #dee2e6;
    }
</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Laporan Laba Rugi</li>
@endsection

@section('content')
<div class="flex-grow-1">
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
                        <label for="store" class="form-label">Nama Toko</label>
                        <select class="form-select" id="store" name="store">
                            <option value="">Semua Toko</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ request('store') == $store->id ? 'selected' : '' }}>{{ $store->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2 custom-btn-color">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card p-4">
        <div class="card-datatable table-responsive">
            <table class="table table-bordered">
                <thead style="background-color: #bae3ff;">
                    <tr>
                        <th>Keterangan</th>
                        <th colspan="2">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" style="background-color: #f0f0f0;"><strong>Pendapatan</strong></td>
                    </tr>
                    <tr>
                        <td>Penjualan Barang</td>
                        <td colspan="2">Rp {{ number_format($penjualan_barang['total_penjualan'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Return Penjualan</td>
                        <td colspan="2">(Rp {{ number_format($return_penjualan, 0, ',', '.') }})</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><strong>Total Pendapatan Bersih</strong></td>
                        <td colspan="2" style="text-align: right;"><strong>Rp {{ number_format($total_pendapatan_bersih, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="background-color: #f0f0f0;"><strong>Harga Pokok Penjualan (HPP)</strong></td>
                    </tr>
                    <tr>
                        <td>Pembelian</td>
                        <td colspan="2">Rp {{ number_format($pembelian, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Retur Pembelian</td>
                        <td colspan="2">(Rp {{ number_format($retur_pembelian, 0, ',', '.') }})</td>
                    </tr>
                    <tr>
                        <td>Stock Opname</td>
                        <td colspan="2">(Rp {{ number_format($stock_opname, 0, ',', '.') }})</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><strong>Total Harga Pokok Penjualan (HPP)</strong></td>
                        @php
                            $hpp = $pembelian - $retur_pembelian - ($stock_opname < 0 ? abs($stock_opname) : -$stock_opname);
                        @endphp
                        <td colspan="2" style="text-align: right;"><strong>Rp {{ number_format($hpp, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><strong>Laba Kotor</strong></td>
                        <td colspan="2" style="text-align: right;"><strong>Rp {{ number_format($total_pendapatan_bersih - $hpp, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="background-color: #f0f0f0;"><strong>Biaya Pengeluaran</strong></td>
                    </tr>
                    @php
                        $groupedExpenses = $expense_category->where('source_table', 'expense_items')->groupBy('category_name');
                        $totalExpense = 0;
                    @endphp
                    @foreach ($groupedExpenses as $categoryName => $expenses)
                    <tr>
                        <td>{{ $categoryName }}</td>
                        @php
                            $categoryTotal = $expenses->sum('total_amount');
                            $totalExpense += $categoryTotal;
                        @endphp
                        <td colspan="2">Rp {{ number_format($categoryTotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td><strong>Biaya pembelian</strong></td>
                        <td colspan="2"><strong>Rp {{ number_format($expense_category->where('source_table', 'purchase_expenses')->sum('total_amount'), 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Biaya Transfer Stok</strong></td>
                        <td colspan="2"><strong>Rp {{ number_format($expense_category->where('source_table', 'stock_transfer_expenses')->sum('total_amount'), 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><strong>Total Biaya Pengeluaran</strong></td>
                        @php
                            $totalExpense_all = $totalExpense + $expense_category->where('source_table', 'purchase_expenses')->sum('total_amount') + $expense_category->where('source_table', 'stock_transfer_expenses')->sum('total_amount');
                        @endphp
                        <td colspan="2" style="text-align: right;"><strong>Rp {{ number_format($totalExpense_all, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><strong>Laba (Rugi) Operasional</strong></td>
                        @php
                            $laba_rugi_operasional = $total_pendapatan_bersih - $hpp - $totalExpense_all;
                        @endphp
                        <td colspan="2" style="text-align: right;"><strong>Rp {{ number_format($laba_rugi_operasional, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td>Laba (Rugi) Bersih Sebelum Pajak</td>
                        <td colspan="2">Rp {{ number_format($laba_rugi_operasional, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Pajak Penghasilan</td>
                        <td colspan="2">Rp {{ number_format($penjualan_barang['total_tax'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><strong>Laba (Rugi) Bersih Setelah Pajak</strong></td>
                        <td colspan="2" style="text-align: right;"><strong>Rp {{ number_format($laba_rugi_operasional - $penjualan_barang['total_tax'], 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    $('#exportExcel').on('click', function() {
        var store_id = $('#store').val();
        window.location.href = "{{ route('labarugi-report.export-excel') }}?store_id=" + store_id;
    });
</script>
@endsection