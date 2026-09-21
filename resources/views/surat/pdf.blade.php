<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; color: #111; }
        .kop { text-align: center; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 20px; }
        .kop img { height: 60px; float: left; }
        .kop h1 { font-size: 14pt; margin: 0; }
        .kop p { font-size: 10pt; margin: 0; }
        .nomor { margin-bottom: 20px; }
        .isi { text-align: justify; line-height: 1.6; margin-bottom: 40px; }
        .ttd-block { margin-top: 40px; width: 260px; float: right; text-align: center; }
        .ttd-block .qr { margin: 8px auto; }
        .ttd-elektronik { border: 1px solid #16a34a; border-radius: 6px; padding: 10px; font-size: 9pt; color: #166534; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="kop">
        <h1>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h1>
        <h1>KANTOR KEMENTERIAN AGAMA KABUPATEN TEMANGGUNG</h1>
        <p>Jl. Contoh Alamat No. 1, Temanggung, Jawa Tengah</p>
    </div>

    <div class="nomor">
        <strong>Nomor:</strong> {{ $surat->nomor_surat }}
    </div>

    <div class="isi">
        {!! nl2br($surat->isi_surat) !!}
    </div>

    <div class="ttd-block">
        @if($ttd)
            <p>Temanggung, {{ now()->translatedFormat('d F Y') }}</p>
            <div class="ttd-elektronik">
                Dokumen ini ditandatangani secara elektronik oleh:<br>
                <strong>{{ $ttd['nama_penandatangan'] }}</strong><br>
                {{ $ttd['jabatan_penandatangan'] }}<br>
                pada {{ $surat->tanggal_ttd?->translatedFormat('d F Y, H:i') ?? now()->translatedFormat('d F Y, H:i') }} WIB
            </div>
            @if($qrSvgMarkup)
            <div class="qr">
                {!! $qrSvgMarkup !!}
                <p style="font-size: 8pt;">Scan untuk verifikasi keaslian</p>
            </div>
            @endif
        @else
            <p style="color: #999; font-style: italic;">-- DRAFT, belum ditandatangani --</p>
        @endif
    </div>
    <div class="clear"></div>
</body>
</html>
