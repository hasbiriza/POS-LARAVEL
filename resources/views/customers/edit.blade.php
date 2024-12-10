@extends('template.app')
@section('title', 'Edit Customer')
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
    <a href="{{ route('customers.index') }}">Customer</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Edit Customer</li>
@endsection

@section('content')
<div class="flex-grow-1">
    <div class="row g-6">
        <div class="col-md-6 mb-3">
            <div class="card">
                <h5 class="card-header">Edit Customer</h5>
                <div class="card-body">
                    <form id="customerForm" class="row" action="{{ route('customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-12 mb-3">
                            <x-input-label for="name" :value="__('Nama Customer')" />
                            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name', $customer->name)" required autofocus autocomplete="name" placeholder="Masukkan Nama Customer" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <x-input-label for="email" :value="__('Email Customer')" />
                            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email', $customer->email)" required autocomplete="email" placeholder="Masukkan Email Customer" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <x-input-label for="phone" :value="__('No. Telpon')" />
                            <x-text-input id="phone" class="form-control" type="text" name="phone" :value="old('phone', $customer->phone)" required autocomplete="phone" placeholder="Masukkan No. Telpon" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <x-input-label for="address" :value="__('Alamat')" />
                            <x-text-input id="address" class="form-control" type="text" name="address" :value="old('address', $customer->address)" autocomplete="address" placeholder="Masukkan Alamat" />
                            <x-input-error :messages="$errors->get('address')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 text-center demo-vertical-spacing">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1 custom-btn-color">Simpan</button>
                            <a href="{{ route('customers.index') }}" class="btn btn-label-secondary">Batal</a>
                        </div>
                    </form>
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