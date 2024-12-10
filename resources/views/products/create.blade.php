@extends('template.app')
@section('title', 'Tambah Produk')
@section('css')
<!-- <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet"> -->
<style>
    .shadow-sm {
        box-shadow: none !important;
    }
</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('products.index') }}">Produk</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Tambah Produk</li>
@endsection

@section('content')
<div class="flex-grow-1">

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Tambah Produk Baru</h5>
                </div>
                <div class="card-body">
                    <form id="productForm" class="row g-3" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12 mt-4">
                            <h5 class="mb-3">
                                <i class="bx bx-info-circle me-2"></i>Informasi Produk
                            </h5>
                            <div class="product-item p-3 border rounded">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nama Produk</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bx bx-package"></i></span>
                                                <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Masukkan nama produk" />
                                            </div>
                                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Deskripsi Produk</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bx bx-detail"></i></span>
                                                <textarea id="description" class="form-control" name="description" rows="8" placeholder="Masukkan deskripsi produk secara detail">{{ old('description') }}</textarea>
                                            </div>
                                            <x-input-error :messages="$errors->get('description')" class="mt-2 text-danger" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="mb-3">
                                            <label for="unit_id" class="form-label">Satuan</label>
                                            <x-select2 id="unit_id" name="unit_id" required>
                                                <option value="">Pilih Satuan</option>
                                                @foreach($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                @endforeach
                                            </x-select2>
                                            <x-input-error :messages="$errors->get('unit_id')" class="mt-2 text-danger" />
                                        </div>
                                        <div class="mb-3">
                                            <label for="brand_id" class="form-label">Merek</label>
                                            <x-select2 id="brand_id" name="brand_id" required>
                                                <option value="">Pilih Merek</option>
                                                @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </x-select2>
                                            <x-input-error :messages="$errors->get('brand_id')" class="mt-2 text-danger" />
                                        </div>
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Kategori</label>
                                            <x-select2 id="category_id" name="category_id[]" required>
                                                <option value="">Pilih Kategori</option>
                                                @foreach($categories->where('parent_id', null) as $parentCategory)
                                                <option value="{{ $parentCategory->id }}" disabled>{{ $parentCategory->name }}</option>
                                                @foreach($parentCategory->children as $childCategory)
                                                <option value="{{ $childCategory->id }}">&nbsp;&nbsp;&nbsp;{{ $childCategory->name }}</option>
                                                @endforeach
                                                @endforeach
                                            </x-select2>
                                            <x-input-error :messages="$errors->get('category_id')" class="mt-2 text-danger" />
                                        </div>
                                        <div class="mb-3">
                                            <label for="store_id" class="form-label">Toko</label>
                                            <x-select2 id="store_id" name="store_id" required>
                                                <option value="">Pilih Toko</option>
                                                @foreach($stores as $store)
                                                <option value="{{ $store->id }}">{{ $store->store_name }}</option>
                                                @endforeach
                                            </x-select2>
                                            <x-input-error :messages="$errors->get('store_id')" class="mt-2 text-danger" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-4">
                                    <h5 class="mb-3">
                                        <i class="bx bx-package me-2"></i>Detail Produk
                                    </h5>
                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="hasVariant" name="has_variant">
                                            <label class="form-check-label" for="hasVariant">
                                                Produk ini memiliki varian
                                            </label>
                                        </div>
                                        <button type="button" id="addVariantBtn" class="btn btn-primary btn-sm custom-btn-color" style="display: none;">
                                            <i class="bx bx-plus me-1"></i>Tambah Varian
                                        </button>
                                    </div>
                                    <div id="productDetailContainer" class="table-responsive">
                                        <div class="table-wrapper" style="overflow-x: auto;">
                                            <table class="table table-bordered table-hover" id="variantTable">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="variant-field" style="display: none; min-width: 120px;">Variasi 1</th>
                                                        <th class="variant-field" style="display: none; min-width: 120px;">Variasi 2</th>
                                                        <th class="variant-field" style="display: none; min-width: 120px;">Variasi 3</th>
                                                        <th style="min-width: 150px;">Harga Beli</th>
                                                        <th style="min-width: 150px;">Harga Jual</th>
                                                        <th style="min-width: 100px;">Stok</th>
                                                        <th style="min-width: 120px;">Berat (kg)</th>
                                                        <th style="min-width: 150px;">SKU</th>
                                                        <th style="min-width: 150px;">Barcode</th>
                                                        <th style="min-width: 200px;">Gambar</th>
                                                        <th class="variant-field" style="display: none; min-width: 100px;">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr id="defaultRow">
                                                        <td class="variant-field" style="display: none;">
                                                            <input type="text" class="form-control form-control-sm" id="variasi_1" name="variasi_1" placeholder="Contoh: Merah">
                                                        </td>
                                                        <td class="variant-field" style="display: none;">
                                                            <input type="text" class="form-control form-control-sm" id="variasi_2" name="variasi_2" placeholder="Contoh: M">
                                                        </td>
                                                        <td class="variant-field" style="display: none;">
                                                            <input type="text" class="form-control form-control-sm" id="variasi_3" name="variasi_3" placeholder="Contoh: Garis-garis">
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-text">Rp</span>
                                                                <input type="number" class="form-control form-control-sm" id="purchase_price" name="purchase_price" placeholder="Harga Beli" required>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-text">Rp</span>
                                                                <input type="number" class="form-control form-control-sm" id="sale_price" name="sale_price" placeholder="Harga Jual" required>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control form-control-sm" id="stock" name="stock" placeholder="Stok" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" step="0.01" class="form-control form-control-sm" id="weight" name="weight" placeholder="Berat (kg)" required>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm" id="sku" name="sku" placeholder="SKU" required>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="text" class="form-control form-control-sm" id="barcode" name="barcode" placeholder="Barcode" required>
                                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="generateBarcode('barcode')"><i class='bx bx-barcode'></i></button>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="file" class="form-control form-control-sm" id="images" name="images[]" multiple accept="image/*">
                                                        </td>
                                                        <td class="variant-field" style="display: none;">
                                                            <button type="button" class="btn btn-danger btn-sm remove-variant" disabled>Hapus</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="variantContainer" style="display: none;" class="mt-3">
                            </div>
                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg me-sm-3 me-1 custom-btn-color">
                                    <i class="bx bx-save me-2"></i>Simpan Produk
                                </button>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="bx bx-arrow-back me-2"></i>Kembali
                                </a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script> -->

<script>
    $(document).ready(function() {
        // $('.form-select').select2({
        //     allowClear: true
        // });

        let defaultRowHtml = $('#defaultRow').clone().html();

        $('#hasVariant').change(function() {
            if (this.checked) {
                $('.variant-field').show();
                $('#variantContainer').show();
                $('#addVariantBtn').show();
                changeInputNamesToVariant();
            } else {
                $('.variant-field').hide();
                $('#variantContainer').hide();
                $('.variant-field input').val('');
                $('#variantContainer').empty();
                $('#addVariantBtn').hide();
                resetInputNames();
                $('#variantTable tbody').html('<tr id="defaultRow">' + defaultRowHtml + '</tr>');
            }
        });

        let variantCount = 1;
        $('#addVariantBtn').click(function() {
            variantCount++;
            let newVariant = `
                <tr>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="variants[${variantCount}][variasi_1]" placeholder="Variasi 1">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="variants[${variantCount}][variasi_2]" placeholder="Variasi 2">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="variants[${variantCount}][variasi_3]" placeholder="Variasi 3">
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control form-control-sm" name="variants[${variantCount}][purchase_price]" placeholder="Harga Beli">
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control form-control-sm" name="variants[${variantCount}][sale_price]" placeholder="Harga Jual">
                        </div>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="variants[${variantCount}][stock]" placeholder="Stok">
                    </td>
                    <td>
                        <input type="number" step="0.01" class="form-control form-control-sm" name="variants[${variantCount}][weight]" placeholder="Berat (kg)">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="variants[${variantCount}][sku]" placeholder="SKU">
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control form-control-sm" id="variantBarcode_${variantCount}" name="variants[${variantCount}][barcode]" placeholder="Barcode" required>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="generateBarcode('variantBarcode_${variantCount}')"><i class='bx bx-barcode'></i></button>
                        </div>
                    </td>
                    <td>
                        <input type="file" class="form-control form-control-sm" name="variants[${variantCount}][images][]" multiple accept="image/*">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-variant">Hapus</button>
                    </td>
                </tr>
            `;
            $('#variantTable tbody').append(newVariant);
            updateRemoveButtons();
        });

        $(document).on('click', '.remove-variant', function() {
            $(this).closest('tr').remove();
            updateRemoveButtons();
        });

        $('#productForm').submit(function() {
            if (!$('#hasVariant').is(':checked')) {
                $('.variant-field input').val('');
                $('#variantContainer tbody').empty();
            }
        });

        function changeInputNamesToVariant() {
            $('#purchase_price').attr('name', 'variants[1][purchase_price]');
            $('#sale_price').attr('name', 'variants[1][sale_price]');
            $('#stock').attr('name', 'variants[1][stock]');
            $('#weight').attr('name', 'variants[1][weight]');
            $('#sku').attr('name', 'variants[1][sku]');
            $('#barcode').attr('name', 'variants[1][barcode]');
            $('#images').attr('name', 'variants[1][images][]');
            $('#variasi_1').attr('name', 'variants[1][variasi_1]');
            $('#variasi_2').attr('name', 'variants[1][variasi_2]');
            $('#variasi_3').attr('name', 'variants[1][variasi_3]');
        }

        function resetInputNames() {
            $('#purchase_price').attr('name', 'purchase_price');
            $('#sale_price').attr('name', 'sale_price');
            $('#stock').attr('name', 'stock');
            $('#weight').attr('name', 'weight');
            $('#sku').attr('name', 'sku');
            $('#barcode').attr('name', 'barcode');
            $('#images').attr('name', 'images[]');
            $('#variasi_1').attr('name', 'variasi_1');
            $('#variasi_2').attr('name', 'variasi_2');
            $('#variasi_3').attr('name', 'variasi_3');
        }

        function updateRemoveButtons() {
            let rows = $('#variantTable tbody tr');
            if (rows.length === 1) {
                rows.find('.remove-variant').prop('disabled', true);
            } else {
                rows.find('.remove-variant').prop('disabled', false);
            }
        }

    });
</script>
<script>
    // Fungsi untuk generate barcode EAN-13
    function generateBarcode(inputId) {
        function generateEAN13() {
            let barcode = '899';
            for (let i = 0; i < 9; i++) {
                barcode += Math.floor(Math.random() * 10);
            }
            let sum = 0;
            for (let i = 0; i < 12; i++) {
                sum += parseInt(barcode.charAt(i)) * (i % 2 === 0 ? 1 : 3);
            }
            let checkDigit = (10 - (sum % 10)) % 10;
            return barcode + checkDigit;
        }
        const barcode = generateEAN13();
        document.getElementById(inputId).value = barcode;
    }
</script>
@endsection