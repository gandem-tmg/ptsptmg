<!DOCTYPE html>
<html>
<head>
    <title>Bukti Pengajuan Permohonan</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 2mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.1;
            margin: 0;
            padding: 2mm;
            height: auto;
            page-break-inside: avoid;
        }
        .header {
            text-align: center;
            margin-bottom: 5px;
        }
        .header h1 {
            margin: 0;
            font-size: 11px;
            font-weight: bold;
        }
        .header img.kop-surat {
            width: 100%;
            height: auto;
            margin-bottom: 5px;
        }
        .content {
            margin: 3px 0;
        }
        .field {
            margin-bottom: 3px;
            clear: both;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 22mm;
            vertical-align: top;
            font-size: 10px;
        }
        .value {
            display: inline-block;
            max-width: calc(100% - 25mm);
            word-wrap: break-word;
            font-size: 10px;
        }
        .cek-status {
            margin-top: 6px;
            padding-top: 5px;
            border-top: 1px dashed #999;
            font-size: 8px;
            text-align: center;
        }
        .footer {
            margin-top: 5px;
            text-align: center;
            font-size: 7px;
            color: #666;
        }
        .footer img {
            width: 50mm;
            height: auto;
            margin-bottom: 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('KOP_SURAT.PNG') }}" alt="Kop Surat" class="kop-surat">
        <h1>BUKTI PENGAJUAN PERMOHONAN</h1>
    </div>

    <div class="content">
        <div class="field">
            <span class="label">No. Tiket:</span>
            <span class="value">{{ $permohonan->no_tiket }}</span>
        </div>

        <div class="field">
            <span class="label">Nama Pemohon:</span>
            <span class="value">{{ $permohonan->user ? $permohonan->user->name : $permohonan->nama }}</span>
        </div>

        <div class="field">
            <span class="label">No. HP:</span>
            <span class="value">{{ ($permohonan->user->no_hp ?? null) ?: ($permohonan->no_hp ?: '-') }}</span>
        </div>

        <div class="field">
            <span class="label">Layanan:</span>
            <span class="value">{{ $permohonan->nama_layanan_label }}</span>
        </div>

        <div class="field">
            <span class="label">Seksi Tujuan:</span>
            <span class="value">{{ $permohonan->seksi_label }}</span>
        </div>

        <div class="field">
            <span class="label">Tanggal:</span>
            <span class="value">{{ $permohonan->tanggal_pengajuan->format('d-m-Y') }}</span>
        </div>

        <div class="cek-status">
            Cek status permohonan Anda kapan saja di<br>
            <strong>{{ url('/status-permohonan') }}</strong><br>
            atau login ke akun Anda.
        </div>
    </div>

    <div class="footer">
        <img src="{{ public_path('QR_CODE.PNG') }}" alt="QR Code">
        <p>Dokumen ini merupakan bukti resmi pengajuan permohonan</p>
        <p>Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>
