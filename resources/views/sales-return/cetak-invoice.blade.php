<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Retur Penjualan</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            margin: 0;
            padding: 10mm;
            font-size: 10pt;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10mm;
        }
        .logo-address {
            display: flex;
            align-items: flex-start;
            width: 40%;
        }
        .logo {
            width: 25mm;
            height: auto;
            margin-right: 5mm;
        }
        .address {
            flex-grow: 1;
        }
        .invoice-title {
            width: 20%;
            text-align: center;
            margin-top: 15mm; 
        }
        .invoice-details {
            width: 40%;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5mm;
        }
        th, td {
            border: 0.5pt solid #ddd;
            padding: 2mm;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer-table {
            width: 100%;
            margin-top: 5mm;
        }
        .footer-table td {
            vertical-align: top;
            border: none;
            padding: 0;
        }
        .signature-box {
            height: 20mm;
        }
        .note-box {
            border: 0.5pt solid #c2c2c2;
            padding: 2mm;
            height: 30mm;
            width: 100%;
        }
        .thank-you {
            font-style: italic;
            font-size: 9pt;
            text-align: left;
            margin-top: 3mm;
        }
        .barcode {
            text-align: right;
            margin-bottom: 3mm;
        }
        .barcode-status-container {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-address">
            <img src="{{ asset('storage/' . $store->logo) }}" alt="Logo Toko" class="logo">
            <div class="address">
                <p><strong>{{ $store->store_name }}</strong><br>{{ $store->address }}</p>
            </div>
        </div>
        <div class="invoice-title">
            <h1>NOTA RETUR</h1>
        </div>
        <div class="invoice-details">
            <div class="barcode-status-container">
                <div class="status" style="background-color: red; color: white; padding: 5px 10px; margin-bottom: 10px;">
                    RETUR
                </div>
                <div class="barcode">
                    <svg id="barcode"></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="customer-details">
        <h3>Pembeli:</h3>
        <p>Nama : {{ $customer->name }}</p>
        <p>Alamat : {{ $customer->address }}</p>
    </div>

    <div style="display: flex; justify-content: space-between;">
        <h3>Transaksi Retur</h3>
        <p>{{ $return->return_date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah Retur</th>
                <th>Diskon</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($returnItems as $item)
                @if($item->quantity_returned > 0)
                <tr>
                    <td>
                        {{ $item->product_name }}
                        <br>
                        <small>
                            {{ $item->variasi_1 }}
                            {{ $item->variasi_2 ? ', ' . $item->variasi_2 : '' }}
                            {{ $item->variasi_3 ? ', ' . $item->variasi_3 : '' }}
                        </small>
                    </td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>{{ $item->quantity_returned }}</td>
                    <td>{{ number_format($item->discount_item, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Subtotal Retur</strong></td>
                <td><strong>Rp {{ number_format($return->total_return, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Diskon</strong></td>
                <td><strong>Rp {{ number_format($return->discount_return, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Pajak</strong></td>
                <td><strong>Rp {{ number_format($return->tax_return, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total Retur</strong></td>
                <td><strong>Rp {{ number_format($return->total_return - $return->discount_return + $return->tax_return, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <table class="footer-table">
        <tr>
            <td style="width: 50%;">
                <strong>Catatan:</strong>
                <div class="note-box">
                    <p>{{ $return->note ?? 'Tidak ada catatan' }}</p>
                </div>
                <p class="thank-you">* Terima kasih atas pengertian Anda</p>
            </td>
            <td style="width: 25%; text-align: center;">
                <p>Toko</p>
                <div class="signature-box"></div>
                <p>( {{ $store->store_name }} )</p>
            </td>
            <td style="width: 25%; text-align: center;">
                <p>Customer</p>
                <div class="signature-box"></div>
                <p>( {{ $customer->name }} )</p>
            </td>
        </tr>
    </table>
    <script>
        JsBarcode("#barcode", "{{ $return->return_no }}", {
            format: "CODE128",
            width: 1.5,
            height: 40,
            displayValue: true
        });
    </script>
</body>
</html>