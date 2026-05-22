<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Struk Transaksi</title>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            padding: 20px;
        }

        .card {
            max-width: 380px;
            margin: auto;
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .card-header {
            text-align: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .title {
            font-size: 12px;
            letter-spacing: 3px;
            color: #007bff;
            font-weight: bold;
        }

        .subtitle {
            margin-top: 5px;
            font-size: 16px;
            font-weight: 800;
        }

        .body {
            padding: 15px;
            font-size: 11px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .border-dashed {
            border-top: 1px dashed #ccc;
            margin: 10px 0;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="card">

        <div class="card-header">
            <img src="{{ public_path('storage/images/logo.png') }}" width="50">

            <div class="title">SIMKOPI RECEIPT</div>
            <div class="subtitle">STRUK PEMBELIAN</div>
        </div>

        <div class="body">

            <table>
                <tr>
                    <td>Bon #{{ $transaksi->id_pesanan }}</td>
                    <td class="text-right">{{ $transaksi->staff->nama ?? '-' }}</td>
                </tr>
            </table>

            <div class="border-dashed"></div>

            <table>
                <thead>
                    <tr>
                        <th align="left">Produk</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($details as $item)
                        <tr>
                            <td>{{ $item['nama_produk'] }}</td>
                            <td class="text-center">{{ $item['jumlah'] }}</td>
                            <td class="text-right">{{ number_format($item['harga'], 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="border-dashed"></div>

            <table>
                <tr>
                    <td>Total Item</td>
                    <td class="text-right">{{ $total_item }}</td>
                </tr>

                <tr>
                    <td>Total Harga</td>
                    <td class="text-right">
                        Rp {{ number_format($total_harga, 0, ',', '.') }}
                    </td>
                </tr>

                <tr>
                    <td>
                        Diskon (%)
                    </td>
                    <td class="text-right">
                        {{ $diskon }}%
                    </td>
                </tr>

                <tr>
                    <td>
                        Diskon (Rp.)
                    </td>
                    <td class="text-right">
                        - Rp {{ number_format($diskon_rp, 0, ',', '.') }}
                    </td>
                </tr>


                <tr>
                    <td><b>Total Bayar</b></td>
                    <td class="text-right">
                        <b>Rp {{ number_format($total_bayar, 0, ',', '.') }}</b>
                    </td>
                </tr>

                <tr>
                    <td>Bayar</td>
                    <td class="text-right">
                        Rp {{ number_format($transaksi->uang_bayar ?? $total_bayar, 0, ',', '.') }}
                    </td>
                </tr>

                <tr>
                    <td><b>Kembalian</b></td>
                    <td class="text-right">
                        Rp {{
    number_format(
        ($transaksi->uang_bayar ?? $total_bayar) - $total_bayar,
        0,
        ',',
        '.'
    )
                    }}
                    </td>
                </tr>
            </table>

            <div class="border-dashed"></div>

            <div class="text-center" style="font-size:10px;">
                {{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d F Y') }}
                {{ \Carbon\Carbon::parse($transaksi->created_at)->format('H:i:s') }}
            </div>

            <div class="text-center" style="margin-top:5px; font-weight:bold;">
                MEMBER: {{ strtoupper($transaksi->nama_pembeli) }}
            </div>

            <div style="margin-top:10px; background:#fff3cd; padding:6px; font-size:10px; text-align:center;">
                E-receipt available for 90 days
            </div>

        </div>
    </div>

</body>

</html>