<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembelian</title>
    <style>
        @page {
            size: 80mm 210mm;
            margin: 0;
        }
        body {
            font-family: 'Arial Narrow', Arial, sans-serif;
            font-size: 8pt;
            line-height: 1.2;
            margin: 0;
            padding: 2mm;
            display: flex;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .container {
            width: 70mm;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 5mm;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 2mm;
        }
        .header h3 {
            font-size: 10pt;
            margin: 0;
            color: #343a40;
        }
        .header p {
            font-size: 8pt;
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
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2mm;
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
        }
        .item-details {
            font-size: 7pt;
            color: #6c757d;
        }
        .store-logo {
            max-width: 10mm;
            margin-bottom: 1mm;
        }
        .divider {
            border-top: 0.1mm solid #dee2e6;
            margin: 1mm 0;
        }
        .footer {
            text-align: center;
            font-size: 7pt;
            margin-top: 2mm;
            color: #6c757d;
        }
        .total-items {
            text-align: center;
            font-weight: bold;
            margin: 1mm 0;
            color: #343a40;
        }
        .total-section {
            margin-top: 2mm;
        }
        .total-section p {
            display: flex;
            justify-content: space-between;
            margin: 0.5mm 0;
        }
        .total-section .label {
            font-weight: bold;
        }
        .total-section .value {
            text-align: right;
        }
        .expenses-section {
            margin-top: 1mm;
        }
        .expenses-section p {
            margin: 0.5mm 0;
        }
        .expenses-section .expense-item {
            display: flex;
            justify-content: space-between;
        }
        .expenses-section .expense-note {
            font-size: 7pt;
            color: #6c757d;
            margin-left: 2mm;
        }
    </style>
</head>
<!-- <body onload="window.print()"> -->
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('storage/' . $store->logo) }}" alt="Logo Toko" class="store-logo">
            <h3>{{ $store->store_name }}</h3>
            <p>{{ $store->address }}</p>
            <p>Telp: {{ $store->phone }}</p>
        </div>
        
        <div class="divider"></div>
        
        <div class="info">
            <p><span><strong>No:</strong> {{ $transaction->transaction_id }}</span></p>
            <p><span><strong>Tgl:</strong> {{ date('d/m/Y H:i', strtotime($transaction->purchase_date)) }}</span></p>
            <div class="divider"></div>
            <p><span><strong>Supplier:</strong> {{ $supplier->store_name }}</span></p>
        </div>
        
        <div class="divider"></div>
        
        <table>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td>
                        <strong>{{ $product->product_name }}</strong>
                        @if($product->variasi_1 || $product->variasi_2 || $product->variasi_3)
                            ({{ $product->variasi_1 }} {{ $product->variasi_2 }} {{ $product->variasi_3 }})
                        @endif
                        <br>
                        <span class="item-details">
                            {{ $product->quantity }} x {{ number_format($product->purchase_price, 0, ',', '.') }}
                        </span>
                    </td>
                    <td style="text-align: right;">{{ number_format($product->quantity * $product->purchase_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="total-items">
            Total Item: {{ $products->sum('quantity') }}
        </div>
        
        <div class="divider"></div>
        
        <div class="total-section">
            <p>
                <span class="label">Total Pembelian:</span>
                <span class="value">{{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </p>
            
            @if($transaction->discount > 0)
            <p>
                <span class="label">Diskon:</span>
                <span class="value">{{ number_format($transaction->discount, 0, ',', '.') }}</span>
            </p>
            <p>
                <span class="label">Total Setelah Diskon:</span>
                <span class="value">{{ number_format($transaction->total_amount - $transaction->discount, 0, ',', '.') }}</span>
            </p>
            @endif
            @if($expenses->isNotEmpty())
                <div class="expenses-section">
                    <p class="label">Biaya Lain-lain:</p>
                    @foreach($expenses as $expense)
                        <p class="expense-item">
                            <span>{{ $expense->name }}</span>
                            <span>{{ number_format($expense->amount, 0, ',', '.') }}</span>
                        </p>
                        @if($expense->note)
                            <p class="expense-note">{{ $expense->note }}</p>
                        @endif
                    @endforeach
                </div>
            @endif
            
            <div class="divider"></div>
            
            <p>
                <span class="label">Total Keseluruhan:</span>
                <span class="value">{{ number_format($transaction->total_amount - $transaction->discount + $expenses->sum('amount'), 0, ',', '.') }}</span>
            </p>
            <p>
                <span class="label">Total Bayar:</span>
                <span class="value">{{ number_format($detail_payment->sum('amount_paid'), 0, ',', '.') }}</span>
            </p>
            @if($transaction->remaining_payment > 0)
                <p>
                    <span class="label">Sisa Pembayaran:</span>
                    <span class="value">{{ number_format($transaction->remaining_payment, 0, ',', '.') }}</span>
                </p>
            @else
                <p>
                    <span class="label">Kembalian:</span>
                    <span class="value">{{ number_format($detail_payment->sum('amount_paid') - ($transaction->total_amount - $transaction->discount + $expenses->sum('amount')), 0, ',', '.') }}</span>
                </p>
            @endif
        </div>
        
        <div class="divider"></div>
        
        <div class="footer">
            <p>Terima kasih atas kerjasamanya.</p>
        </div>
    </div>
</body>
</html>
