<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Belanja #{{ $transaction->id_transaksi }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 80mm;
            margin: 0;
            padding: 5mm;
            background-color: #ffffff;
            color: #000000;
            font-size: 12px;
            line-height: 1.4;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .header h3 {
            margin: 0 0 5px 0;
            font-size: 16px;
            text-transform: uppercase;
        }

        .header p {
            margin: 0 0 3px 0;
            font-size: 11px;
        }

        .info-table, .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td, .items-table td, .items-table th {
            padding: 3px 0;
            font-size: 11px;
        }

        .items-table th {
            text-align: left;
            border-bottom: 1px dashed #000;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
        }

        @media print {
            body {
                width: 80mm;
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print();">

    <div class="header text-center">
        <h3>SEBLAK BUNDAKA</h3>
        <p>Cabang ke-4 - Batuphat Timur</p>
        <p>Muara Satu, Lhokseumawe, Aceh</p>
        <p>HP: 0812-3456-7890</p>
    </div>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td>ID Trx: #{{ $transaction->id_transaksi }}</td>
            <td class="text-right">Kasir: {{ $transaction->user->nama_user }}</td>
        </tr>
        <tr>
            <td>Tgl: {{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d/m/Y') }}</td>
            <td class="text-right">Jam: {{ $transaction->jam_transaksi }}</td>
        </tr>
        <tr>
            <td>Metode: <span style="text-transform: uppercase;">{{ $transaction->metode_pembayaran }}</span></td>
            <td class="text-right">Status: LUNAS</td>
        </tr>
        @php
            $globalLevel = $transaction->detailTransaksi->pluck('level_pedas')->filter()->first();
        @endphp
        @if(!is_null($globalLevel))
        <tr>
            <td colspan="2">Level Pedas: <span class="bold">Lvl {{ $globalLevel }}</span></td>
        </tr>
        @endif
    </table>

    <div class="divider"></div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">Menu</th>
                <th style="width: 15%; text-align: center;">Qty</th>
                <th style="width: 35%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->detailTransaksi as $detail)
                <tr>
                    <td>{{ $detail->barang->nama_barang }}</td>
                    <td style="text-align: center;">{{ $detail->jumlah }}</td>
                    <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <table class="info-table" style="font-weight: bold;">
        <tr>
            <td>TOTAL HARGA:</td>
            <td class="text-right">Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
        </tr>
        <tr style="font-weight: normal;">
            <td>BAYAR:</td>
            <td class="text-right">Rp {{ number_format($transaction->bayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>KEMBALIAN:</td>
            <td class="text-right">Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="footer text-center">
        <p class="bold">TERIMA KASIH ATAS KUNJUNGAN ANDA</p>
        <p>Silakan datang kembali!</p>
    </div>

</body>
</html>
