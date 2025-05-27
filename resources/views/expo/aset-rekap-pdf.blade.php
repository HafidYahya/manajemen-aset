<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rekap Aset</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #333;
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .subheader {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #888;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-size: 11px;
        }

        tr:nth-child(even) td {
            background-color: #fcfcfc;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            position: fixed;
            bottom: -10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 10px;">
        <img src="{{ public_path('images/company_logo_datanex_light.png') }}" alt="Logo Perusahaan" width="500">
    </div>

    <h2>LAPORAN REKAP ASET</h2>
    <div class="subheader">Sistem Manajemen Aset Kantor</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Aset</th>
                <th>Lokasi</th>
                <th>Jumlah</th>
                <th>Total Nilai</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ optional($item->lokasi)->ruangan }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($item->status_utama) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ \Carbon\Carbon::now()->format('d F Y H:i') }}
    </div>
</body>
</html>
