@extends('template.app')
@section('title', 'Tambah Biaya')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet">
<style type="text/css">
    .select2-container {
        z-index: 9999;
    }
</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Tambah Biaya</li>
@endsection

@section('content')
<div class="flex-grow-1">
    @include('components.toast-notification')
    <form id="expenseForm" method="POST">
    @csrf
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Tambah Biaya Pengeluaran</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="store" class="form-label">Toko</label>
                    <select class="form-select" id="store" name="store" required>
                        <option value="">Pilih Toko</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="reference_no" class="form-label">No. Referensi</label>
                    <input type="text" class="form-control" id="reference_no" value="{{ $noreff }}" name="reference_no" required>
                </div>
                <div class="col-md-4">
                    <label for="expense_date" class="form-label">Tanggal Biaya</label>
                    <input type="date" class="form-control" id="expense_date" name="expense_date" required>
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-md-12">
                    <label for="note" class="form-label">Catatan</label>
                    <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Detail Biaya</h5>
            <div class="table-responsive">
                <table class="table table-bordered" id="expenseTable">
                    <thead>
                        <tr>
                            <th>Kategori Biaya</th>
                            <th>User</th>
                            <th style="display: none;">Customer</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Expense rows will be added here dynamically -->
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-primary mt-3 custom-btn-color" id="addExpenseBtn">Tambah Biaya</button>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Pembayaran</h5>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered" id="paymentTable">
                        <thead>
                            <tr>
                                <th>Tanggal Bayar</th>
                                <th>Metode Pembayaran</th>
                                <th>Akun Bank</th>
                                <th>Jumlah Bayar</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="date" class="form-control payment-date" name="payment[date]" required></td>
                                <td>
                                    <select class="form-select payment-method" name="payment[method]" required>
                                        <option value="">Pilih metode pembayaran</option>
                                        <option value="cash">Tunai</option>
                                        <option value="bank_transfer">Transfer Bank</option>
                                        <option value="tempo">Tempo</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select payment-account" name="payment[account]" required disabled>
                                        <option value="">Pilih Akun Bank</option>
                                        @foreach($bank as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }} - {{ $bank->account_number }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" class="form-control payment-amount" name="payment[amount]" required></td>
                                <td><input type="text" class="form-control payment-note" name="payment[note]"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <input type="number" class="form-control" id="total_amount" name="total_amount" readonly style="display: none;">
            <button type="submit" class="btn btn-primary float-end mt-3 custom-btn-color">Simpan</button>
        </div>
    </div>
    </form>
</div>
@endsection

@section('js')
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
<script src="{{ asset('assets/js/ui-toasts.js') }}"></script>

<script>
$(document).ready(function() {
    let expenseCount = 0;
    $('.payment-method').on('change', function() {
        if ($(this).val() === 'bank_transfer') {
            $('.payment-account').prop('disabled', false);
        } else {
            $('.payment-account').prop('disabled', true);
            $('.payment-account').val(''); 
        }

        if ($(this).val() === 'tempo') {
            $('.payment-amount').val(0);
            $('.payment-amount').prop('readonly', true);
        } else {
            $('.payment-amount').prop('readonly', false);
        }
    });

    $('#addExpenseBtn').on('click', function() {
        expenseCount++;
        const newExpenseRow = `
            <tr id="expenseRow${expenseCount}">
                <td>
                    <select class="form-select expense-category" name="expenses[${expenseCount}][category]" required>
                        <option value="">Pilih Kategori Biaya</option>
                        @foreach($expenseCategories as $expenseCategory)
                            <option value="{{ $expenseCategory->id }}">{{ $expenseCategory->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-select expense-user" name="expenses[${expenseCount}][user]" required>
                        <option value="">Pilih User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td style="display: none;">
                    <select class="form-select expense-customer" name="expenses[${expenseCount}][customer]">
                        <option value="">Pilih Customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control expense-amount" name="expenses[${expenseCount}][amount]" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm delete-expense" data-expense-id="${expenseCount}">Hapus</button>
                </td>
            </tr>
        `;
        $('#expenseTable tbody').append(newExpenseRow);
    });

    $(document).on('click', '.delete-expense', function() {
        const expenseId = $(this).data('expense-id');
        $(`#expenseRow${expenseId}`).remove();
        updateTotalAmount();
    });

    $(document).on('input', '.expense-amount', function() {
        updateTotalAmount();
    });

    function updateTotalAmount() {
        let total = 0;
        $('.expense-amount').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('.payment-amount').val(total.toFixed(2));
        $('#total_amount').val(total.toFixed(2));
    }

    $('#expenseForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: '{{ route("biaya-pengeluaran.store") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if(response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data biaya berhasil disimpan',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '{{ route("biaya-pengeluaran.index") }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat menyimpan data biaya'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan: ' + xhr.responseText
                });
            }
        });
    });
});
</script>
@endsection