@extends('template.app')
@section('title', 'Edit Penjualan')

@section('css')
<style>
    .card {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }
    .card-title {
        color: #495057;
        font-weight: bold;
    }
    .detail-row {
        margin-bottom: 15px;
    }
    .detail-label {
        font-weight: bold;
        color: #6c757d;
    }
    .badge {
        font-size: 0.9em;
        padding: 5px 10px;
    }
    .table {
        margin-bottom: 0;
    }
    .table th {
        background-color: #f8f9fa;
    }
    .total-section {
        background-color: #e9ecef;
        padding: 15px;
        border-radius: 8px;
        margin-top: 20px;
    }
    .btn-print {
        margin-right: 10px;
    }
    .delete-item {
        color: red;
        cursor: pointer;
    }
</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('sales-list.index') }}">Daftar Penjualan</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit Penjualan</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('sales-list.update', $transaction->id) }}" method="POST" id="edit-form">
            @csrf
            @method('PUT')
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="row detail-row">
                        <div class="col-md-3 detail-label">ID Transaksi:</div>
                        <div class="col-md-3">{{ $transaction->transaction_id }}</div>
                        <div class="col-md-3 detail-label">Nama Pelanggan:</div>
                        <div class="col-md-3">{{ $customer->name }}</div>
                    </div>
                    <div class="row detail-row">
                        <div class="col-md-3 detail-label">Tanggal Transaksi:</div>
                        <div class="col-md-3">
                            <label>{{ $transaction->transaction_date }}</label>
                        </div>
                        <div class="col-md-3 detail-label">Nama Toko:</div>
                        <div class="col-md-3">{{ $store->store_name }}</div>
                    </div>
                    <div class="row detail-row">
                        <div class="col-md-3 detail-label">Metode Pembayaran:</div>
                        <div class="col-md-3">
                            <label>
                                @if($transaction->payment_method == 'cash')
                                    Cash
                                @elseif($transaction->payment_method == 'bank_transfer')
                                    Transfer
                                @elseif($transaction->payment_method == 'tempo')
                                    Tempo
                                @endif
                            </label>
                        </div>
                        <div class="col-md-3 detail-label">Kasir:</div>
                        <div class="col-md-3">{{ $kasir->name }}</div>
                    </div>
                    <div class="row detail-row">
                        <div class="col-md-3 detail-label">Status:</div>
                        <div class="col-md-3">
                            <label>
                                @if($transaction->status == 'lunas')
                                    Lunas
                                @elseif($transaction->status == 'tempo')
                                    Belum Lunas
                                @endif
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="product-select">Tambah Produk</label>
                        <x-select2 id="product-select" class="form-control select2">
                            <option value="">Pilih Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                        data-name="{{ $product->product_name }}"
                                        data-price="{{ $product->sale_price }}"
                                        data-stock="{{ $product->stock }}"
                                        data-variant1="{{ $product->variant1 }}"
                                        data-variant2="{{ $product->variant2 }}"
                                        data-variant3="{{ $product->variant3 }}"
                                        data-unit="{{ $product->unit_name }}">
                                    {{ $product->product_name }} 
                                    ({{ $product->variant1 }}
                                    {{ $product->variant2 ? ', '.$product->variant2 : '' }}
                                    {{ $product->variant3 ? ', '.$product->variant3 : '' }})
                                    - Rp {{ number_format($product->sale_price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </x-select2>
                    </div>
                </div>
            </div>

            <h6 class="mt-4 mb-3">Detail Item</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="items-table">
                    <thead>
                        <tr>
                            <th>Nama Item</th>
                            <th>Unit</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $index => $item)
                        <tr>
                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->sales_transaction_items_id }}">
                            <input type="hidden" name="items[{{ $index }}][product_pricing_id]" value="{{ $item->product_pricing_id }}">
                            <input type="hidden" name="items[{{ $index }}][price]" value="{{ $item->sale_price }}">
                            <td>
                                {{ $item->item_name }}
                                <br>
                                <small class="text-muted">
                                    {{ $item->variasi_1 }}
                                    {{ $item->variasi_2 ? ', ' . $item->variasi_2 : '' }}
                                    {{ $item->variasi_3 ? ', ' . $item->variasi_3 : '' }}
                                </small>
                            </td>
                            <td>{{ $item->unit_name }}</td>
                            <td><input type="number" name="items[{{ $index }}][quantity]" class="form-control item-quantity" value="{{ $item->quantity }}"></td>
                            <td>Rp <span class="item-price">{{ number_format($item->sale_price, 0, ',', '.') }}</span></td>
                            <td>Rp <span class="item-subtotal">{{ number_format($item->quantity * $item->sale_price, 0, ',', '.') }}</span></td>
                            <td><button type="button" class="btn btn-sm btn-danger delete-item" data-index="{{ $index }}" style="color: white;">X</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Diskon (%):</strong></td>
                            <td>
                                <input type="number" name="discount_amount" id="discount-amount" class="form-control" value="{{ $transaction->discount_amount }}" onchange="updateTotals()">
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Pajak (%):</strong></td>
                            <td>
                                <input type="number" name="tax_amount" id="tax-amount" class="form-control" value="{{ $transaction->tax_amount }}" onchange="updateTotals()">
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total:</strong></td>
                            <td><strong>Rp <span id="total-amount">{{ number_format($transaction->total_amount - $transaction->discount_amount + $transaction->tax_amount, 0, ',', '.') }}</span></strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <h6 class="mt-4 mb-3">Pembayaran</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal Pembayaran</th>
                            <th>Metode Pembayaran</th>
                            <th>Bank</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentMethods as $index => $paymentMethod)
                        <tr>
                            <input type="hidden" name="payment_methods[{{ $index }}][id]" value="{{ $paymentMethod->id }}">
                            <td><label>{{ $paymentMethod->payment_date }}</label></td>
                            <td><label>
                                @if($paymentMethod->payment_method == 'cash')
                                    Tunai
                                @elseif($paymentMethod->payment_method == 'bank_transfer')
                                    Transfer Bank
                                @elseif($paymentMethod->payment_method == 'tempo')
                                    Tempo
                                @endif
                            </label></td>
                            <td><label>{{ $paymentMethod->bank_name }} - {{ $paymentMethod->bank_account_number }}</label></td>
                            <td><input type="number" name="payment_methods[{{ $index }}][amount]" class="form-control payment-amount" value="{{ intval($paymentMethod->amount) }}"></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total Pembayaran:</strong></td>
                            <td><strong>Rp <span id="total-payment">{{ number_format($transaction->status == 'tempo' ? $paymentMethods->sum('amount') : $transaction->total_payment, 0, ',', '.') }}</span></strong></td>
                        </tr>
                        <tr id="remaining-payment-row" style="{{ $transaction->status == 'tempo' ? '' : 'display: none;' }}">
                            <td colspan="3" class="text-end"><strong>Sisa Pembayaran (Tempo):</strong></td>
                            <td><strong>Rp <span id="remaining-payment">{{ number_format($transaction->remaining_payment, 0, ',', '.') }}</span></strong></td>
                        </tr>
                        <tr id="change-row" style="{{ $transaction->status != 'tempo' ? '' : 'display: none;' }}">
                            <td colspan="3" class="text-end"><strong>Kembalian:</strong></td>
                            <td><strong>Rp <span id="change-amount">{{ number_format($transaction->change_payment, 0, ',', '.') }}</span></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="mt-4 d-flex justify-content-end">
                <button type="button" class="btn btn-secondary me-2" onclick="window.history.back();">Batal</button>
                <button type="submit" class="btn btn-primary custom-btn-color">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        let itemIndex = {{ count($items) }};
        let addedProducts = new Set();
        $('#items-table tbody tr').each(function() {
            let productId = $(this).find('input[name$="[product_pricing_id]"]').val();
            addedProducts.add(productId);
        });

        $('#product-select').on('change', function() {
            let selectedOption = $(this).find('option:selected');
            let productId = selectedOption.val();

            if (productId && !addedProducts.has(productId)) {
                let productName = selectedOption.data('name');
                let productPrice = selectedOption.data('price');
                let productStock = selectedOption.data('stock');
                let variant1 = selectedOption.data('variant1');
                let variant2 = selectedOption.data('variant2');
                let variant3 = selectedOption.data('variant3');
                let unitName = selectedOption.data('unit');

                let newRow = `
                    <tr>
                        <input type="hidden" name="items[${itemIndex}][id]" value="">
                        <input type="hidden" name="items[${itemIndex}][product_pricing_id]" value="${productId}">
                        <input type="hidden" name="items[${itemIndex}][price]" value="${productPrice}">
                        <td>
                            ${productName}
                            <br>
                            <small class="text-muted">
                                ${variant1}
                                ${variant2 ? ', ' + variant2 : ''}
                                ${variant3 ? ', ' + variant3 : ''}
                            </small>
                        </td>
                        <td>${unitName}</td>
                        <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control item-quantity" value="1" max="${productStock}"></td>
                        <td>Rp <span class="item-price">${numberWithCommas(productPrice)}</span></td>
                        <td>Rp <span class="item-subtotal">${numberWithCommas(productPrice)}</span></td>
                        <td><button type="button" class="btn btn-sm btn-danger delete-item" data-index="${itemIndex}" style="color: white;">X</button></td>
                    </tr>
                `;
                $('#items-table tbody').append(newRow);
                itemIndex++;
                updateTotals();
                addedProducts.add(productId);
            } else if (addedProducts.has(productId)) {
                alert('Produk ini sudah ada dalam tabel.');
            }
            $(this).val('').trigger('change.select2'); 
        });

        $(document).on('click', '.delete-item', function() {
            let row = $(this).closest('tr');
            let productId = row.find('input[name$="[product_pricing_id]"]').val();
            addedProducts.delete(productId);
            row.remove();
            updateTotals();
        });

        $(document).on('input', '.item-quantity, .payment-amount, #discount-amount, #tax-amount', function() {
            updateTotals();
        });

        function updateTotals() {
            let subtotal = 0;
            $('.item-quantity').each(function() {
                let quantity = parseInt($(this).val());
                let price = parseInt($(this).closest('tr').find('input[name$="[price]"]').val());
                let itemSubtotal = quantity * price;
                $(this).closest('tr').find('.item-subtotal').text(numberWithCommas(itemSubtotal));
                subtotal += itemSubtotal;
            });

            let discountAmount = parseFloat($('#discount-amount').val()) || 0;
            let taxAmount = parseFloat($('#tax-amount').val()) || 0;

            let total = subtotal - discountAmount + taxAmount;

            $('#subtotal-amount').text(numberWithCommas(subtotal));
            $('#discount-amount-display').text(numberWithCommas(discountAmount));
            $('#tax-amount-display').text(numberWithCommas(taxAmount));
            $('#total-amount').text(numberWithCommas(total));

            let totalPayment = 0;
            $('.payment-amount').each(function() {
                totalPayment += parseInt($(this).val()) || 0;
            });
            $('#total-payment').text(numberWithCommas(totalPayment));

            let remainingPayment = total - totalPayment;
            $('#remaining-payment').text(numberWithCommas(Math.max(0, remainingPayment)));

            if (remainingPayment > 0) {
                $('#remaining-payment-row').show();
                $('#change-row').hide();
            } else {
                $('#remaining-payment-row').hide();
                $('#change-row').show();
            }

            let change = totalPayment - total;
            $('#change-amount').text(numberWithCommas(Math.max(0, change)));
        }

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function updatePaymentMethodSelect() {
            let totalPayment = parseInt($('#total-payment').text().replace(/,/g, ''));
            let remainingPayment = parseInt($('#remaining-payment').text().replace(/,/g, ''));

            if (remainingPayment > 0) {
                $('#payment-method-select').val('tempo');
                $('#bank-select').show();
            } else {
                $('#payment-method-select').val('cash');
                $('#bank-select').hide();
            }
        }

        updatePaymentMethodSelect();

        $('#payment-method-select').on('change', function() {
            if ($(this).val() === 'tempo') {
                $('#bank-select').show();
            } else {
                $('#bank-select').hide();
            }
        });
        updateTotals();
    });
</script>
@endsection