<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Label QR Aset - {{ $aset->kode }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f5f7fa;
        }
        .qr-label-container {
            width: 90mm;
            height: 90mm;
            padding: 5mm;
            border: 1mm solid #e0e0e0;
            border-radius: 5mm;
            background: white;
            box-shadow: 0 2mm 4mm rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }
        .company-header {
            width: 100%;
            text-align: center;
            margin-bottom: 2mm;
            padding-bottom: 2mm;
            border-bottom: 0.3mm solid #f0f0f0;
        }
        .company-name {
            font-weight: bold;
            font-size: 10pt;
            color: #2c3e50;
            margin: 0;
            line-height: 1.2;
        }
        .company-description {
            font-size: 8pt;
            color: #7f8c8d;
            margin: 1mm 0 0 0;
            font-style: italic;
        }
        .qr-container {
            width: 60mm;
            height: 60mm;
            border: 0.5mm solid #f0f0f0;
            padding: 1mm;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
        }
        .qr-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .asset-info {
            width: 100%;
            text-align: center;
        }
        .asset-code {
            font-weight: bold;
            font-size: 14pt;
            color: #e74c3c;
            margin: 1mm 0;
            letter-spacing: 0.5pt;
        }
        .asset-name {
            font-size: 10pt;
            color: #34495e;
            margin: 1mm 0;
            line-height: 1.2;
        }
        .footer {
            width: 100%;
            font-size: 8pt;
            color: #95a5a6;
            padding: 2mm 0;
            border-top: 0.3mm dashed #ecf0f1;
            text-align: center; /* Untuk membuat teks footer di tengah */
            margin-top: auto; /* Untuk mendorong footer ke bawah */
            padding-bottom: 1mm; /* Padding bottom yang sesuai */
        }
        .footer-content {
            display: inline-block; /* Untuk membuat konten footer bisa diatur jaraknya */
        }
        .scan-info {
            color: #3498db;
            font-weight: bold;
        }
        .print-date {
            font-style: italic;
            margin-left: 5mm; /* Jarak antara teks scan dan tanggal */
        }
        
        /* Print specific styles */
        @media print {
            body {
                background: none;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                height: auto;
                display: block;
            }
            .qr-label-container {
                box-shadow: none;
                margin: 10mm auto;
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
    <div class="qr-label-container">
        <div class="company-header">
            <p class="company-name">PT. DATANEX INOVASI PRATAMA</p>
            <p class="company-description">IT Solutions Provider Company</p>
        </div>
        
        <div class="qr-container">
            <img src="{{ public_path('storage/' . $aset->qr_code) }}" alt="QR Code Aset">
        </div>
        
        <div class="asset-info">
            <p class="asset-code">{{ $aset->kode }}</p>
            <p class="asset-name">{{ $aset->nama ?? 'Nama Aset' }}</p>
        </div>
        
        <div class="footer">
            <div class="footer-content">
                <span class="scan-info">SCAN QR UNTUK VERIFIKASI</span>
                <span class="print-date">{{ date('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>
</body>
</html>