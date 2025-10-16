<!DOCTYPE html>
<html>
<head>
    <title>Bukti Pengajuan Permohonan</title>
    <style>
        body {
            font-family: 'Times New Roman', 'DejaVu Serif', serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .content {
            margin: 20px 0;
        }
        .field {
            margin-bottom: 15px;
            clear: both;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
            vertical-align: top;
        }
        .value {
            display: inline-block;
            max-width: calc(100% - 160px);
            word-wrap: break-word;
        }
        .lampiran-list {
            margin-top: 10px;
        }
        .lampiran-list li {
            margin-bottom: 5px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BUKTI PENGAJUAN PERMOHONAN</h1>
        <p>KEMENTERIAN AGAMA KABUPATEN TEMANGGUNG</p>
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
        <p>Dokumen ini merupakan bukti resmi pengajuan permohonan</p>
        <p>Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>
