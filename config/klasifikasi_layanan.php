<?php

/**
 * Sumber kebenaran tunggal untuk navigasi publik berbasis kebutuhan.
 *
 * Ini murni untuk halaman PUBLIK (browse, wizard, search). Struktur
 * organisasi (tabel `seksi`) tetap terpisah dan tetap dipakai untuk
 * disposisi/alur kerja internal — lihat app/Models/Seksi.php.
 *
 * Kalau nanti nambah/ubah kategori atau persona, cukup ubah di sini —
 * seeder (database/seeders/data/layanan_klasifikasi.php), controller,
 * dan view semuanya narik dari config ini, gak ada yang di-hardcode ganda.
 *
 * CATATAN DESAIN: sengaja TIDAK ada kategori "Keagamaan Non-Islam" atau
 * sejenisnya. Layanan untuk umat Katolik & Buddha dilebur ke kategori
 * FUNGSIONAL yang sama dengan layanan Islam yang sejenis (bantuan rumah
 * ibadah/lembaga masuk "Keagamaan & Rumah Ibadah" bareng bantuan masjid,
 * bantuan guru masuk "Guru & Pendidik" bareng TPG guru agama Islam, dst).
 * Ini konsisten dengan layanan Islam yang juga TIDAK diberi label
 * "Keagamaan Islam" di kategorinya — semua agama diperlakukan setara,
 * dikelompokkan berdasarkan jenis kebutuhan, bukan berdasarkan agama.
 * Nama resmi tiap layanan (mis. "...(Buddha)") tetap apa adanya sesuai SK,
 * hanya KATEGORI PENGELOMPOKAN-nya yang tidak lagi memisahkan per agama.
 */

return [

    /**
     * 7 kategori kebutuhan yang tampil sebagai navigasi utama di halaman
     * depan. Urutan array = urutan tampil. Tidak ada field "icon" (dihapus
     * atas permintaan: emoji dirasa kurang resmi untuk portal instansi
     * pemerintah) — tampilan murni teks.
     */
    'kategori' => [
        'pendidikan_madrasah' => [
            'label' => 'Pendidikan Madrasah',
            'deskripsi' => 'Urusan madrasah (MI/MTs/MA): siswa, izin operasional, data pendidikan, dan legalisir dokumen.',
            'subkategori' => [],
        ],
        'pendidikan_pesantren_diniyah' => [
            'label' => 'Pendidikan Pesantren & Diniyah',
            'deskripsi' => 'Urusan pondok pesantren, madrasah diniyah takmiliyah (MDT), lembaga pendidikan Al-Qur\'an (LPQ), dan lembaga pendidikan keagamaan non-formal lainnya.',
            'subkategori' => [],
        ],
        'guru_pendidik' => [
            'label' => 'Guru & Pendidik',
            'deskripsi' => 'Urusan guru dan tenaga pendidik agama: mutasi, tunjangan, dan layanan terkait pendidik.',
            'subkategori' => [],
        ],
        'keagamaan' => [
            'label' => 'Keagamaan & Rumah Ibadah',
            'deskripsi' => 'Urusan rumah ibadah, izin & bantuan lembaga keagamaan, arah kiblat, majelis taklim, zakat, wakaf, dan layanan keagamaan lainnya.',
            'subkategori' => [],
        ],
        'perkawinan_keluarga' => [
            'label' => 'Perkawinan & Keluarga',
            'deskripsi' => 'Konsultasi perkawinan, bimbingan calon pengantin, dan layanan terkait.',
            'subkategori' => [],
        ],
        'kepegawaian' => [
            'label' => 'Kepegawaian',
            'deskripsi' => 'Kenaikan pangkat, pensiun, cuti, tugas belajar, penghargaan, dan layanan kepegawaian ASN.',
            'subkategori' => [],
        ],
        'dokumen_administrasi' => [
            'label' => 'Dokumen & Administrasi',
            'deskripsi' => 'Legalisir, permohonan data, izin penelitian, PKL/magang, advokasi hukum, dan pengaduan.',
            'subkategori' => [],
        ],
    ],

    /**
     * Tag persona untuk fitur wizard "Tidak tahu harus pilih layanan apa?".
     * 1 layanan boleh punya lebih dari 1 tag (lihat kolom target_pengguna,
     * tersimpan sebagai JSON array). Tidak ada field "icon" (lihat catatan
     * di atas kategori).
     *
     * Sama seperti kategori: tidak ada tag persona "non-Islam". Pengurus
     * lembaga Katolik & Buddha diberi tag sendiri-sendiri yang eksplisit
     * (`pengurus_katolik`, `pengurus_buddha`), setara dengan tag persona
     * lain (bukan digabung jadi satu label "selain Islam").
     */
    'target_pengguna' => [
        'wali_siswa' => ['label' => 'Wali Murid / Siswa / Alumni Madrasah'],
        'pengurus_madrasah_ponpes' => ['label' => 'Pengurus Madrasah / Ponpes / MDT / LPQ'],
        'guru_pendidik' => ['label' => 'Guru / Tenaga Pendidik Agama'],
        'pengurus_masjid' => ['label' => 'Pengurus Masjid / Majelis Taklim / Zakat & Wakaf'],
        'calon_pengantin' => ['label' => 'Calon Pengantin / Remaja'],
        'asn_kemenag' => ['label' => 'Pegawai Kemenag (ASN)'],
        'pengurus_katolik' => ['label' => 'Pengurus/Umat Lembaga Katolik'],
        'pengurus_buddha' => ['label' => 'Pengurus/Umat Lembaga Buddha'],
        'masyarakat_umum' => ['label' => 'Masyarakat Umum Lainnya'],
    ],

    /**
     * Badge "bentuk proses" di kartu layanan — bukan kategori navigasi,
     * cuma label tambahan (lihat diskusi: Bantuan/Konsultasi/Data lintas
     * domain, gak layak jadi kotak navigasi sendiri).
     */
    'jenis_layanan' => [
        'izin' => 'Izin',
        'rekomendasi' => 'Rekomendasi',
        'bantuan' => 'Bantuan',
        'konsultasi' => 'Konsultasi',
        'data' => 'Data & Informasi',
        'legalisir' => 'Legalisir',
        'kepegawaian' => 'Administrasi Kepegawaian',
        'pengaduan' => 'Pengaduan',
        'permintaan' => 'Permintaan Petugas',
        'pengukuran' => 'Pengukuran / Survei Teknis',
    ],

];
