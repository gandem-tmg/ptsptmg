<?php

/**
 * Peta klasifikasi kebutuhan-pengguna untuk 45 layanan, dikunci per
 * `kode_layanan` supaya independen dari LayananSeeder.php (yang isinya
 * konten SP asli: nama, seksi, persyaratan — jangan dicampur di sini).
 *
 * Kolom yang dipetakan di sini persis field baru hasil migration
 * `2026_09_18_000001_add_klasifikasi_kebutuhan_to_layanan_table`:
 *   - kategori        : wajib, harus salah satu key di config('klasifikasi_layanan.kategori')
 *   - subkategori      : opsional, harus ada di daftar subkategori kategori terkait (atau null)
 *   - target_pengguna  : array tag, tiap tag harus ada di config('klasifikasi_layanan.target_pengguna')
 *   - tag_pencarian    : string bebas, kata kunci awam dipisah koma
 *   - jenis_layanan    : harus salah satu key di config('klasifikasi_layanan.jenis_layanan')
 *
 * Kalau ada kode_layanan baru yang belum dipetakan di sini, LayananSeeder
 * akan membiarkan field-field ini null (layanan tetap tersimpan, tapi
 * TIDAK akan muncul di grid kategori / wizard sampai dipetakan lewat
 * panel admin atau ditambahkan ke sini).
 */

return [

    // === Sub Bagian Tata Usaha ===
    'TU-01' => [ // Pengaduan Pelayanan Publik — tetap dikasih kategori (buat
        // fallback/pencarian/konsistensi data), TAPI jenis_layanan
        // 'pengaduan' dipakai controller untuk MENGECUALIKANNYA dari grid
        // kategori dan menaruhnya sebagai tombol/link terpisah — sesuai
        // kesepakatan diskusi arsitektur.
        'kategori' => 'dokumen_administrasi',
        'subkategori' => null,
        'target_pengguna' => ['masyarakat_umum'],
        'tag_pencarian' => 'aduan, keluhan, komplain, lapor, pengaduan pelayanan',
        'jenis_layanan' => 'pengaduan',
    ],
    'TU-02' => [
        'kategori' => 'dokumen_administrasi',
        'subkategori' => null,
        'target_pengguna' => ['masyarakat_umum'],
        'tag_pencarian' => 'izin penelitian, riset, skripsi, wawancara, pengambilan data, mahasiswa',
        'jenis_layanan' => 'izin',
    ],
    'TU-03' => [
        'kategori' => 'dokumen_administrasi',
        'subkategori' => null,
        'target_pengguna' => ['masyarakat_umum'],
        'tag_pencarian' => 'pkl, magang, praktik kerja lapangan, siswa, mahasiswa',
        'jenis_layanan' => 'izin',
    ],
    'TU-04' => [
        'kategori' => 'dokumen_administrasi',
        'subkategori' => null,
        'target_pengguna' => ['masyarakat_umum'],
        'tag_pencarian' => 'legalisir, cap basah, fotokopi ijazah, legalisir dokumen',
        'jenis_layanan' => 'legalisir',
    ],
    'TU-05' => [
        'kategori' => 'keagamaan',
        'subkategori' => null,
        'target_pengguna' => ['masyarakat_umum'],
        'tag_pencarian' => 'rohaniwan, pembaca doa, penceramah, permintaan petugas acara',
        'jenis_layanan' => 'permintaan',
    ],
    'TU-06' => [
        'kategori' => 'kepegawaian',
        'subkategori' => null,
        'target_pengguna' => ['asn_kemenag'],
        'tag_pencarian' => 'kenaikan pangkat, kp reguler, kp struktural, kp fungsional, pangkat',
        'jenis_layanan' => 'kepegawaian',
    ],
    'TU-07' => [
        'kategori' => 'kepegawaian',
        'subkategori' => null,
        'target_pengguna' => ['asn_kemenag'],
        'tag_pencarian' => 'pensiun, purna tugas, purna bakti',
        'jenis_layanan' => 'kepegawaian',
    ],
    'TU-08' => [
        'kategori' => 'kepegawaian',
        'subkategori' => null,
        'target_pengguna' => ['asn_kemenag'],
        'tag_pencarian' => 'cuti, izin kerja, cuti tahunan, cuti melahirkan',
        'jenis_layanan' => 'kepegawaian',
    ],
    'TU-09' => [
        'kategori' => 'kepegawaian',
        'subkategori' => null,
        'target_pengguna' => ['asn_kemenag'],
        'tag_pencarian' => 'gelar akademik, pencantuman gelar, penyesuaian ijazah',
        'jenis_layanan' => 'kepegawaian',
    ],
    'TU-10' => [
        'kategori' => 'kepegawaian',
        'subkategori' => null,
        'target_pengguna' => ['asn_kemenag'],
        'tag_pencarian' => 'satyalancana, tanda kehormatan, penghargaan pegawai',
        'jenis_layanan' => 'kepegawaian',
    ],
    'TU-11' => [
        'kategori' => 'kepegawaian',
        'subkategori' => null,
        'target_pengguna' => ['asn_kemenag'],
        'tag_pencarian' => 'tugas belajar, izin belajar, beasiswa pegawai',
        'jenis_layanan' => 'kepegawaian',
    ],
    'TU-12' => [
        'kategori' => 'dokumen_administrasi',
        'subkategori' => null,
        'target_pengguna' => ['asn_kemenag', 'masyarakat_umum'],
        'tag_pencarian' => 'advokasi hukum, bantuan hukum, konsultasi hukum',
        'jenis_layanan' => 'konsultasi',
    ],
    'TU-13' => [
        'kategori' => 'keagamaan',
        'subkategori' => null,
        'target_pengguna' => ['masyarakat_umum'],
        'tag_pencarian' => 'konflik keagamaan, sengketa antar umat, kerukunan umat beragama',
        'jenis_layanan' => 'konsultasi',
    ],

    // === Seksi Pendidikan Diniyah dan Pondok Pesantren ===
    'PDPONTREN-01' => [
        'kategori' => 'pendidikan_pesantren_diniyah',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_madrasah_ponpes'],
        'tag_pencarian' => 'bantuan madin, bantuan lpq, bantuan pondok pesantren',
        'jenis_layanan' => 'bantuan',
    ],
    'PDPONTREN-02' => [
        'kategori' => 'pendidikan_pesantren_diniyah',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_madrasah_ponpes'],
        'tag_pencarian' => 'izin lpq, lembaga pendidikan al-quran, tpq, tpa',
        'jenis_layanan' => 'izin',
    ],
    'PDPONTREN-03' => [
        'kategori' => 'pendidikan_pesantren_diniyah',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_madrasah_ponpes'],
        'tag_pencarian' => 'izin mdt, madrasah diniyah takmiliyah',
        'jenis_layanan' => 'izin',
    ],
    'PDPONTREN-04' => [
        'kategori' => 'pendidikan_pesantren_diniyah',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_madrasah_ponpes'],
        'tag_pencarian' => 'izin pondok pesantren, ponpes, izin operasional ponpes',
        'jenis_layanan' => 'izin',
    ],
    'PDPONTREN-05' => [
        'kategori' => 'pendidikan_pesantren_diniyah',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_madrasah_ponpes', 'guru_pendidik'],
        'tag_pencarian' => 'emis 4.0, verval pd, verifikasi validasi peserta didik',
        'jenis_layanan' => 'konsultasi',
    ],

    // === Seksi Pendidikan Agama Islam ===
    'PAIS-01' => [
        'kategori' => 'guru_pendidik',
        'subkategori' => null,
        'target_pengguna' => ['guru_pendidik'],
        'tag_pencarian' => 'aplikasi siaga, konsultasi siaga',
        'jenis_layanan' => 'konsultasi',
    ],
    'PAIS-02' => [
        'kategori' => 'guru_pendidik',
        'subkategori' => null,
        'target_pengguna' => ['guru_pendidik'],
        'tag_pencarian' => 'tpg, tunjangan profesi guru, sertifikasi guru agama',
        'jenis_layanan' => 'data',
    ],
    'PAIS-03' => [
        'kategori' => 'guru_pendidik',
        'subkategori' => null,
        'target_pengguna' => ['guru_pendidik'],
        'tag_pencarian' => 'mutasi guru, pindah tugas guru agama',
        'jenis_layanan' => 'rekomendasi',
    ],

    // === Seksi Pendidikan Madrasah ===
    'PENMA-01' => [
        'kategori' => 'pendidikan_madrasah',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_madrasah_ponpes'],
        'tag_pencarian' => 'pendirian madrasah, izin madrasah baru',
        'jenis_layanan' => 'izin',
    ],
    'PENMA-02' => [
        'kategori' => 'pendidikan_madrasah',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_madrasah_ponpes'],
        'tag_pencarian' => 'ubah izin madrasah, ganti izin operasional, izin rusak',
        'jenis_layanan' => 'izin',
    ],
    'PENMA-03' => [
        'kategori' => 'pendidikan_madrasah',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_madrasah_ponpes'],
        'tag_pencarian' => 'penutupan madrasah, tutup madrasah',
        'jenis_layanan' => 'izin',
    ],
    'PENMA-04' => [
        'kategori' => 'pendidikan_madrasah',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_madrasah_ponpes'],
        'tag_pencarian' => 'kepala madrasah, pengangkatan kepala sekolah swasta',
        'jenis_layanan' => 'rekomendasi',
    ],
    'PENMA-05' => [
        'kategori' => 'pendidikan_madrasah',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_madrasah_ponpes'],
        'tag_pencarian' => 'bantuan madrasah, bantuan sarana madrasah',
        'jenis_layanan' => 'bantuan',
    ],
    'PENMA-06' => [
        'kategori' => 'pendidikan_madrasah',
        'subkategori' => null,
        'target_pengguna' => ['wali_siswa'],
        'tag_pencarian' => 'ijazah hilang, ijazah rusak, surat keterangan pengganti ijazah',
        'jenis_layanan' => 'legalisir',
    ],
    'PENMA-07' => [
        'kategori' => 'pendidikan_madrasah',
        'subkategori' => null,
        'target_pengguna' => ['wali_siswa'],
        'tag_pencarian' => 'legalisir ijazah madrasah, legalisir rapor madrasah',
        'jenis_layanan' => 'legalisir',
    ],
    'PENMA-08' => [
        'kategori' => 'pendidikan_madrasah',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_madrasah_ponpes'],
        'tag_pencarian' => 'data siswa madrasah, dapodik madrasah, emis siswa',
        'jenis_layanan' => 'data',
    ],
    'PENMA-09' => [
        'kategori' => 'pendidikan_madrasah',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_madrasah_ponpes', 'wali_siswa'],
        'tag_pencarian' => 'konsultasi pendidikan madrasah, tanya jawab madrasah',
        'jenis_layanan' => 'konsultasi',
    ],

    // === Seksi Bimbingan Masyarakat Islam ===
    'BIMAS_ISLAM-01' => [
        'kategori' => 'dokumen_administrasi',
        'subkategori' => null,
        'target_pengguna' => ['masyarakat_umum'],
        'tag_pencarian' => 'data keagamaan, statistik keagamaan islam',
        'jenis_layanan' => 'data',
    ],
    'BIMAS_ISLAM-02' => [
        'kategori' => 'keagamaan',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_masjid'],
        'tag_pencarian' => 'majelis taklim, pendaftaran majelis taklim, perpanjangan izin majelis taklim',
        'jenis_layanan' => 'rekomendasi',
    ],
    'BIMAS_ISLAM-03' => [
        'kategori' => 'keagamaan',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_masjid'],
        'tag_pencarian' => 'bantuan masjid, bantuan mushalla, dana masjid',
        'jenis_layanan' => 'bantuan',
    ],
    'BIMAS_ISLAM-04' => [
        'kategori' => 'keagamaan',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_masjid'],
        'tag_pencarian' => 'arah kiblat, ukur kiblat, kiblat masjid',
        'jenis_layanan' => 'pengukuran',
    ],
    'BIMAS_ISLAM-05' => [
        'kategori' => 'perkawinan_keluarga',
        'subkategori' => null,
        'target_pengguna' => ['calon_pengantin'],
        'tag_pencarian' => 'konsultasi perkawinan, masalah rumah tangga, bp4',
        'jenis_layanan' => 'konsultasi',
    ],
    'BIMAS_ISLAM-06' => [
        'kategori' => 'perkawinan_keluarga',
        'subkategori' => null,
        'target_pengguna' => ['calon_pengantin'],
        'tag_pencarian' => 'catin, bimbingan pranikah, kursus calon pengantin, suscatin',
        'jenis_layanan' => 'konsultasi',
    ],
    'BIMAS_ISLAM-07' => [
        'kategori' => 'perkawinan_keluarga',
        'subkategori' => null,
        'target_pengguna' => ['calon_pengantin'],
        'tag_pencarian' => 'brus, bimbingan remaja usia sekolah, pendidikan pranikah remaja',
        'jenis_layanan' => 'konsultasi',
    ],

    // === Penyelenggara Zakat Wakaf ===
    'ZAWA-01' => [
        'kategori' => 'keagamaan',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_masjid'],
        'tag_pencarian' => 'laz, lembaga amil zakat, simzat, izin kantor zakat',
        'jenis_layanan' => 'izin',
    ],
    'ZAWA-02' => [
        'kategori' => 'keagamaan',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_masjid'],
        'tag_pencarian' => 'ruislag, tukar guling tanah wakaf, harta benda wakaf',
        'jenis_layanan' => 'rekomendasi',
    ],
    'ZAWA-03' => [
        'kategori' => 'keagamaan',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_masjid'],
        'tag_pencarian' => 'akta ikrar wakaf, aiw, apaiw, siwak, sertifikasi tanah wakaf',
        'jenis_layanan' => 'data',
    ],

    // === Penyelenggara Katolik ===
    // Sengaja dilebur ke kategori FUNGSIONAL yang sama dengan layanan
    // Islam sejenis (bukan dikelompokkan sebagai "layanan agama lain") —
    // lihat catatan desain di config/klasifikasi_layanan.php.
    'KATOLIK-01' => [
        'kategori' => 'keagamaan',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_katolik'],
        'tag_pencarian' => 'bantuan sarana katolik, bantuan gereja katolik',
        'jenis_layanan' => 'bantuan',
    ],
    'KATOLIK-02' => [
        'kategori' => 'guru_pendidik',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_katolik', 'guru_pendidik'],
        'tag_pencarian' => 'bantuan guru katolik, insentif guru agama katolik',
        'jenis_layanan' => 'bantuan',
    ],

    // === Penyelenggara Buddha ===
    'BUDDHA-01' => [
        'kategori' => 'keagamaan',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_buddha'],
        'tag_pencarian' => 'bantuan buddha, bantuan vihara',
        'jenis_layanan' => 'bantuan',
    ],
    'BUDDHA-02' => [
        // Masuk kategori "Pendidikan Pesantren & Diniyah" (bukan "Pendidikan
        // Madrasah") karena sifatnya lembaga pendidikan keagamaan non-formal,
        // padanan fungsional dengan ponpes/MDT/LPQ — bukan sekolah formal
        // berjenjang (MI/MTs/MA) seperti isi kategori Madrasah.
        'kategori' => 'pendidikan_pesantren_diniyah',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_buddha'],
        'tag_pencarian' => 'izin lembaga pendidikan keagamaan buddha',
        'jenis_layanan' => 'izin',
    ],
    'BUDDHA-03' => [
        'kategori' => 'keagamaan',
        'subkategori' => null,
        'target_pengguna' => ['pengurus_buddha'],
        'tag_pencarian' => 'tanda daftar rumah ibadah buddha, tanda daftar organisasi keagamaan buddha, vihara',
        'jenis_layanan' => 'data',
    ],

];
