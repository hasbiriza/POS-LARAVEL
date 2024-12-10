@extends('template.app')
@section('title', 'Edit Kategori')
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
    <a href="{{ route('categories.index') }}">Kategori</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Edit Kategori</li>
@endsection

@section('content')
<div class="flex-grow-1">
    <div class="row g-6">
        <div class="col-md-6 mb-3">
            <div class="card">
                <h5 class="card-header">Edit Kategori</h5>
                <div class="card-body">
                    <form id="categoryForm" class="row" action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-12 mb-3">
                            <x-input-label for="name" :value="__('Nama Kategori')" />
                            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name', $category->name)" required autofocus autocomplete="name" placeholder="Masukkan nama kategori" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <x-input-label for="parent_id" :value="__('Parent Kategori')" />
                            <select id="parent_id" class="form-select" name="parent_id">
                                <option value="">No Parent</option>
                                @foreach($parentCategories as $parentCategory)
                                <option value="{{ $parentCategory->id }}" {{ $category->parent_id == $parentCategory->id ? 'selected' : '' }}>
                                    {{ $parentCategory->name }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('parent_id')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 text-center demo-vertical-spacing">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">Simpan</button>
                            <a href="{{ route('categories.index') }}" class="btn btn-label-secondary">Batal</a>
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