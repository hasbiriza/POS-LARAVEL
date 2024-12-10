@extends('template.app')
@section('title', 'Daftar Biaya Pengeluaran')
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
<li class="breadcrumb-item active" aria-current="page">Daftar Biaya Pengeluaran</li>
@endsection

@section('content')
<div class="flex-grow-1">

    @include('components.toast-notification')

    <!-- Dashboard Cards -->
    <!-- <div class="row mb-4">
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Biaya Pembelian</h5>
                    <h2 class="card-text">Rp. {{ number_format($purchase_expense, 0, ',', '.') }}</h2>
                    <a href="{{ route('biaya-pengeluaran-list.index') }}?type=purchase" class="btn btn-primary mt-3">Detail</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Biaya Transfer Stok</h5>
                    <h2 class="card-text">Rp. {{ number_format($stocktransfer_expense, 0, ',', '.') }}</h2>
                    <a href="{{ route('biaya-pengeluaran-list.index') }}?type=stocktransfer" class="btn btn-primary mt-3">Detail</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Biaya Lainnya</h5>
                    <h2 class="card-text">Rp. {{ number_format($other_expense, 0, ',', '.') }}</h2>
                    <a href="{{ route('biaya-pengeluaran-list.index') }}?type=other" class="btn btn-primary mt-3">Detail</a>
                </div>
            </div>
        </div>
    </div> -->

    <div class="row mb-4">
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card custom-card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <div class="icon mb-3" style="font-size: 40px; color: #007bff;">
                        <i class="bx bx-cart"></i>
                    </div>
                    <h5 class="card-title">Biaya Pembelian</h5>
                    <h2 class="card-text text-primary">Rp. {{ number_format($purchase_expense, 0, ',', '.') }}</h2>
                    <a href="{{ route('biaya-pengeluaran-list.index') }}?type=purchase" class="btn btn-outline-primary mt-3">Detail</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card custom-card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <div class="icon mb-3" style="font-size: 40px; color: #007bff;">
                        <i class="bx bx-transfer"></i>
                    </div>
                    <h5 class="card-title">Biaya Transfer Stok</h5>
                    <h2 class="card-text text-info">Rp. {{ number_format($stocktransfer_expense, 0, ',', '.') }}</h2>
                    <a href="{{ route('biaya-pengeluaran-list.index') }}?type=stocktransfer" class="btn btn-outline-info mt-3">Detail</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card custom-card shadow-sm h-100 border-0">
                <div class="card-body text-center">
                    <div class="icon mb-3" style="font-size: 40px; color: #007bff;">
                        <i class="bx bx-receipt"></i>
                    </div>
                    <h5 class="card-title">Biaya Lainnya</h5>
                    <h2 class="card-text text-warning">Rp. {{ number_format($other_expense, 0, ',', '.') }}</h2>
                    <a href="{{ route('biaya-pengeluaran-list.index') }}?type=other" class="btn btn-outline-warning mt-3">Detail</a>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection