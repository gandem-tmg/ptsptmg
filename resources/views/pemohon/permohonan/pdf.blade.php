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
        .header p {
            margin: 2px 0;
            font-size: 9px;
        }
        .header img {
            width: 100%;
            margin-bottom: 3px;
        }
        .header img.kop-surat {
            width: 100%;
            height: auto;
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
        .lampiran-list {
            margin-top: 2px;
        }
        .lampiran-list li {
            margin-bottom: 1px;
            font-size: 7px;
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
        <img src="{{ public_path('KOP_SURAT.PNG') }}" alt="Kop Surat" class="kop-surat" style="margin-bottom: 5px;">
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

        @if($permohonan->user && $permohonan->user->email)
        <div class="field">
            <span class="label">Email:</span>
            <span class="value">{{ $permohonan->user->email }}</span>
        </div>
        @endif

        @if(!$permohonan->user)
        <div class="field">
            <span class="label">Alamat:</span>
            <span class="value">{{ $permohonan->alamat }}</span>
        </div>

        <div class="field">
            <span class="label">NIK:</span>
            <span class="value">{{ $permohonan->nik }}</span>
        </div>

        <div class="field">
            <span class="label">No. HP:</span>
            <span class="value">{{ $permohonan->no_hp }}</span>
        </div>
        @endif

        <div class="field">
            <span class="label">Layanan:</span>
            <span class="value">{{ $permohonan->layanan->nama_layanan }}</span>
        </div>

        <div class="field">
            <span class="label">Unit Kerja:</span>
            <span class="value">{{ $permohonan->unit_kerja }}</span>
        </div>

        @if($permohonan->deskripsi)
        <div class="field">
            <span class="label">Deskripsi:</span>
            <span class="value">{{ $permohonan->deskripsi }}</span>
        </div>
        @endif

        <div class="field">
            <span class="label">Tanggal Pengajuan:</span>
            <span class="value">{{ $permohonan->tanggal_pengajuan->format('d-m-Y') }}</span>
        </div>

        <div class="field">
            <span class="label">Status:</span>
            <span class="value">{{ ucfirst($permohonan->status) }}</span>
        </div>

        <div class="field">
            <span class="label">Tanggal Dibuat:</span>
            <span class="value">{{ $permohonan->created_at->format('d-m-Y H:i:s') }}</span>
        </div>

        @if($permohonan->lampiranPermohonan->count() > 0)
        <div class="field">
            <span class="label">Lampiran:</span>
            <div class="value">
                <ul class="lampiran-list">
                    @foreach($permohonan->lampiranPermohonan as $lampiran)
                    <li>{{ $lampiran->persyaratan->nama_persyaratan }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>

    <div class="footer">
        <img src="{{ public_path('QR_CODE.PNG') }}" alt="QR Code" class="qr-code">
        <p>Dokumen ini merupakan bukti resmi pengajuan permohonan</p>
        <p>Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>
