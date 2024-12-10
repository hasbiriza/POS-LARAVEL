@extends('template.app')
@section('title', 'Users add')
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
    <a href="{{ route('users.index') }}">Users</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Edit Users</li>
@endsection

@section('content')
<div class="flex-grow-1">
    <div class="row g-6">
        <div class="col-md-6 mb-3">
            <div class="card">
                <h5 class="card-header">Add New User</h5>
                <div class="card-body">
                    <form id="userForm" class="row" action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <!-- Name -->
                        <div class="col-12 mb-3">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter username" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                        </div>

                        <!-- Email Address -->
                        <div class="col-12 mb-3">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Enter email" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                        </div>

                        <!-- Password -->
                        <div class="col-12 mb-3">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" placeholder="Enter password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-12 mb-3">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Retype password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <label for="roles" class="form-label">Roles</label>
                            <select id="roles" class="js-example-basic-multiple" name="roles[]" multiple="multiple">
                                @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 text-center demo-vertical-spacing">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1 custom-btn-color">Submit</button>
                            <a href="{{ route('users.index') }}" class="btn btn-label-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">User Information</h5>
                    <p class="card-text">Here are some important points about user management:</p>
                    <ul>
                        <li>Users are individuals who can access and use the system.</li>
                        <li>Each user should have a unique email address.</li>
                        <li>Passwords should be strong and kept confidential.</li>
                        <li>Roles determine what actions a user can perform in the system.</li>
                        <li>Regular review and updates of user accounts are recommended for security.</li>
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
        $('.js-example-basic-multiple').select2({
            placeholder: "- Choose Roles -",
            allowClear: true
        });
    });
</script>
@endsection