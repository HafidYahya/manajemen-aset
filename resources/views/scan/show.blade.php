<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Aset - {{ $aset->nama }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --light-color: #ecf0f1;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f7fa;
            padding: 0;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 30px;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .card-header h2 {
            font-weight: 500;
            margin: 0;
        }
        
        .card-body {
            padding: 25px;
            display: flex;
            flex-direction: column;
        }
        
        @media (min-width: 768px) {
            .card-body {
                flex-direction: row;
            }
        }
        
        .asset-image {
            flex: 1;
            padding: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--light-color);
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        @media (min-width: 768px) {
            .asset-image {
                margin-bottom: 0;
                margin-right: 25px;
            }
        }
        
        .asset-image img {
            max-width: 100%;
            height: auto;
            max-height: 300px;
            border-radius: 5px;
            object-fit: cover;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }
        
        .asset-info {
            flex: 2;
        }
        
        .info-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .info-label {
            font-weight: 500;
            color: var(--secondary-color);
            display: block;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 1.1em;
        }
        
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 500;
            text-transform: capitalize;
        }
        
        .status-active {
            background-color: rgba(46, 204, 113, 0.2);
            color: var(--success-color);
        }
        
        .status-inactive {
            background-color: rgba(231, 76, 60, 0.2);
            color: var(--danger-color);
        }
        
        .status-maintenance {
            background-color: rgba(243, 156, 18, 0.2);
            color: var(--warning-color);
        }
        
        .qr-section {
            margin-top: 30px;
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .qr-placeholder {
            width: 150px;
            height: 150px;
            margin: 0 auto 15px;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
            border-radius: 5px;
        }
        
        footer {
            text-align: center;
            margin-top: 30px;
            color: #7f8c8d;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Informasi Detail Aset</h2>
            </div>
            
            <div class="card-body">
                <div class="asset-image">
                    <img src="{{ asset('storage/' . $aset->foto) }}" alt="Foto Aset {{ $aset->nama }}">
                </div>
                
                <div class="asset-info">
                    <div class="info-item">
                        <span class="info-label">Kode Aset</span>
                        <span class="info-value">{{ $aset->kode }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Nama Aset</span>
                        <span class="info-value">{{ $aset->nama }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Lokasi</span>
                        <span class="info-value">{{ $aset->lokasi->ruangan ?? 'Belum ditentukan' }}</span>
                    </div>
                    
                    <div class="info-item">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            <span class="status status-{{ strtolower($aset->status) }}">
                                {{ ucfirst($aset->status) }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="qr-section">
                <h3>Scan QR Code untuk info aset</h3>
                <div class="qr-placeholder">
                    [QR Code]
                </div>
                <p>Gunakan aplikasi pemindai QR untuk melihat informasi ini di perangkat mobile</p>
            </div>
        </div>
        
        <footer>
            &copy; {{ date('Y') }} Sistem Manajemen Aset. Terakhir diperbarui: {{ now()->format('d M Y H:i') }}
        </footer>
    </div>
</body>
</html>