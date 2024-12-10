@extends('template.app')
@section('title', 'Setting Layout')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Layout</li>
@endsection

@section('content')
<div class="flex-grow-1">
   
    <div class="row g-12">
        <div class="col-md-12 mb-3">
            <div class="card">
                <h5 class="card-header">Change Settings</h5>
                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-12 mb-3">
                            <label for="name" class="form-label">Application Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $setting->name }}" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" class="form-control" id="logo" name="logo">
                            @if ($setting->logo)
                            <img src="{{ asset('images/' . $setting->logo) }}" alt="Logo" width="100" class="mt-2">
                            @endif
                        </div>
                        <div class="col-12 mb-3">
                            <label for="theme" class="form-label">Pilih Layout</label>
                            <select name="layout" id="layout" class="form-select">
                                <option value="" disabled> Pilih Layout</option>
                                @foreach ($layout as $item)
                                <option value="{{ $item->layout_id }}" {{ Session::get('layout_id') == $item->layout_id ? 'selected' : '' }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 text-center demo-vertical-spacing">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1 custom-btn-color">Update Settings</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-label-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class ="row">
    <div class="col-md-12 mb-3">
        <div class="card">
            <h5 class="card-header">Layout Details</h5>
            <div class="card-body">
                @foreach($layout as $item)
                    <div class="layout-item mb-4 p-3 border rounded">
                        <h6 class="mb-3 text-primary">{{ $item->name }}</h6>
                        <div class="row g-3">
                            <div class="col-md-3 col-sm-6">
                                <div class="color-box p-2 rounded">
                                    <strong>App Brand Color</strong>
                                    <div class="d-flex align-items-center mt-2">
                                        <div class="color-preview me-2" style="width: 30px; height: 30px; background-color: {{ $item->app_brand_color }}; border-radius: 4px;"></div>
                                        <span>{{ $item->app_brand_color }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="color-box p-2 rounded">
                                    <strong>Sidebar Color</strong>
                                    <div class="d-flex align-items-center mt-2">
                                        <div class="color-preview me-2" style="width: 30px; height: 30px; background-color: {{ $item->sidebar_color }}; border-radius: 4px;"></div>
                                        <span>{{ $item->sidebar_color }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="color-box p-2 rounded">
                                    <strong>Navbar Color</strong>
                                    <div class="d-flex align-items-center mt-2">
                                        <div class="color-preview me-2" style="width: 30px; height: 30px; background-color: {{ $item->navbar_color }}; border-radius: 4px;"></div>
                                        <span>{{ $item->navbar_color }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="color-box p-2 rounded">
                                    <strong>Menu Link Color</strong>
                                    <div class="d-flex align-items-center mt-2">
                                        <div class="color-preview me-2" style="width: 30px; height: 30px; background-color: {{ $item->menu_link_color }}; border-radius: 4px;"></div>
                                        <span>{{ $item->menu_link_color }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="color-box p-2 rounded">
                                    <strong>Menu Link Hover</strong>
                                    <div class="d-flex align-items-center mt-2">
                                        <div class="color-preview me-2" style="width: 30px; height: 30px; background-color: {{ $item->menu_link_hover_color }}; border-radius: 4px;"></div>
                                        <span>{{ $item->menu_link_hover_color }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="color-box p-2 rounded">
                                    <strong>Button Color</strong>
                                    <div class="d-flex align-items-center mt-2">
                                        <div class="color-preview me-2" style="width: 30px; height: 30px; background-color: {{ $item->button_color }}; border-radius: 4px;"></div>
                                        <span>{{ $item->button_color }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="color-box p-2 rounded">
                                    <strong>Button Hover</strong>
                                    <div class="d-flex align-items-center mt-2">
                                        <div class="color-preview me-2" style="width: 30px; height: 30px; background-color: {{ $item->button_hover_color }}; border-radius: 4px;"></div>
                                        <span>{{ $item->button_hover_color }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="font-box p-2 rounded">
                                    <strong>Fonts</strong>
                                    <div class="mt-2">{{ $item->fonts }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection