@extends('template.app')
@section('title', 'Barcode Produk')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">

<style type="text/css">
    .select2-container {
        z-index: 9999;
    }

    .barcode-container {
        display: flex;
        flex-wrap: wrap;
    }

    .barcode-item {
        padding: 10px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .barcode-image {
        width: 100%;
        height: auto;
    }

    .product-name {
        text-align: center;
        margin-top: 5px;
    }
</style>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Barcode Produk</li>
@endsection

@section('content')
<div class="flex-grow-1">
    <div class="card mb-3">
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label for="productSearch" class="form-label">Cari Produk</label>
                    <x-select2 class="form-control select2" id="productSearch" name="product" placeholder="Cari produk..."></x-select2>
                </div>
            </form>
            <div id="selectedProducts"></div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Pengaturan Label Barcode</h5>
        </div>
        <div class="card-body">
            <form id="barcodeSettingsForm">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="labelWidth" class="form-label">Lebar Label (mm)</label>
                        <input type="number" class="form-control" id="labelWidth" name="labelWidth" value="33">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="labelHeight" class="form-label">Tinggi Label (mm)</label>
                        <input type="number" class="form-control" id="labelHeight" name="labelHeight" value="15">
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="barcodeWidth" class="form-label">Lebar Barcode (mm)</label>
                        <input type="number" class="form-control" id="barcodeWidth" name="barcodeWidth" value="30">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="barcodeHeight" class="form-label">Tinggi Barcode (mm)</label>
                        <input type="number" class="form-control" id="barcodeHeight" name="barcodeHeight" value="10">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="fontSize" class="form-label">Ukuran Font Nama Produk (px)</label>
                        <input type="number" class="form-control" id="fontSize" name="fontSize" value="8">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="leftMargin" class="form-label">Margin Kiri (mm)</label>
                        <input type="number" class="form-control" id="leftMargin" name="leftMargin" value="2">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="topMargin" class="form-label">Margin Atas (mm)</label>
                        <input type="number" class="form-control" id="topMargin" name="topMargin" value="2">
                    </div>


                    <div class="col-md-4 mb-3">
                        <label for="barcodeGap" class="form-label">Jarak Garis Kode Batang (mm)</label>
                        <input type="number" class="form-control" id="barcodeGap" name="barcodeGap" value="3" min="0">
                    </div>


                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="displayName" name="displayName" checked>
                            <label class="form-check-label" for="displayName">
                                Tampilkan Nama Produk
                            </label>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="showVariations" name="showVariations" checked>
                            <label class="form-check-label" for="showVariations">
                                Tampilkan Variasi Produk
                            </label>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>

<script>
    $(document).ready(function() {
        // Select2 product search initialization
        $('#productSearch').select2({
            placeholder: 'Cari produk...',
            ajax: {
                url: '{{ route("productbarcode.search") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        name: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.product_name + ' ' + (item.variasi_1 || '') + ' ' + (item.variasi_2 || '') + ' ' + (item.variasi_3 || ''),
                                id: item.product_pricing_id,
                                data: item
                            }
                        })
                    };
                },
                cache: true
            }
        });

        // Handling selected products and barcode settings
        $('#productSearch').on('select2:select', function(e) {
            var data = e.params.data.data;
            if ($('#selectedProducts table').length === 0) {
                var tableHtml = `
                <table class="table table-bordered product-item mb-3">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>SKU</th>
                            <th>Barcode</th>
                            <th>Jumlah Barcode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary mt-3 custom-btn-color" id="printButton">
                    <i class="bx bx-printer"></i> Cetak Barcode
                </button>
            `;
                $('#selectedProducts').append(tableHtml);
            }
            // Store the product name without variations
            var baseProductName = data.product_name; // Only base product name
            var variations = [data.variasi_1, data.variasi_2, data.variasi_3].filter(Boolean).join(', '); // Join non-empty variations

            var productHtml = `
            <tr data-variasi='${JSON.stringify([data.variasi_1, data.variasi_2, data.variasi_3])}'>
                <td>
                    <input type="text" class="form-control" name="products[${data.product_pricing_id}][name]" value="${baseProductName} ${variations ? '(' + variations + ')' : ''}" readonly>

                </td>
                <td>
                    <input type="text" class="form-control" name="products[${data.product_pricing_id}][sku]" value="${data.sku}" readonly>
                </td>
                <td>
                    <input type="text" class="form-control" name="products[${data.product_pricing_id}][barcode]" value="${data.barcode}" readonly>
                </td>
                <td>
                    <input type="number" class="form-control" name="products[${data.product_pricing_id}][jumlah_barcode]" placeholder="Jumlah Barcode">
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-product">X</button>
                </td>
            </tr>
        `;
            $('#productTableBody').append(productHtml);
        });

        // Remove selected product
        $(document).on('click', '.remove-product', function() {
            $(this).closest('tr').remove();
            if ($('#productTableBody tr').length === 0) {
                $('#selectedProducts table').remove();
                $('#printButton').remove();
            }
        });

        // Print barcode logic
        $(document).on('click', '#printButton', function() {
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print Barcode</title>');
            printWindow.document.write('<style>');
            printWindow.document.write(`
            @page { size: roll; margin: 0; }
            body { margin: 0; }
            .barcode-container { display: flex; flex-wrap: wrap; }
            .barcode-item { width: ${$('#labelWidth').val()}mm; height: ${$('#labelHeight').val()}mm; padding: 5px; box-sizing: border-box; margin-left: ${$('#leftMargin').val()}mm; margin-top: ${$('#topMargin').val()}mm;}
            .barcode-image { width: ${$('#barcodeWidth').val()}mm; height: ${$('#barcodeHeight').val()}mm; }
            .product-name { font-size: ${$('#fontSize').val()}px; }
        `);
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<div class="barcode-container">');

            var barcodePromises = [];

            $('#productTableBody tr').each(function() {
                var barcode = $(this).find('input[name$="[barcode]"]').val();
                var baseProductName = $(this).find('input[name$="[name]"]').val().split(' (')[0]; // Use only the base product name
                var quantity = parseInt($(this).find('input[name$="[jumlah_barcode]"]').val()) || 0;

                // Ambil variasi dari atribut data
                var variations = JSON.parse($(this).attr('data-variasi'));

                for (var i = 0; i < quantity; i++) {
                    barcodePromises.push(generateBarcode(barcode, baseProductName, variations));
                }
            });

            Promise.all(barcodePromises).then(function(barcodeHtml) {
                printWindow.document.write(barcodeHtml.join(''));
                printWindow.document.write('</div>');
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
            });
        });

        function generateBarcode(barcode, productName, variations) {
            return new Promise(function(resolve) {
                var canvas = document.createElement('canvas');
                var fontSize = Math.max(parseInt($('#fontSize').val()), 10);

                JsBarcode(canvas, barcode, {
                    format: 'EAN13',
                    displayValue: true,
                    width: 1 + parseFloat($('#barcodeGap').val()),
                    fontSize: 30,
                    flat: true
                });

                // Ambil variasi sebagai string
                var variationsString = variations.filter(Boolean).join(', ');
                var showVariationsHtml = $('#showVariations').is(':checked') && variationsString ? `<div class="product-name">${variationsString}</div>` : '';

                // Tampilkan nama produk jika checkbox dicentang
                var displayNameHtml = $('#displayName').is(':checked') ? `<div class="product-name">${productName}</div>` : '';

                // Menggabungkan HTML untuk barcode
                var barcodeHtml = `
            <div class="barcode-item">
                <img class="barcode-image" src="${canvas.toDataURL('image/png')}">
                ${displayNameHtml}
                ${showVariationsHtml}

            </div>
        `;
                resolve(barcodeHtml);
            });
        }
    });
</script>
@endsection