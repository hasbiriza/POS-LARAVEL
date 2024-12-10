<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
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
            <h1>INVOICE</h1>
        </div>
        <div class="invoice-details">
            <div class="barcode-status-container">
                <div class="status {{ $transaction->status == 'lunas' ? 'status-lunas' : 'status-belum-lunas' }}">
                    {{ strtoupper($transaction->status) }}
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
    <h3>Transaksi</h3>
        <p> {{ $transaction->transaction_date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Item</th>
                <th>Unit</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>
                    {{ $item->item_name }}
                    <br>
                    <small>
                        {{ $item->variasi_1 }}
                        {{ $item->variasi_2 ? ', ' . $item->variasi_2 : '' }}
                        {{ $item->variasi_3 ? ', ' . $item->variasi_3 : '' }}
                    </small>
                </td>
                <td>{{ $item->unit_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>Rp {{ number_format($item->sale_price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item->quantity * $item->sale_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Diskon:</strong></td>
                <td><strong>Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Pajak:</strong></td>
                <td><strong>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                <td><strong>Rp {{ number_format($transaction->total_amount - $transaction->discount_amount + $transaction->tax_amount, 0, ',', '.') }}</strong></td>
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
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paymentMethods as $payment)
            <tr>
                <td>{{ $payment->payment_date }}</td>
                <td>{{ $payment->payment_method }}</td>
                <td>{{ $payment->bank_name }} - {{ $payment->bank_account_number }}</td>
                <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total Pembayaran:</strong></td>
                <td><strong>Rp {{ number_format($transaction->total_payment, 0, ',', '.') }}</strong></td>
            </tr>
            @if($transaction->payment_method === 'tempo')
                @if($transaction->remaining_payment > 0)
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Sisa Pembayaran:</strong></td>
                    <td><strong>Rp {{ number_format($transaction->remaining_payment, 0, ',', '.') }}</strong></td>
                </tr>
                @endif
            @endif
            @if($transaction->change_payment > 0)
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Kembalian:</strong></td>
                <td><strong>Rp {{ number_format($transaction->change_payment, 0, ',', '.') }}</strong></td>
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
                <p class="thank-you">* Terima kasih telah berbelanja di tempat kami, Kepuasan anda adalah tujuan kami.</p>
            </td>
            <td style="width: 25%; text-align: center;">
                <p>Kasir</p>
                <div class="signature-box"></div>
                <p>( {{ $kasir->name }} )</p>
            </td>
            <td style="width: 25%; text-align: center;">
                <p>Customer</p>
                <div class="signature-box"></div>
                <p>( {{ $customer->name }} )</p>
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