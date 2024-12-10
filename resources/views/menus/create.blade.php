@extends('template.app')
@section('title', 'Menu add')
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
    <a href="{{ route('menus.index') }}">Menus</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Create Menu</li>
@endsection

@section('content')
<div class="flex-grow-1">
    <div class="row g-6">
        <div class="col-md-6 mb-3">
            <div class="card">
                <h5 class="card-header">Add New Menu</h5>
                <div class="card-body">
                    <form id="menuForm" class="row" action="{{ route('menus.store') }}" method="POST">
                        @csrf
                        <!-- Name -->
                        <div class="col-12 mb-3">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter menu name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                        </div>

                        <!-- URL -->
                        <div class="col-12 mb-3">
                            <x-input-label for="url" :value="__('URL')" />
                            <x-text-input id="url" class="form-control" type="text" name="url" :value="old('url')" autocomplete="url" placeholder="Enter menu URL" />
                            <x-input-error :messages="$errors->get('url')" class="mt-2 text-danger" />
                        </div>

                        <!-- Icon -->
                        <div class="col-12 mb-3">
                            <x-input-label for="icon" :value="__('Icon')" />
                            <x-text-input id="icon" class="form-control" type="text" name="icon" :value="old('icon')" placeholder="Enter menu icon" />
                            <x-input-error :messages="$errors->get('icon')" class="mt-2 text-danger" />
                        </div>

                        <!-- Parent Menu -->
                        <div class="col-12 mb-3">
                            <x-input-label for="parent_id" :value="__('Parent Menu')" />
                            <select id="parent_id" class="form-select" name="parent_id">
                                <option value="">No Parent</option>
                                @foreach ($menus as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('parent_id')" class="mt-2 text-danger" />
                        </div>
                        <div class="col-12 text-center demo-vertical-spacing">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1 custom-btn-color">Submit</button>
                            <a href="{{ route('menus.index') }}" class="btn btn-label-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Menu Information</h5>
                    <p class="card-text">Here are some important points about menu management:</p>
                    <ul>
                        <li>Menus are used to organize and structure the navigation of your application.</li>
                        <li>Each menu should have a unique name and URL.</li>
                        <li>Icons can be used to visually represent menu items.</li>
                        <li>Parent menus allow for the creation of hierarchical menu structures.</li>
                    </ul>
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