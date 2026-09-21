<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 10.5pt; color: #1e293b; }
        .kop { text-align: center; border-bottom: 2px solid #0f766e; padding-bottom: 10px; margin-bottom: 18px; }
        .kop h1 { font-size: 13pt; margin: 0; color: #0f766e; }
        .kop p { font-size: 9pt; margin: 2px 0 0; color: #475569; }
        h2.judul { text-align: center; font-size: 12pt; margin: 0 0 2px; }
        p.periode { text-align: center; font-size: 10pt; color: #475569; margin: 0 0 18px; }

        .ikm-box { border: 1px solid #cbd5e1; border-radius: 6px; padding: 14px 18px; margin-bottom: 18px; }
        .ikm-nilai { font-size: 28pt; font-weight: bold; color: #0f172a; }
        .ikm-kategori { display: inline-block; margin-left: 10px; padding: 3px 10px; border-radius: 12px; font-size: 9pt; font-weight: bold; }
        .kat-sangat-baik { background: #d1fae5; color: #047857; }
        .kat-baik { background: #e0f2fe; color: #0369a1; }
        .kat-kurang-baik { background: #fef3c7; color: #b45309; }
        .kat-tidak-baik { background: #fee2e2; color: #b91c1c; }
        .ikm-ket { font-size: 8.5pt; color: #64748b; margin-top: 4px; }

        h3.section { font-size: 10.5pt; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; margin: 18px 0 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        th, td { border: 1px solid #e2e8f0; padding: 5px 8px; font-size: 9.5pt; text-align: left; }
        th { background: #f1f5f9; color: #334155; }
        td.angka { text-align: right; }
        .kosong { color: #94a3b8; font-style: italic; font-size: 9.5pt; }
        .footer-note { margin-top: 22px; font-size: 8pt; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="kop">
        <h1>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h1>
        <p>Kantor Kementerian Agama Kabupaten Temanggung — Pelayanan Terpadu Satu Pintu (PTSP)</p>
    </div>

    <h2 class="judul">Laporan Hasil Survei Kepuasan Masyarakat (SKM)</h2>
    <p class="periode">Periode: {{ $periodeLabel }} &middot; Dicetak {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>

    <div class="ikm-box">
        @if($nilaiIkm)
            @php
                $katClass = match($nilaiIkm['kategori']) {
                    'Sangat Baik' => 'kat-sangat-baik',
                    'Baik' => 'kat-baik',
                    'Kurang Baik' => 'kat-kurang-baik',
                    default => 'kat-tidak-baik',
                };
            @endphp
            <span class="ikm-nilai">{{ $nilaiIkm['nilai'] }}</span>
            <span class="ikm-kategori {{ $katClass }}">{{ $nilaiIkm['kategori'] }}</span>
            <p class="ikm-ket">Nilai IKM skala 0–100, dari {{ $nilaiIkm['jumlah_jawaban'] }} jawaban &middot; mengikuti Permenpan RB No. 14/2017</p>
        @else
            <p class="kosong">Belum ada data survei untuk periode ini.</p>
        @endif
    </div>

    <h3 class="section">Sebaran Penilaian</h3>
    @if(array_sum($distribusiSkala) === 0)
        <p class="kosong">Belum ada jawaban skala masuk.</p>
    @else
        <table>
            <tr><th>Kategori</th><th class="angka">Jumlah</th><th class="angka">Persentase</th></tr>
            @php $totalSkala = array_sum($distribusiSkala); @endphp
            @foreach($distribusiSkala as $label => $jumlah)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="angka">{{ $jumlah }}</td>
                    <td class="angka">{{ $totalSkala > 0 ? round($jumlah / $totalSkala * 100) : 0 }}%</td>
                </tr>
            @endforeach
        </table>
    @endif

    <h3 class="section">Rata-rata per Pertanyaan</h3>
    @if($rataRataPerPertanyaan->isEmpty())
        <p class="kosong">Belum ada pertanyaan tipe skala 1-4.</p>
    @else
        <table>
            <tr><th>Pertanyaan</th><th class="angka">Jumlah Jawaban</th><th class="angka">Rata-rata (skala 1-4)</th></tr>
            @foreach($rataRataPerPertanyaan as $row)
                <tr>
                    <td>{{ $row->pertanyaan }}</td>
                    <td class="angka">{{ $row->jumlah_jawaban }}</td>
                    <td class="angka">{{ $row->rata_rata ?? '-' }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    <h3 class="section">Rata-rata per Layanan</h3>
    @if($rataRataPerLayanan->isEmpty())
        <p class="kosong">Belum ada data survei per-layanan untuk periode ini.</p>
    @else
        <table>
            <tr><th>Layanan</th><th class="angka">Jumlah Jawaban</th><th class="angka">Rata-rata (skala 1-4)</th></tr>
            @foreach($rataRataPerLayanan as $row)
                <tr>
                    <td>{{ $row->nama_layanan }}</td>
                    <td class="angka">{{ $row->jumlah_jawaban }}</td>
                    <td class="angka">{{ $row->rata_rata }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    <h3 class="section">Rata-rata per Seksi</h3>
    @if($rataRataPerSeksi->isEmpty())
        <p class="kosong">Belum ada data survei per-layanan untuk periode ini.</p>
    @else
        <table>
            <tr><th>Seksi</th><th class="angka">Jumlah Jawaban</th><th class="angka">Rata-rata (skala 1-4)</th></tr>
            @foreach($rataRataPerSeksi as $row)
                <tr>
                    <td>{{ $row->nama_seksi }}</td>
                    <td class="angka">{{ $row->jumlah_jawaban }}</td>
                    <td class="angka">{{ $row->rata_rata }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    <p class="footer-note">Laporan ini dibuat otomatis oleh sistem PTSP Online Kemenag Kab. Temanggung.</p>
</body>
</html>
