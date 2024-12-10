@extends('template.app')
@section('title', 'Tambah Kategori Biaya')
@section('css')
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
    <a href="{{ route('biaya-kategori.index') }}">Kategori Biaya</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Tambah Kategori Biaya</li>
@endsection

@section('content')
<div class="flex-grow-1">
    <div class="row g-6">
        <div class="col-md-6 mb-3">
            <div class="card">
                <h5 class="card-header">Tambah Kategori Biaya</h5>
                <div class="card-body">
                    <form id="biayaKategoriForm" class="row" action="{{ route('biaya-kategori.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12 mb-3">
                            <x-input-label for="name" :value="__('Nama Kategori Biaya')" />
                            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Masukkan nama kategori biaya" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <x-input-label for="description" :value="__('Deskripsi Kategori Biaya')" />
                            <textarea id="description" class="form-control" name="description" rows="3" placeholder="Masukkan deskripsi kategori biaya">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 text-center demo-vertical-spacing">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1 custom-btn-color">Simpan</button>
                            <a href="{{ route('biaya-kategori.index') }}" class="btn btn-label-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
