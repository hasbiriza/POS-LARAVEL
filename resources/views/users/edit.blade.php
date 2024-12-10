@extends('template.app')
@section('title', 'Users edit')
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
    <a href="{{ route('users.index') }}">User</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Edit User</li>
@endsection

@section('content')
<div class="flex-grow-1">
    <div class="row g-6">
        <div class="col-md-6 mb-3">
            <div class="card">
                <h5 class="card-header">Edit User</h5>
                <div class="card-body">
                    <form id="userForm" class="row" action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <!-- Name -->
                        <div class="col-12 mb-3">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name', $user->name)" required autofocus autocomplete="name" placeholder="Enter username" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                        </div>

                        <!-- Email Address -->
                        <div class="col-12 mb-3">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email', $user->email)" required autocomplete="username" placeholder="Enter email" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                        </div>

                        <!-- Password -->
                        <div class="col-12 mb-3">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Enter password (leave blank to keep current)" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-12 mb-3">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" autocomplete="new-password" placeholder="Retype password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <label for="roles" class="form-label">Roles</label>
                            <select id="roles" class="js-example-basic-multiple" name="roles[]" multiple="multiple">
                                @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ $user->roles->contains($role->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 text-center demo-vertical-spacing">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1 custom-btn-color">Update</button>
                            <a href="{{ route('users.index') }}" class="btn btn-label-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Updating User Information</h5>
                    <p class="card-text">Here are some important points to consider when updating user data:</p>
                    <ul>
                        <li>Ensure the user's name is current and correctly spelled.</li>
                        <li>Verify that the email address is up-to-date and still active.</li>
                        <li>If changing the password, ensure it meets security requirements.</li>
                        <li>Review and adjust the user's roles if their responsibilities have changed.</li>
                        <li>Double-check all information before saving to maintain data accuracy.</li>
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