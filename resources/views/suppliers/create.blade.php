@extends('template.app')
@section('title', 'Tambah Supplier')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .shadow-sm {
        box-shadow: none !important;
    }
</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('suppliers.index') }}">Supplier</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Tambah Supplier</li>
@endsection

@section('content')
<div class="flex-grow-1">
    <div class="row g-6">
        <div class="col-md-6 mb-3">
            <div class="card">
                <h5 class="card-header">Tambah Data Supplier</h5>
                <div class="card-body">
                    <form id="supplierForm" class="row" action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12 mb-3">
                            <x-input-label for="store_name" :value="__('Nama Toko')" />
                            <x-text-input id="store_name" class="form-control" type="text" name="store_name" :value="old('store_name')" required autofocus autocomplete="store_name" placeholder="Masukkan Nama Toko" />
                            <x-input-error :messages="$errors->get('store_name')" class="mt-2 text-danger" />
                        </div>
                        <div class="col-12 mb-3">
                            <x-input-label for="pic_name" :value="__('Nama PIC')" />
                            <x-text-input id="pic_name" class="form-control" type="text" name="pic_name" :value="old('pic_name')" required autocomplete="pic_name" placeholder="Masukkan Nama PIC" />
                            <x-input-error :messages="$errors->get('pic_name')" class="mt-2 text-danger" />
                        </div>
                        <div class="col-12 mb-3">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="email" placeholder="Masukkan Email" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                        </div>
                        <div class="col-12 mb-3">
                            <x-input-label for="phone" :value="__('No. Telpon')" />
                            <x-text-input id="phone" class="form-control" type="tel" name="phone" :value="old('phone')" required autocomplete="tel" placeholder="Enter supplier phone" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2 text-danger" />
                        </div>
                        <div class="col-12 mb-3">
                            <x-input-label for="address" :value="__('Alamat')" />
                            <textarea id="address" class="form-control" name="address" rows="3" placeholder="Masukkan Alamat">{{ old('address') }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2 text-danger" />
                        </div>
                        <div class="col-12 text-center demo-vertical-spacing">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1 custom-btn-color">Simpan</button>
                            <a href="{{ route('suppliers.index') }}" class="btn btn-label-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>

</div>
@endsection

@section('js')
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.form-select').select2({
            allowClear: true
        });
    });
</script>
@endsection