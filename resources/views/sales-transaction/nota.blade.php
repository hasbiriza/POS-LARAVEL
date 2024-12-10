<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi</title>
    <style>
        @page {
            size: 80mm;
            margin: 0;
        }
        body {
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 8pt;
            line-height: 1.2;
            margin: 5mm;
            padding: 0;
            /* background-color: #f8f9fa; */
        }
        .container {
            width: 100%;
            /* max-width: 80mm; */
            /* padding: 0; */
            background-color: #ffffff;
        }
        .header {
            text-align: center;
            margin-bottom: 3mm;
        }
        .header h3 {
            font-size: 10pt;
            margin: 0;
            color: #343a40;
        }
        .header p {
            font-size: 7pt;
            margin: 0;
            color: #6c757d;
        }
        .info {
            margin-bottom: 2mm;
        }
        .info p {
            margin: 0.3mm 0;
            display: flex;
            justify-content: space-between;
            color: #495057;
            font-size: 7pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2mm;
            font-size: 7pt;
        }
        th, td {
            padding: 0.5mm;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .total {
            text-align: left;
        }
        .total p {
            margin: 0.3mm 0;
            display: flex;
            justify-content: space-between;
            color: #495057;
            font-size: 7pt;
        }
        .item-details {
            font-size: 7pt;
            color: #6c757d;
        }
        .store-logo {
            max-width: 12mm;
            margin-bottom: 1mm;
        }
        .divider {
            border-top: 0.1mm solid #dee2e6;
            margin: 1mm 0;
        }
        .footer {
            text-align: center;
            font-size: 7pt;
            margin-top: 3mm;
            color: #6c757d;
        }
        .total-items {
            text-align: center;
            font-weight: bold;
            margin: 1mm 0;
            color: #343a40;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="header">
            <img src="{{ asset('storage/' . $store->logo) }}" alt="Logo Toko" class="store-logo">
            <h3>{{ $store->store_name }}</h3>
            <p>{{ $store->address }}</p>
            <p>Telp: {{ $store->phone }}</p>
        </div>
        
        <div class="divider"></div>
        
        <div class="info">
            <p><span><strong>No:</strong> INV-{{ date('Ymd', strtotime($transaction->transaction_date)) }}-{{ str_pad($transaction->id, 3, '0', STR_PAD_LEFT) }}</span></p>
            <p><span><strong>Tgl:</strong> {{ date('d/m/Y H:i', strtotime($transaction->transaction_date)) }}</span></p>
            <div class="divider"></div>
            <p><span><strong>Cust:</strong> {{ $customer->name }}</span> <span><strong>Kasir:</strong> {{ $kasir->name }}</span> </p>
        </div>
        
        <div class="divider"></div>
        
        <table>
            <tbody>
                @foreach ($items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->item_name }}</strong>
                        @if($item->variasi_1 || $item->variasi_2 || $item->variasi_3)
                            ({{ $item->variasi_1 }} {{ $item->variasi_2 }} {{ $item->variasi_3 }})
                        @endif
                        <br>
                        <span class="item-details">
                            {{ $item->quantity }} {{ $item->unit_name }} x {{ number_format($item->sale_price, 0, ',', '.') }}
                        </span>
                    </td>
                    <td style="text-align: right;">{{ number_format($item->quantity * $item->sale_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        
        <div class="total-items">
            Total Item: {{ $items->sum('quantity') }}
        </div>
        
        <div class="divider"></div>
        
        <div class="total">
            @if($transaction->discount_amount > 0)
            <p><span>Diskon</span> <span>{{ number_format($transaction->discount_amount, 0, ',', '.') }}</span></p>
            @endif
            @if($transaction->tax_amount > 0)
            <p><span>Pajak</span> <span>{{ number_format($transaction->tax_amount, 0, ',', '.') }}</span></p>
            @endif
            <p><span><strong>Total</strong></span> <span> <strong>{{ number_format($transaction->total_amount - $transaction->discount_amount + $transaction->tax_amount, 0, ',', '.') }}</strong></span></p>
            <p><span>Bayar ({{ ucfirst($transaction->payment_method) }})</span> <span>{{ number_format($transaction->total_payment, 0, ',', '.') }}</span></p>
            @if($transaction->change_payment > 0)
            <p><span>Kembalian</span> <span>{{ number_format($transaction->change_payment, 0, ',', '.') }}</span></p>
            @endif
        </div>
        
        <div class="divider"></div>
        
        <div class="footer">
            <p>Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan</p>
        </div>
    </div>
</body>
</html>
