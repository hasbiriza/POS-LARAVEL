@extends('template.app')
@section('title', 'Edit Bank')
@section('css')
<style>
    .shadow-sm {
        box-shadow: none !important;
    }
</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('bank.index') }}">Bank</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Edit Bank</li>
@endsection

@section('content')
<div class="flex-grow-1">
    <div class="row g-6">
        <div class="col-md-6 mb-3">
            <div class="card">
                <h5 class="card-header">Edit Bank</h5>
                <div class="card-body">
                    <form id="bankForm" class="row" action="{{ route('bank.update', $bank->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-12 mb-3">
                            <x-input-label for="name" :value="__('Nama Bank')" />
                            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name', $bank->name)" required autofocus autocomplete="name" placeholder="Masukkan nama bank" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <x-input-label for="branch" :value="__('Cabang')" />
                            <x-text-input id="branch" class="form-control" type="text" name="branch" :value="old('branch', $bank->branch)" required placeholder="Masukkan cabang bank" />
                            <x-input-error :messages="$errors->get('branch')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <x-input-label for="account_number" :value="__('Nomor Rekening')" />
                            <x-text-input id="account_number" class="form-control" type="text" name="account_number" :value="old('account_number', $bank->account_number)" required placeholder="Masukkan nomor rekening" />
                            <x-input-error :messages="$errors->get('account_number')" class="mt-2 text-danger" />
                        </div>
                        <div class="col-12 mb-3">
                            <x-input-label for="account_name" :value="__('Nama Pemilik')" />
                            <x-text-input id="account_name" class="form-control" type="text" name="account_name" :value="old('account_name', $bank->account_name)" required placeholder="Masukkan nama pemilik rekening" />
                            <x-input-error :messages="$errors->get('account_name')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <x-input-label for="swift_code" :value="__('Kode SWIFT')" />
                            <x-text-input id="swift_code" class="form-control" type="text" name="swift_code" :value="old('swift_code', $bank->swift_code)" placeholder="Masukkan kode SWIFT" />
                            <x-input-error :messages="$errors->get('swift_code')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <x-input-label for="credit" :value="__('Kredit')" />
                            <x-text-input id="credit" class="form-control" type="number" name="credit" :value="old('credit', $bank->credit)" required placeholder="Masukkan jumlah kredit" step="0.01" />
                            <x-input-error :messages="$errors->get('credit')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <x-input-label for="debit" :value="__('Debit')" />
                            <x-text-input id="debit" class="form-control" type="number" name="debit" :value="old('debit', $bank->debit)" required placeholder="Masukkan jumlah debit" step="0.01" />
                            <x-input-error :messages="$errors->get('debit')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <x-input-label for="saldo" :value="__('Saldo')" />
                            <x-text-input id="saldo" class="form-control" type="number" name="saldo" :value="old('saldo', $bank->saldo)" required placeholder="Masukkan saldo" step="0.01" />
                            <x-input-error :messages="$errors->get('saldo')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 mb-3">
                            <x-input-label for="logo" :value="__('Logo Bank')" />
                            @if($bank->logo)
                            <img src="{{ asset('storage/' . $bank->logo) }}" alt="Current Logo" class="mb-2" style="max-width: 100px;">
                            @endif
                            <input id="logo" class="form-control" type="file" name="logo" accept="image/*" />
                            <x-input-error :messages="$errors->get('logo')" class="mt-2 text-danger" />
                        </div>

                        <div class="col-12 text-center demo-vertical-spacing">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1 custom-btn-color">Update</button>
                            <a href="{{ route('bank.index') }}" class="btn btn-label-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
@endsection