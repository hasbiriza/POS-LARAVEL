@extends('template.app')
@section('title', 'Tambah Pajak')
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
    <a href="{{ route('pajak.index') }}">Pajak</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Tambah Pajak</li>
@endsection

@section('content')
<div class="flex-grow-1">
    <div class="row g-6">
        <div class="col-md-6 mb-3">
            <div class="card">
                <h5 class="card-header">Tambah Pajak</h5>
                <div class="card-body">
                    <form id="pajakForm" class="row" action="{{ route('pajak.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12 mb-3">
                            <x-input-label for="name" :value="__('Nama Pajak')" />
                            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Masukkan nama pajak" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <x-input-label for="description" :value="__('Deskripsi Pajak')" />
                            <textarea id="description" class="form-control" name="description" rows="3" placeholder="Masukkan deskripsi pajak">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <x-input-label for="discount_value" :value="__('Nilai Pajak (%)')" />
                            <div class="input-group">
                                <x-text-input id="discount_value" class="form-control" type="number" name="discount_value" :value="old('discount_value')" required placeholder="Masukkan nilai pajak" min="0" max="100" step="0.01" />
                                <span class="input-group-text">%</span>
                            </div>
                            <x-input-error :messages="$errors->get('discount_value')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" class="form-select" name="status" required>
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 text-center demo-vertical-spacing">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">Simpan</button>
                            <a href="{{ route('pajak.index') }}" class="btn btn-label-secondary">Batal</a>
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