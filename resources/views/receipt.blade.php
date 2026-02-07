<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk {{ $transaction->transaction_code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2D5016;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #2D5016;
            font-size: 18px;
        }
        .header p {
            margin: 3px 0;
            color: #666;
            font-size: 10px;
        }
        .info-section {
            margin-bottom: 15px;
            font-size: 11px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table th {
            background-color: #7FB069;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        table td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        .total-section {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            color: #2D5016;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #2D5016;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px dashed #999;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    
    <!-- Header -->
    <div class="header">
        <h1>🌿 SMART WASTE BANK</h1>
        <p>Sistem Kasir Bank Sampah Berbasis IoT</p>
        <p>Jl. Contoh No. 123, Surabaya | Telp: (031) 1234567</p>
    </div>
    
    <!-- Transaction Info -->
    <div class="info-section">
        <div class="info-row">
            <span><strong>No. Transaksi:</strong></span>
            <span>{{ $transaction->transaction_code }}</span>
        </div>
        <div class="info-row">
            <span><strong>Tanggal:</strong></span>
            <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span><strong>Kasir:</strong></span>
            <span>{{ $transaction->cashier_name }}</span>
        </div>
        <div class="info-row">
            <span><strong>Customer:</strong></span>
            <span>{{ $transaction->customer_name_snapshot }}</span>
        </div>
    </div>
    
    <!-- Items Table -->
    <table>
        <thead>
            <tr>
                <th>Jenis Sampah</th>
                <th style="text-align: center;">Berat (kg)</th>
                <th style="text-align: right;">Harga/kg</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->items as $item)
            <tr>
                <td>{{ $item->waste_type }}</td>
                <td style="text-align: center;">{{ number_format($item->weight, 2) }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->price_per_kg, 0, ',', '.') }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Total -->
    <div class="total-section">
        TOTAL: Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>Terima kasih telah berkontribusi untuk lingkungan yang lebih bersih! 🌍</p>
        <p>Struk ini dicetak pada {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
    
</body>
</html>