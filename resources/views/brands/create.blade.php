@extends('template.app')
@section('title', 'Tambah Merek')
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
    <a href="{{ route('brands.index') }}">Merek</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Tambah Merek</li>
@endsection

@section('content')
<div class="flex-grow-1">
    <!-- <h4 class="py-3 mb-2">Daftar Merek</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-style1">
            <li class="breadcrumb-item">
                <a href="{{ route('brands.index') }}">Merek</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#" class="text-primary">Tambah Merek</a>
            </li>
        </ol>
    </nav> -->
    <div class="row g-6">
        <div class="col-md-6 mb-3">
            <div class="card">
                <h5 class="card-header">Tambah Merek</h5>
                <div class="card-body">
                    <form id="brandForm" class="row" action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12 mb-3">
                            <x-input-label for="name" :value="__('Nama Merek')" />
                            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter brand name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" class="form-control" name="description" placeholder="Enter brand description">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <x-input-label for="logo" :value="__('Logo')" />
                            <input id="logo" class="form-control" type="file" name="logo" accept="image/*" />
                            <x-input-error :messages="$errors->get('logo')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 text-center demo-vertical-spacing">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1 custom-btn-color">Simpan</button>
                            <a href="{{ route('brands.index') }}" class="btn btn-label-secondary">Batal</a>
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