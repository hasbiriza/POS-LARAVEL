<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pembelian</title>
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
        .status {
            padding: 2mm 4mm;
            font-weight: bold;
            border: 0.5pt solid;
            display: inline-block;
            margin-bottom: 3mm;
        }
        .status-lunas {
            background-color: #40ab40;
            color: white;
        }
        .status-belum-lunas {
            background-color: red;
            color: white;
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
<body onload="window.print()">
    <div class="header">
        <div class="logo-address">
            <img src="{{ asset('storage/' . $store->logo) }}" alt="Logo Toko" class="logo">
            <div class="address">
                <p><strong>{{ $store->store_name }}</strong><br>{{ $store->address }}</p>
            </div>
        </div>
        <div class="invoice-title">
            <h1>INVOICE PEMBELIAN</h1>
        </div>
        <div class="invoice-details">
            <div class="barcode-status-container">
                <div class="status {{ $transaction->payment_status == 'lunas' ? 'status-lunas' : 'status-belum-lunas' }}">
                    {{ strtoupper($transaction->payment_status) }}
                </div>
                <div class="barcode">
                    <svg id="barcode"></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="supplier-details">
        <h3>Supplier:</h3>
        <p>Nama : {{ $supplier->store_name }}</p>
        <p>Alamat : {{ $supplier->address }}</p>
    </div>

    <div style="display: flex; justify-content: space-between;">
    <h3>Transaksi</h3>
        <p> {{ $transaction->purchase_date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Item</th>
                <th>SKU</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Diskon</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>
                    {{ $product->product_name }}
                    <br>
                    <small>
                        {{ $product->variasi_1 }}
                        {{ $product->variasi_2 ? ', ' . $product->variasi_2 : '' }}
                        {{ $product->variasi_3 ? ', ' . $product->variasi_3 : '' }}
                    </small>
                </td>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->quantity }}</td>
                <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($product->diskon, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($product->quantity * $product->purchase_price - $product->diskon, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right;"><strong>Sub Total:</strong></td>
                <td><strong>Rp {{ number_format($products->sum(function($product) { return $product->quantity * $product->purchase_price - $product->diskon; }), 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right;"><strong>Diskon:</strong></td>
                <td><strong>Rp {{ number_format($transaction->discount, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right;"><strong>Grand Total:</strong></td>
                <td><strong>Rp {{ number_format($transaction->total_amount - $transaction->discount, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <h3>Biaya Lain-lain</h3>
    <table>
        <thead>
            <tr>
                <th>Kategori Biaya</th>
                <th>Jumlah</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
            <tr>
                <td>{{ $expense->name }}</td>
                <td>Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                <td>{{ $expense->note }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align: right;"><strong>Total Biaya Lain-lain:</strong></td>
                <td><strong>Rp {{ number_format($expenses->sum('amount'), 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <h3>Pembayaran</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal Pembayaran</th>
                <th>Metode Pembayaran</th>
                <th>Bank</th>
                <th>Catatan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detail_payment as $payment)
            <tr>
                <td>{{ $payment->payment_date }}</td>
                <td>{{ $payment->payment_method }}</td>
                <td>{{ $payment->bank_name }} - {{ $payment->bank_account_number }}</td>
                <td>{{ $payment->payment_note }}</td>
                <td>Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total Pembayaran:</strong></td>
                <td><strong>Rp {{ number_format($detail_payment->sum('amount_paid'), 0, ',', '.') }}</strong></td>
            </tr>
            @if($transaction->remaining_payment > 0)
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Sisa Pembayaran:</strong></td>
                <td><strong>Rp {{ number_format($transaction->remaining_payment, 0, ',', '.') }}</strong></td>
            </tr>
            @else
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Kembalian:</strong></td>
                <td><strong>Rp {{ number_format($detail_payment->sum('amount_paid') - ($transaction->total_amount - $transaction->discount + $expenses->sum('amount')), 0, ',', '.') }}</strong></td>
            </tr>
            @endif

        </tfoot>
    </table>

    <table class="footer-table">
        <tr>
            <td style="width: 50%;">
                <strong>Catatan:</strong>
                <div class="note-box">
                    <p>{{ $transaction->note ?? 'Tidak ada catatan' }}</p>
                </div>
                <p class="thank-you">* Terima kasih atas kerjasamanya.</p>
            </td>
            <td style="width: 25%; text-align: center;">
                <p>Pembeli</p>
                <div class="signature-box"></div>
                <p>( {{ $store->store_name }} )</p>
            </td>
            <td style="width: 25%; text-align: center;">
                <p>Supplier</p>
                <div class="signature-box"></div>
                <p>( {{ $supplier->store_name }} )</p>
            </td>
        </tr>
    </table>
    <script>
        JsBarcode("#barcode", "{{ $transaction->transaction_id }}", {
            format: "CODE128",
            width: 1.5,
            height: 40,
            displayValue: true
        });
    </script>
</body>
</html>