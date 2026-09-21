<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\Persyaratan;
use App\Models\Seksi;
use Illuminate\Database\Seeder;

/**
 * 45 layanan riil hasil ekstraksi dari dokumen
 * "Standar Pelayanan Kemenag Kab. Temanggung Tahun 2026"
 * (nama layanan, seksi penanggung jawab, dan persyaratan
 * diambil langsung dari isi dokumen resmi).
 *
 * CATATAN: kolom `tipe_pelaksanaan` diisi otomatis berdasarkan kata kunci
 * (mis. "legalisir" -> perlu_fisik, "aplikasi emis" -> sistem_eksternal).
 * Ini TEBAKAN AWAL, bukan keputusan final -- silakan koreksi lewat
 * panel admin (Layanan > Edit) sesuai hasil klasifikasi bro sendiri.
 */
class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $seksi = Seksi::pluck('id', 'kode_seksi');

        // Lapisan klasifikasi kebutuhan-pengguna (kategori, subkategori,
        // target_pengguna, tag_pencarian, jenis_layanan) — file terpisah
        // dari data SP asli ($data di bawah), supaya penataan navigasi
        // publik bisa dikelola sendiri tanpa mengutak-atik isi SP.
        $klasifikasi = require __DIR__ . '/data/layanan_klasifikasi.php';

        $data = [
            [
                'kode_layanan' => 'TU-01',
                'nama_layanan' => 'Pengaduan Pelayanan Publik',
                'seksi_kode' => 'TU',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Aduan sekurang-kurangnya berisi:',
                    'penjelasan detail terkait pengaduan pelayanan publik yang disampaikan oleh masyarakat/instansi terkait pelayanan publik di lingkungan Kantor Kementerian Agama Kabupaten Temanggung',
                    'bukti dukung pengaduan',
                    'identitas pemohon;',
                    'nomor kontak personal yang dapat dihubungi',
                    'Pelayanan publik yang diadukan masih dalam ranah kewenangan Kantor Kementerian Agama Kabupaten Temanggung'
                ],
            ],
            [
                'kode_layanan' => 'TU-02',
                'nama_layanan' => 'Permohonan Izin Penelitian (Wawancara/ Pengambilan Data Penelitian)',
                'seksi_kode' => 'TU',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat permohonan ijin penelitian yang ditujukan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung bercap dan ditandatangani'
                ],
            ],
            [
                'kode_layanan' => 'TU-03',
                'nama_layanan' => 'Permohonan Praktek Kerja Lapangan/Magang',
                'seksi_kode' => 'TU',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat permohonan ijin PKL/magang yang ditujukan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung dan ditandatangani',
                    'Untuk permohonan PKL/Magang dapat melampirkan periode PKL/Magang beserta jumlah anggotanya'
                ],
            ],
            [
                'kode_layanan' => 'TU-04',
                'nama_layanan' => 'Permohonan Legalisir Dokumen',
                'seksi_kode' => 'TU',
                'tipe_pelaksanaan' => 'perlu_fisik',
                'persyaratan' => [
                    'Dokumen Asli;',
                    'Fotocopy Dokumen yang akan di legalisir'
                ],
            ],
            [
                'kode_layanan' => 'TU-05',
                'nama_layanan' => 'Permohonan Rohaniwan Dan Pembaca Doa',
                'seksi_kode' => 'TU',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat permohonan rohaniwan yang ditujukan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung bercap dan ditandatangani;',
                    'Mencantumkan jadwal dan petugas rohaniwan untuk agama Islam/ Kristen/ Katolik/ Buddha/ Hindu'
                ],
            ],
            [
                'kode_layanan' => 'TU-06',
                'nama_layanan' => 'Pengajuan Kenaikan Pangkat',
                'seksi_kode' => 'TU',
                'tipe_pelaksanaan' => 'sistem_eksternal',
                'persyaratan' => [
                    'KP Regular',
                    'Persyaratan :',
                    'Sudah 4 tahun dalam pangkat terakhir',
                    'SK Kenaikan Pangkat terakhir',
                    'SK Jabatan terakhir',
                    'SKP 2 tahun terakhir dengan predikat min baik',
                    'Bagi yang naik pangkat dari gol I ke gol II, gol II ke gol III, dan gol III ke IV dibutuhkan STL ujian dinas tingkat I dan II',
                    'Surat Pengantar (dari Kantor Kemenag)',
                    'KP Struktural',
                    'Persyaratan :',
                    'Sudah 4 tahun dalam pangkat terakhir',
                    'SK Kenaikan Pangkat terakhir',
                    'SK Jabatan terakhir',
                    'SKP 2 tahun terakhir dengan predikat min baik',
                    'Berita acara pelantikan lengkap',
                    'Surat Pengantar (dari Kantor Kemenag)',
                    'KP Jabatan Fungsional',
                    'Persyaratan :',
                    'SK Kenaikan Pangkat terakhir',
                    'SK Jabatan terakhir',
                    'SKP 2 tahun terakhir dengan predikat min baik',
                    'PAK (penetapan Angkat kredit) dengan nilai yang cukup untuk naik pangkat sesuai jenjang terdiri dari PAK konvensional, PAK integrasi, dan PAK konversi',
                    'Surat Pengantar (dari Kantor Kemenag)',
                    'KP PI (Penyesuaian Ijazah)',
                    'Persyaratan :',
                    'SK Kenaikan Pangkat terakhir',
                    'SK Jabatan terakhir',
                    'SKP 2 tahun terakhir dengan predikat min baik',
                    'Ijazah dan transkrip nilai pendidikan lebih tinggi',
                    'STLUPKP (Surat Tanda Lulus Ujian Penyesuaian Kenaikan Pangkat) dan uraian pekerjaan sesuai dengan jabatan yang akan diduduki',
                    'Surat Pengantar (dari Kantor Kemenag)'
                ],
            ],
            [
                'kode_layanan' => 'TU-07',
                'nama_layanan' => 'Pengajuan Pensiun',
                'seksi_kode' => 'TU',
                'tipe_pelaksanaan' => 'sistem_eksternal',
                'persyaratan' => [
                    'Pensiun BUP',
                    'Persyaratan :',
                    'Data Perorangan Calon Penerima Pensiun (DPCP) yang ditandatangani oleh PNS yang bersangkutan dan atasan langsung',
                    'SK CPNS dan SK PNS',
                    'SK Kenaikan Pangkat Terakhir',
                    'Surat Nikah Asli',
                    'KK',
                    'SKP 1 Tahun Terakhir',
                    'Surat Pernyataan Tidak Pernah Mendapat Hukuman Disiplin Sedang / Berat (dari Kantor Kemenag)',
                    'Surat Pernyataan Tidak Sedang Menjalani Hukuman Disiplin Sedang / Berat (dari Kantor Kemenag)',
                    'Surat Pengantar Pensiun (dari Kantor Kemenag)',
                    'Pensiun Janda / Duda',
                    'Persyaratan :',
                    'Data Perorangan Calon Penerima Pensiun (DPCP) yang ditandatangani oleh PNS yang bersangkutan dan atasan langsung',
                    'SK CPNS dan SK PNS',
                    'SK Kenaikan Pangkat Terakhir',
                    'Surat Nikah Asli',
                    'KK',
                    'SKP 1 Tahun Terakhir',
                    'Akta Kematian',
                    'Surat Keterangan Janda / Duda dari Kelurahan',
                    'Surat Pernyataan Tidak Pernah Mendapat Hukuman Disiplin Sedang / Berat (dari Kantor Kemenag)',
                    'Surat Pernyataan Tidak Sedang Menjalani Hukuman Disiplin Sedang / Berat (dari Kantor Kemenag)',
                    'Surat Pengantar Pensiun (dari Kantor Kemenag)',
                    'Pensiun APS (Atas Permintaan Sendiri)',
                    'Persyaratan :',
                    'Masa kerja min 20 tahun dengan usia min 50 tahun',
                    'Data Perorangan Calon Penerima Pensiun (DPCP) yang ditandatangani oleh PNS yang bersangkutan dan atasan langsung',
                    'SK CPNS dan SK PNS',
                    'SK Kenaikan Pangkat Terakhir',
                    'Surat Nikah Asli',
                    'KK',
                    'SKP 1 Tahun Terakhir',
                    'Surat Permohonan dari ybs diteruskan untuk dibuatkan surat dari kab/kota dan kanwil',
                    'Surat Pernyataan Tidak Pernah Mendapat Hukuman Disiplin Sedang / Berat (dari Kantor Kemenag)',
                    'Surat Pernyataan Tidak Sedang Menjalani Hukuman Disiplin Sedang / Berat (dari Kantor Kemenag)',
                    'Surat Pengantar Pensiun (dari Kantor Kemenag)',
                    'Pensiun Tidak Sehat Jasmani Dan Rohani',
                    'Persyaratan :',
                    'Data Perorangan Calon Penerima Pensiun (DPCP) yang ditandatangani oleh PNS yang bersangkutan dan atasan langsung',
                    'SK CPNS dan SK PNS',
                    'SK Kenaikan Pangkat Terakhir',
                    'Surat Nikah Asli',
                    'KK',
                    'SKP 1 Tahun Terakhir',
                    'Surat keterangan dari tim penilai Kesehatan dengan hasil poin e (dinyatakan sudah tidak layak untuk bekerja)',
                    'Surat Pernyataan Tidak Pernah Mendapat Hukuman Disiplin Sedang / Berat (dari Kantor Kemenag)',
                    'Surat Pernyataan Tidak Sedang Menjalani Hukuman Disiplin Sedang / Berat (dari Kantor Kemenag)',
                    'Surat Pengantar Pensiun (dari Kantor Kemenag)'
                ],
            ],
            [
                'kode_layanan' => 'TU-08',
                'nama_layanan' => 'Pengajuan Cuti',
                'seksi_kode' => 'TU',
                'tipe_pelaksanaan' => 'sistem_eksternal',
                'persyaratan' => [
                    'Cuti Tahunan',
                    'Persyaratan :',
                    'Blanko cuti tertanda tangan atasan langsung',
                    'SK PNS atau PPPK pemohon',
                    'Cuti Sakit',
                    'Persyaratan :',
                    'Surat Keterangan sakit dari dokter',
                    'Blanko cuti tertanda tangan atasan langsung',
                    'SK PNS atau PPPK pemohon',
                    'Cuti Alasan Penting',
                    'Persyaratan :',
                    'Keluarga Sakit Keras/Meninggal: Surat keterangan rawat inap/diagnosa dari dokter atau surat keterangan kematian',
                    'Pernikahan Sendiri: Salinan undangan pernikahan',
                    'Mendampingi istri melahirkan: Surat keterangan rawat inap',
                    'Blanko cuti tertanda tangan atasan langsung',
                    'SK PNS atau PPPK pemohon',
                    'Cuti Melahirkan',
                    'Persyaratan :',
                    'Blanko cuti tertanda tangan atasan langsung',
                    'Surat keterangan dokter mengenai Hari Perkiraan Lahir (HPL)',
                    'SK PNS atau PPPK pemohon',
                    'Cuti Besar',
                    'Persyaratan :',
                    'Telah bekerja paling singkat 5 tahun secara terus-menerus',
                    'Blanko cuti tertanda tangan atasan langsung',
                    'SK PNS atau PPPK pemohon',
                    'Untuk Ibadah Haji: Melampirkan jadwal keberangkatan atau kloter resmi',
                    'Cuti di Luar Tanggungan Negara',
                    'Persyaratan :',
                    'Surat Pengantar dari Instansi pengusul',
                    'Salinan SK PNS dan SK KP terakhir',
                    'Permohonan secara tertulis PNS kepada PPK',
                    'Dokumen pendukung alasan PNS yang bersangkutan mengajukan CLTN',
                    'Nota Persetujuan Kepala Badan Kepegawaian Negara'
                ],
            ],
            [
                'kode_layanan' => 'TU-09',
                'nama_layanan' => 'Pencantuman Gelar Akademik',
                'seksi_kode' => 'TU',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat Keputusan Tugas Belajar/SKMI/SKPP',
                    'Ijazah Asli / Ijazah Penyetaraan bagi Lulusan Luar Negeri',
                    'Transkrip Nilai / Transkrip Penyetaraan bagi Lulusan Luar Negeri',
                    'Surat Akreditasi Program Studi',
                    'Screenshot laman PDDIKTI / PISN',
                    'Surat Pengantar (dari Kantor Kemenag)'
                ],
            ],
            [
                'kode_layanan' => 'TU-10',
                'nama_layanan' => 'Pengajuan Tanda Kehormatan Satyalancana Karya Satya',
                'seksi_kode' => 'TU',
                'tipe_pelaksanaan' => 'sistem_eksternal',
                'persyaratan' => [
                    'Daftar Riwayat Hidup (DRH) yang ditandatangani oleh ybs dan Atasan Langsung',
                    'SK CPNS',
                    'SK Kenaikan Pangkat Terakhir',
                    'SK Jabatan Terakhir',
                    'Piagam Tanda Kehormatan Satyalancana Karya Satya sebelumnya, jika sebelumnya sudah pernah memperoleh'
                ],
            ],
            [
                'kode_layanan' => 'TU-11',
                'nama_layanan' => 'Pengajuan Tugas Belajar',
                'seksi_kode' => 'TU',
                'tipe_pelaksanaan' => 'sistem_eksternal',
                'persyaratan' => [
                    'SK PNS',
                    'SK Pangkat Terakhir',
                    'SK Jabatan',
                    'SKP (Sasaran Kerja Pegawai) bernilai baik dalam 2 tahun terakhir',
                    'Surat izin/rekomendasi dari pimpinan unit kerja',
                    'Surat Keterangan Lulus Seleksi atau Bukti Penerimaan dari Perguruan Tinggi',
                    'Sertifikat Akreditasi Program Studi tujuan minimal B',
                    'Jadwal Kuliah',
                    'Surat Keterangan Jarak Tempuh',
                    'Surat Perjanjian Tugas Belajar',
                    'Surat Pengantar (dari Kemenag Kabupaten)'
                ],
            ],
            [
                'kode_layanan' => 'TU-12',
                'nama_layanan' => 'Advokasi Hukum',
                'seksi_kode' => 'TU',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat Permohonan Resmi',
                    'Kronologi Kasus Tertulis',
                    'Surat Panggilan APH / Gugatan Pengadilan',
                    'SK Pegawai / Identitas Diri pemohon',
                    'Regulasi Dasar/SOP Pelaksanaan Tugas',
                    'Dokumen Bukti Pendukung Lainnya'
                ],
            ],
            [
                'kode_layanan' => 'TU-13',
                'nama_layanan' => 'Bimbingan / Penanganan Konflik Berdimensi Keagamaan',
                'seksi_kode' => 'TU',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Identitas Pelapor/Pemohon',
                    'Formulir Laporan Konflik/Permohonan Bimbingan\\',
                    'Kronologi & Bukti Awal',
                    'Legalitas Lembaga (Jika mewakili kelompok)'
                ],
            ],
            [
                'kode_layanan' => 'PDPONTREN-01',
                'nama_layanan' => 'Rekomendasi Bantuan Madin / LPQ / Pondok Pesantren',
                'seksi_kode' => 'PDPONTREN',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat Permohonan yang ditujukan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung lengkap dengan tanda tangan & stempel basah (asli)',
                    'Proposal Permohonan Bantuan dilengkapi dengan:',
                    'Permohonan Bantuan yang ditujukan kepada……….., mengetahui Kades/Lurah, Camat/Kepala KUA',
                    'Fotokopi Ijin Pendirian/Operasional Ponpes/Madin/TPQ',
                    'Profil/Latar belakang Ponpes/Madin/TPQ',
                    'Surat Keterangan Status Tanah/Surat Keterangan Domisili Lembaga',
                    'Susunan pengurus/Panitia',
                    'Rencana Anggaran Belanja (RAB)',
                    'Gambar/Foto Lengkap',
                    'BAP EMIS'
                ],
            ],
            [
                'kode_layanan' => 'PDPONTREN-02',
                'nama_layanan' => 'Rekomendasi Ijin Operasional Lembaga Pendidikan Al-Qur’An (LPQ)',
                'seksi_kode' => 'PDPONTREN',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat Permohonan yang ditujukan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung lengkap dengan tanda tangan & stempel basah (asli)',
                    'Proposal Permohonan Pendirian LPQ dilengkapi dengan:',
                    'Pendahuluan/latar belakang, Visi Misi Pendirian LPQ',
                    'Profil LPQ',
                    'Surat Keterangan Status Tanah',
                    'Keterangan Domisili Lembaga',
                    'SK Kepala dan Tenaga Pengajar',
                    'Fotokopi Ijasah Kepala dan Tenaga pengajar',
                    'Susunan Pengurus LPQ',
                    'Jadwal Pembelajaran LPQ',
                    'Data Ustadz/Ustadzah Lengkap (sesuai format EMIS)',
                    'Data Santri Lengkap (sesuai format EMIS)'
                ],
            ],
            [
                'kode_layanan' => 'PDPONTREN-03',
                'nama_layanan' => 'Rekomendasi Ijin Operasional Madrasah Diniyah Takmiliyah (MDT)',
                'seksi_kode' => 'PDPONTREN',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Data lembaga',
                    'Data Kepala MDT',
                    'Data Santri',
                    'Data Pendidik (Ustadz/Ustadzah)',
                    'Data Tenaga Kependidikan',
                    'Kurikulum',
                    'Data Sarana Prasarana',
                    'Berkas Persyaratan Utama:',
                    'Surat permohonan oleh lembaga',
                    'Struktur Organisasi Kepengurusan MDT',
                    'KTP Pengurus',
                    'Surat keterangan domisili dari kepala desa/kelurahan',
                    'Rekomendasi ormas',
                    'Surat pernyataan bersedia dan sanggup menyelenggarakan dan mengelola MDT dengan baik dan bertanggung jawab',
                    'Surat pernyataan loyal terhadap Pancasila, UUD 1945, NKRI dan tidak berafiliasi dengan organisasi terlarang di Indonesia',
                    'Dokumen Pendukung:',
                    'Logo',
                    'Foto gedung',
                    'Foto ruang belajar',
                    'Foto sarana prasarana',
                    'Foto kegiatan belajar mengajar',
                    'Foto papan nama/plang',
                    'Ruang kelas/pembelajaran yang terdapat foto Presiden dan Wakil Presiden RI serta Burung Garuda',
                    'Tiang bendera dan bendera merah putih'
                ],
            ],
            [
                'kode_layanan' => 'PDPONTREN-04',
                'nama_layanan' => 'Rekomendasi Ijin Operasional Pondok Pesantren',
                'seksi_kode' => 'PDPONTREN',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat permohonan ijin pendirian /ijin operasional yang ditujukan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung',
                    'Proposal permohonan ijin operasional dilengkapi dengan dokumen:',
                    'Pendahuluan/latar belakang',
                    'Profil lengkap pondok pesantren',
                    'Surat keterangan domisili lembaga dari kelurahan/kepala desa',
                    'Surat keterangan status tanah',
                    'Susunan pengurus Pondok Pesantren',
                    'Jadwal pembelajaran dan kurikulum di pesantren',
                    'Data ustadz/ustadzah lengkap dilampiri dengan ijazah/syahadah',
                    'Data santri lengkap (santri mukim minimal 15 santri di lampiri fotokopi ijazah dan KK)',
                    'Rekomendasi dari KUA setempat',
                    'Fotokopi SK Kemenkumham, Akta Notaris/Akta Yayasan',
                    'Fotokopi syahadah pengasuh',
                    'Fotokopi NPWP Pondok Pesantren',
                    'Menandatangani Surat Pernyataan Bermaterai',
                    'Surat keterangan/rekomendasi ormas keagamaan/MUI'
                ],
            ],
            [
                'kode_layanan' => 'PDPONTREN-05',
                'nama_layanan' => 'Konsultasi Aplikasi EMIS 4.0 Dan Vervikasi & Validasi Peserta Didik (Verval Pd)',
                'seksi_kode' => 'PDPONTREN',
                'tipe_pelaksanaan' => 'sistem_eksternal',
                'persyaratan' => [
                    'Lembaga memiliki akun EMIS 4.0 yang telah di verifikasi dan disetujui oleh admin kabupaten',
                    'Membawa laptop/perangkat yang mendukung',
                    'Terdaftar sebagai operator EMIS lembaga'
                ],
            ],
            [
                'kode_layanan' => 'PAIS-01',
                'nama_layanan' => 'Konsultasi Aplikasi SIAGA',
                'seksi_kode' => 'PAIS',
                'tipe_pelaksanaan' => 'sistem_eksternal',
                'persyaratan' => [
                    'Identitas pemohon (GPAI, Pengawas PAI, atau operator satuan pendidikan)',
                    'Uraian kendala/permasalahan yang dialami pada aplikasi SIAGA',
                    'Tangkapan layar (screenshot) kendala apabila ada',
                    'Akun SIAGA yang bersangkutan (username, bukan kata sandi)'
                ],
            ],
            [
                'kode_layanan' => 'PAIS-02',
                'nama_layanan' => 'Pembayaran TPG Guru Agama Islam',
                'seksi_kode' => 'PAIS',
                'tipe_pelaksanaan' => 'sistem_eksternal',
                'persyaratan' => [
                    'SK Penetapan Penerima Tunjangan Profesi yang telah terbit',
                    'Nomor rekening aktif atas nama penerima',
                    'Data GPAI/Pengawas PAI yang telah terverifikasi dan valid pada SIAGA',
                    'Data realisasi anggaran TPG per periode pembayaran'
                ],
            ],
            [
                'kode_layanan' => 'PAIS-03',
                'nama_layanan' => 'Rekomendasi Mutasi Guru',
                'seksi_kode' => 'PAIS',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat permohonan mutasi dari guru yang bersangkutan',
                    'Surat persetujuan/pelepasan dari satuan pendidikan dan pejabat berwenang asal',
                    'Surat kesediaan menerima dari satuan pendidikan tujuan',
                    'Fotokopi SK pangkat/golongan terakhir (bagi PNS) atau SK terakhir (bagi Non-PNS)',
                    'Fotokopi SK pembagian tugas mengajar tahun berjalan',
                    'Surat keterangan tidak sedang menjalani hukuman disiplin/proses hukum',
                    'Pas foto sesuai ketentuan'
                ],
            ],
            [
                'kode_layanan' => 'PENMA-01',
                'nama_layanan' => 'Penerbitan Surat Rekomendasi Izin Pendirian Madrasah',
                'seksi_kode' => 'PENMA',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat permohonan pendirian madrasah yang ditujukan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung',
                    'Proposal pendirian madrasah',
                    'Fotokopi Akta Pendirian Yayasan',
                    'Fotokopi Surat Keputusan Pengesahan Badan Hukum Yayasan dari Kementerian Hukum',
                    'Fotokopi Nomor Pokok Wajib Pajak (NPWP) Yayasan',
                    'Susunan Pengurus Yayasan',
                    'Dokumen kepemilikan atau penguasaan tanah yang sah',
                    'Dokumen kepemilikan atau penguasaan gedung/bangunan yang akan digunakan',
                    'Denah lokasi madrasah',
                    'Struktur organisasi madrasah',
                    'Daftar calon pendidik dan tenaga kependidikan beserta kualifikasinya',
                    'Daftar calon peserta didik',
                    'Rencana pembiayaan operasional madrasah',
                    'Rencana pengembangan madrasah',
                    'Kurikulum yang akan digunakan sesuai ketentuan',
                    'Profil yayasan'
                ],
            ],
            [
                'kode_layanan' => 'PENMA-02',
                'nama_layanan' => 'Perubahan, Penggantian, Atau Kerusakan Izin Operasional Madrasah',
                'seksi_kode' => 'PENMA',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Perubahan Izin Operasional',
                    'Surat permohonan perubahan izin operasional yang ditujukan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung',
                    'Surat permohonan dari Ketua Yayasan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung',
                    'Izin Operasional Madrasah asli atau salinannya',
                    'Surat Keputusan Yayasan atau dokumen pendukung yang menjadi dasar perubahan',
                    'Dokumen pendukung sesuai jenis perubahan, misalnya:',
                    'perubahan nama madrasah',
                    'perubahan alamat',
                    'perubahan penyelenggara/yayasan',
                    'perubahan bentuk atau data lain yang tercantum dalam izin operasional',
                    'Akta Perubahan Yayasan beserta pengesahan dari Kementerian Hukum apabila perubahan berkaitan dengan badan hukum yayasan',
                    'Penggantian Izin Operasional karena Hilang',
                    'Surat permohonan penggantian izin operasional',
                    'Surat Keterangan Kehilangan dari Kepolisian',
                    'Fotokopi izin operasional (apabila masih dimiliki)',
                    'Surat Pernyataan Tanggung Jawab Mutlak (SPTJM) dari Ketua Yayasan',
                    'Kerusakan Dokumen Izin Operasional',
                    'Surat permohonan penerbitan surat keterangan kerusakan dokumen',
                    'Dokumen izin operasional yang rusak',
                    'Identitas pemohon'
                ],
            ],
            [
                'kode_layanan' => 'PENMA-03',
                'nama_layanan' => 'Penutupan Madrasah',
                'seksi_kode' => 'PENMA',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat permohonan penutupan madrasah dari Ketua Yayasan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung',
                    'Surat Keputusan Yayasan tentang penutupan madrasah',
                    'Fotokopi Izin Operasional Madrasah',
                    'Profil singkat madrasah yang akan ditutup',
                    'Daftar peserta didik yang masih aktif beserta rencana penyelesaian hak pendidikannya (mutasi atau penyelesaian pendidikan)',
                    'Daftar pendidik dan tenaga kependidikan',
                    'Berita Acara atau dokumen penyelesaian aset pendidikan apabila dipersyaratkan oleh yayasan atau ketentuan yang berlaku',
                    'Surat pernyataan bahwa seluruh hak peserta didik telah atau akan diselesaikan sesuai ketentuan'
                ],
            ],
            [
                'kode_layanan' => 'PENMA-04',
                'nama_layanan' => 'Rekomendasi Pengangkatan Kepala Madrasah Swasta',
                'seksi_kode' => 'PENMA',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat permohonan dari Yayasan penyelenggara kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung',
                    'Daftar Riwayat Hidup',
                    'Surat Pernyataan Mampu Membaca dan Menulis Al Qur\'an',
                    'Fotokopi ijazah kualifikasi akademik paling rendah sarjana (S-1) atau diploma empat (D-IV)',
                    'Sertifikat pendidik untuk guru PNS/NonPNS(tidak wajib)',
                    'Foto copy SK terakhir dari Yayasan',
                    'Surat keterangan pengalaman mengajar yang dikeluarkan oleh Kepala Madrasah',
                    'Surat keterangan sebagai guru tetap yayasan minimal 6 (enam) tahun untuk Madrasah atau 3 (tiga) tahun unutk RA',
                    'Fotokopi hasil Penilaian Kinerja Guru (PKG) 2 tahun terakhir',
                    'Fotokopi surat keputusan atau surat keterangan terkait pengalaman manajerial dengan tugas yang relevan dengan fungsi Kepala Madrasah',
                    'Surat keterangan sehat jasmani dan rohani dan bebas NAPZA yang dikeluarkan oleh rumah sakit Pemerintah',
                    'Surat pernyataan tidak sedang menjadi tersangka atau tidak pemah menjadi terpidana mengetahui Kepala Madrasah',
                    'Surat rekomendasi dari Kepala Madrasah',
                    'Surat rekomendasi dari Pengawas Pembinanya',
                    'Piagam yang relevan (sertifikat izin pendirian madrasah, piagam NPSN, sertifikat akreditasi madrasah, SK Kemenkumham, sertifikat diklat calon kepala madrasah bagi yang memiliki, piagam diklat/bimtek/seminar,dll)'
                ],
            ],
            [
                'kode_layanan' => 'PENMA-05',
                'nama_layanan' => 'Penerbitan Surat Rekomendasi Bantuan Madrasah',
                'seksi_kode' => 'PENMA',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat permohonan rekomendasi bantuan operasional dari Kepala Madrasah/Yayasan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung',
                    'Proposal usulan bantuan sesuai jenis bantuan yang diajukan',
                    'Fotokopi Surat Keputusan Izin Operasional Madrasah yang masih berlaku',
                    'Profil Madrasah',
                    'Rencana Anggaran Biaya (RAB), untuk jenis bantuan yang mensyaratkannya',
                    'Dokumen kepemilikan atau penguasaan tanah dan/atau bangunan yang sah, untuk bantuan yang berkaitan dengan pembangunan, rehabilitasi, atau pengadaan sarana dan prasarana',
                    'Surat Pernyataan Tanggung Jawab Mutlak (SPTJM), untuk jenis bantuan yang mensyaratkannya',
                    'Pakta Integritas, untuk jenis bantuan yang mensyaratkannya',
                    'Dokumen pendukung sesuai karakteristik bantuan yang diajukan, yaitu:',
                    'daftar kebutuhan barang/jasa (apabila dipersyaratkan)',
                    'gambar rencana dan/atau Rencana Anggaran Biaya teknis (untuk bantuan pembangunan/rehabilitasi)',
                    'data penerima manfaat atau peserta didik (untuk bantuan yang mensyaratkannya)',
                    'dokumen lain yang secara tegas disebutkan dalam Petunjuk Teknis program bantuan yang diajukan'
                ],
            ],
            [
                'kode_layanan' => 'PENMA-06',
                'nama_layanan' => 'Pengesahan Surat Keterangan Pengganti Ijazah Madrasah',
                'seksi_kode' => 'PENMA',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Pengesahan Surat Keterangan Pengganti Ijazah karena Hilang',
                    'Surat permohonan yang ditujukan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung',
                    'Surat Keterangan Pengganti Ijazah yang diterbitkan oleh Kepala Madrasah',
                    'Surat Keterangan Kehilangan dari Kepolisian',
                    'Surat Pernyataan Tanggung Jawab Mutlak (SPTJM)',
                    'Surat Pernyataan Saksi',
                    'Fotokopi ijazah yang hilang (apabila masih dimiliki)',
                    'Fotokopi identitas pemohon',
                    'Pengesahan Surat Keterangan Pengganti Ijazah karena Rusak',
                    'Surat permohonan yang ditujukan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung',
                    'Surat Keterangan Pengganti Ijazah yang diterbitkan oleh Kepala Madrasah',
                    'Ijazah asli yang rusak',
                    'Surat Pernyataan Tanggung Jawab Mutlak (SPTJM)',
                    'Fotokopi identitas pemohon',
                    'Pengesahan Surat Keterangan Kesalahan Penulisan Ijazah',
                    'Surat permohonan yang ditujukan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung',
                    'Surat Keterangan Kesalahan Penulisan Ijazah yang diterbitkan oleh Kepala Madrasah',
                    'Ijazah asli',
                    'Dokumen pendukung yang membuktikan data yang benar (misalnya Akta Kelahiran, Kartu Keluarga, atau dokumen kependudukan lainnya sesuai jenis kesalahan)',
                    'Surat Pernyataan Tanggung Jawab Mutlak (SPTJM)',
                    'Fotokopi identitas pemohon'
                ],
            ],
            [
                'kode_layanan' => 'PENMA-07',
                'nama_layanan' => 'Legalisasi Dokumen Pendidikan Madrasah',
                'seksi_kode' => 'PENMA',
                'tipe_pelaksanaan' => 'perlu_fisik',
                'persyaratan' => [
                    'Legalisasi Ijazah Madrasah',
                    'Surat permohonan legalisasi',
                    'Ijazah asli',
                    'Fotokopi ijazah yang akan dilegalisasi',
                    'Legalisasi Transkrip Nilai',
                    'Surat permohonan legalisasi',
                    'Transkrip nilai asli',
                    'Fotokopi transkrip nilai yang akan dilegalisasi',
                    'Legalisasi Piagam Penghargaan',
                    'Surat permohonan legalisasi',
                    'Piagam penghargaan asli',
                    'Fotokopi piagam penghargaan yang akan dilegalisasi'
                ],
            ],
            [
                'kode_layanan' => 'PENMA-08',
                'nama_layanan' => 'Pemutakhiran, Perbaikan, Dan Penyediaan Data Peserta Didik',
                'seksi_kode' => 'PENMA',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Pemutakhiran Data Peserta Didik',
                    'Surat permohonan dari Kepala Madrasah',
                    'Daftar data peserta didik yang akan dimutakhirkan',
                    'Dokumen pendukung sesuai jenis perubahan data, antara lain:',
                    'Akta Kelahiran',
                    'Kartu Keluarga',
                    'Ijazah atau Surat Keterangan Lulus (apabila diperlukan)',
                    'dokumen resmi lainnya yang menjadi dasar perubahan data',
                    'Perbaikan Data Peserta Didik',
                    'Surat permohonan dari Kepala Madrasah',
                    'Bukti kesalahan data yang akan diperbaiki',
                    'Dokumen pendukung yang memuat data yang benar, sesuai jenis data yang diperbaiki',
                    'Penyediaan Data Peserta Didik',
                    'Surat permohonan dari pemohon',
                    'Identitas pemohon',
                    'Surat tugas atau surat kuasa (apabila permohonan diajukan oleh pihak lain atau instansi)',
                    'Dokumen yang menjelaskan tujuan penggunaan data, apabila diperlukan'
                ],
            ],
            [
                'kode_layanan' => 'PENMA-09',
                'nama_layanan' => 'Konsultasi Layanan Pendidikan Madrasah',
                'seksi_kode' => 'PENMA',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Konsultasi EMIS Madrasah',
                    'Menunjukkan identitas sebagai operator madrasah, kepala madrasah, atau pihak yang berwenang',
                    'Menyampaikan permasalahan yang akan dikonsultasikan',
                    'Membawa dokumen pendukung apabila diperlukan',
                    'Konsultasi EMIS GTK',
                    'Guru, tenaga kependidikan, operator madrasah, atau kepala madrasah',
                    'Menyampaikan permasalahan yang akan dikonsultasikan',
                    'Membawa dokumen pendukung sesuai kebutuhan',
                    'Konsultasi TPG',
                    'Guru yang bersangkutan atau kepala madrasah',
                    'Membawa dokumen yang berkaitan dengan permasalahan TPG (apabila diperlukan)',
                    'Konsultasi BOS/BOP',
                    'Kepala Madrasah atau Bendahara BOS/BOP',
                    'Membawa dokumen pendukung apabila diperlukan',
                    'Konsultasi PIP',
                    'Kepala Madrasah, operator, peserta didik, atau orang tua/wali peserta didik',
                    'Membawa identitas dan dokumen pendukung apabila diperlukan',
                    'Konsultasi PPG',
                    'Guru yang bersangkutan',
                    'Membawa dokumen pendukung sesuai kebutuhan'
                ],
            ],
            [
                'kode_layanan' => 'BIMAS_ISLAM-01',
                'nama_layanan' => 'Permohonan Data Keagamaan',
                'seksi_kode' => 'BIMAS_ISLAM',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat Permohonan Data yang ditujukan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung'
                ],
            ],
            [
                'kode_layanan' => 'BIMAS_ISLAM-02',
                'nama_layanan' => 'Terdaftar Perpanjang Dan Rekomendasi Majelis Taklim',
                'seksi_kode' => 'BIMAS_ISLAM',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat yang ditujukan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung',
                    'Daftar Susunan Pengurus Majelis Taklim',
                    'Surat Keterangan Domisili Majelis Taklim Pemerintah setempat',
                    'Foto Copy KTP Pengurus Majelis Taklim',
                    'Foto Copy KTP Minimal 15 (Lima Belas) Orang Jamaah'
                ],
            ],
            [
                'kode_layanan' => 'BIMAS_ISLAM-03',
                'nama_layanan' => 'Rekomendasi Permohonan Bantuan Masjid/Mushalla',
                'seksi_kode' => 'BIMAS_ISLAM',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat permohonan	yang ditujukan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung',
                    'Nomor ID Masjid/Mushalla',
                    'Daftar	Susunan Pengurus/Panitia Pembangunan Masjid/Mushalla',
                    'Daftar Susunan Pengurus/Panitia Pembangunan Masjid/Mushalla',
                    'Rencana Anggaran Biaya yang dibutuhkan',
                    'Foto copy sertifikat tanah atau akta ikrar wakaf dari KUA'
                ],
            ],
            [
                'kode_layanan' => 'BIMAS_ISLAM-04',
                'nama_layanan' => 'Pengukuran Arah Kiblat',
                'seksi_kode' => 'BIMAS_ISLAM',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat permohonan yang ditujukan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung',
                    'Dalam surat permohonan memuat Nomor Contact Person dan peta / denah lokasi'
                ],
            ],
            [
                'kode_layanan' => 'BIMAS_ISLAM-05',
                'nama_layanan' => 'Konsultasi Perkawinan',
                'seksi_kode' => 'BIMAS_ISLAM',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Pemohon mengajukan surat permohonan Penasihatan Perkawinan ditujukan kepada : Yth. Kepala Kantor Kementerian Agama Kabupaten Temanggung, c.q. Kepala Seksi Bimbingan Masyarakat Islam Kantor Kemenag Kabupaten Temanggung yang memuat Identitas Pemohon dan yang Dimohonkan Penasihatan sebagai berikut :',
                    'Nama lengkap pemohon',
                    'Tempat, Tanggal Lahir',
                    'NIP/NIK',
                    'Pangkat/Gol. Ruang, Jabatan',
                    'Pekerjaan',
                    'Satuan organisasi',
                    'Alamat lengkap pemohon',
                    'Nomor WA Aktif',
                    'Surat Kronologis Permasalahan Rumah Tangga',
                    'Surat dari Instansi Pemohon bagi Aparatur Sipil Negara (PNS dan PPPK)',
                    'Foto Copy KTP Suami Istri',
                    'Foto Copy Kartu Keluarga',
                    'Foto Copy Akta/Buku Nikah'
                ],
            ],
            [
                'kode_layanan' => 'BIMAS_ISLAM-06',
                'nama_layanan' => 'Bimbingan Calon Pengantin',
                'seksi_kode' => 'BIMAS_ISLAM',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Bukti pendaftaran nikah'
                ],
            ],
            [
                'kode_layanan' => 'BIMAS_ISLAM-07',
                'nama_layanan' => 'Bimbingan Remaja Usia Sekolah (BRUS)',
                'seksi_kode' => 'BIMAS_ISLAM',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat permohonan atau proposal kerja sama dari pihak sekolah (Madrasah/Sekolah Menengah) atau instansi komunitas remaja',
                    'Data nominatif calon peserta (Remaja Usia Sekolah/Siswa'
                ],
            ],
            [
                'kode_layanan' => 'ZAWA-01',
                'nama_layanan' => 'Rekomendasi Izin Pembukaan Kantor Perwakilan Lembaga Amil Zakat (Laz) Skala Kabupaten Berbasis Simzat',
                'seksi_kode' => 'ZAWA',
                'tipe_pelaksanaan' => 'sistem_eksternal',
                'persyaratan' => [
                    'Semua dokumen persyaratan wajib di-scan dalam format PDF asli (bukan fotokopi) untuk diunggah pada aplikasi SIMZAT:',
                    'Surat permohonan resmi dari Pimpinan LAZ ditujukan kepada Kepala Kantor Kemenag Kabupaten Temanggung',
                    'Dokumen Keputusan Izin Pembentukan LAZ dari Kementerian Agama RI/Kanwil Kemenag Provinsi',
                    'Dokumen Program Kerja pendayagunaan zakat minimal untuk 1 (satu) tahun di wilayah Kabupaten Temanggung',
                    'Surat pernyataan kesediaan diaudit syariah dan keuangan secara berkala',
                    'Dokumen legalitas Pengawas Syariat yang terdaftar',
                    'Daftar riwayat hidup (curriculum vitae) beserta identitas para amil di tingkat perwakilan Kabupaten Temanggung',
                    'Surat keterangan domisili kantor perwakilan dari Kelurahan/Desa setempat'
                ],
            ],
            [
                'kode_layanan' => 'ZAWA-02',
                'nama_layanan' => 'Rekomendasi Tukar Menukar (Ruislag) Harta Benda Wakaf',
                'seksi_kode' => 'ZAWA',
                'tipe_pelaksanaan' => 'sistem_eksternal',
                'persyaratan' => [
                    'Surat Permohonan resmi dari Nazhir yang ditujukan kepada Kepala Kantor Kementerian Agama Kabupaten Temanggung',
                    'Dokumen Sah Harta Benda Wakaf semula:',
                    'Fotokopi Akta Ikrar Wakaf (AIW) / Akta Pengganti Akta Ikrar Wakaf (APAIW) yang dilegalisir Kepala KUA',
                    'Sertifikat Tanah Wakaf asli (atau fotokopi jika asli sedang dalam proses/agunan, dengan melampirkan surat keterangan terkait)',
                    'Surat Rekomendasi/Pertimbangan Kelayakan dari Kepala KUA selaku Pejabat Pembuat Akta Ikrar Wakaf (PPAIW) kecamatan setempat',
                    'Dokumen Alasan Ruislag (Proposal) yang memuat latar belakang, rincian alasan tukar menukar, kejelasan peruntukan kepentingan umum/keagamaan, serta rencana pemanfaatan harta benda penukar',
                    'Dokumen Jaminan Harta Benda Pengganti:',
                    'Sertifikat kepemilikan sah (SHM/HGB) atas tanah calon pengganti yang bebas dari sengketa, sitaan, atau agunan',
                    'Surat pernyataan dari calon penyedia tanah pengganti mengenai kesediaan menukarkan tanahnya',
                    'Hasil Penilaian (Appraisal) dari Penilai Publik Independen (KJPP) yang menerangkan bahwa nilai aset pengganti minimal sama atau lebih tinggi dari aset wakaf semula',
                    'Surat Pernyataan Bersama dari Nazhir dan Kepala Desa/Lurah setempat yang menyatakan bahwa tanah pengganti tersebut aman dan bebas dari sengketa hukum',
                    'Berita Acara Rapat Nazhir yang dihadiri oleh Tokoh Masyarakat, Pemerintah Setempat, dan Mauquf Alaih (penerima manfaat wakaf) mengenai persetujuan ruislag'
                ],
            ],
            [
                'kode_layanan' => 'ZAWA-03',
                'nama_layanan' => 'Pendaftaran Akta Ikrar Wakaf (AIW)/Akta Pengganti Ikrar Wakaf (APAIW) Melalui Aplikasi Siwak',
                'seksi_kode' => 'ZAWA',
                'tipe_pelaksanaan' => 'sistem_eksternal',
                'persyaratan' => [
                    'Persyaratan Umum (Administratif) :',
                    'Surat Pengantar/Keterangan Pendaftaran Wakaf dari Kepala Desa/Lurah setempat',
                    'Scan Asli dan Fotokopi KTP dan Kartu Keluarga (KK) Wakif (Pemberi Wakaf)',
                    'Scan Asli dan Fotokopi KTP dan Kartu Keluarga (KK) Nazhir (Pengelola Wakaf, minimal 3 orang) yang telah disahkan KUA setempat',
                    'Scan Asli dan Fotokopi KTP 2 (dua) orang Saksi ikrar wakaf',
                    'Surat Pernyataan dari Wakif bahwa tanah yang diwakafkan aman, tidak dalam sengketa, sitaan, atau agunan (bermeterai cukup)',
                    'Surat persetujuan tertulis dari ahli waris Wakif (bermeterai cukup)',
                    'Rencana penggunaan/peruntukan harta benda wakaf (masjid, makam, madrasah, dll)',
                    'Persyaratan Khusus Dokumen Tanah (Pilih salah satu sesuai status tanah) :',
                    'Tanah Sudah Bersertifikat :',
                    'Sertifikat Hak Milik (SHM) atau bukti kepemilikan hak atas tanah asli dan fotokopi',
                    'Tanah Belum Bersertifikat  :',
                    'Fotokopi Letter C / Girik / Petuk Pajak Bumi yang dilegalisir Kepala Desa/Lurah',
                    'Surat Riwayat Tanah dari Desa/Kelurahan',
                    'Surat Keterangan Riwayat Kepemilikan Tanah',
                    'Surat Keterangan Tidak Sengketa dari Desa/Kelurahan (diketahui Camat setempat)'
                ],
            ],
            [
                'kode_layanan' => 'KATOLIK-01',
                'nama_layanan' => 'Rekomendasi Bantuan Sarana Prasarana',
                'seksi_kode' => 'KATOLIK',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat Permohonan dan Proposal Bantuan :',
                    'Untuk proposal Bantuan memuat antara lain : Latar belakang, Tujuan, Dasar Hukum, Sasaran, Hasil yang diharapkan, Kepanitian, Jenis Kegiatan, Jadwal Kegiatan dan Penutup. Dilampirkan pula fotocopy rekening Bank atas nama KKG / MGMP PAKat yang masih aktif dan fotocopy NPWP, tersebut. (Urusan Pendidikan)',
                    'Surat Permohonan Bantuan Kegiatan, Sarana Prasarana, dan Rehab. Untuk proposal Bantuan memuat antara lain : Latar belakang, Tujuan, Dasar Hukum, Sasaran, Hasil yang diharapkan, Kepanitian, Jenis Kegiatan, Jadwal Kegiatan dan Penutup. (Urusan Agama)',
                    'Semua Permohonan ditujukan kepada Kepala Kantor Kementerian Agama Kab. Temanggung'
                ],
            ],
            [
                'kode_layanan' => 'KATOLIK-02',
                'nama_layanan' => 'Rekomendasi Bantuan Guru',
                'seksi_kode' => 'KATOLIK',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Surat Permohonan memuat a.l : Nama pemohon, NIP Pemohon, Pangkat/Gol.Ruang pemohon, Jabatan pemohon, Nama termohon, NIP termohon, Pangkat/Gol.Ruang termohon, Jabatan termohon, Tujuan surat permohonan',
                    'Permohonan ditujukan kepada Kepala Kantor Kementerian Agama Kab. Temanggung'
                ],
            ],
            [
                'kode_layanan' => 'BUDDHA-01',
                'nama_layanan' => 'Rekomendasi Bantuan (Penyelenggara Buddha)',
                'seksi_kode' => 'BUDDHA',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Pengguna layanan membuat surat permohonan tertulis yang berisi: identitas pemohon yang meliputi nama institusi / lembaga dibawah naungan Kementerian Agama, Alamat, Tanda Daftar / Ijin Operasional Lembaga dengan melampirkan:',
                    'Surat Permohonan ditujukan kepada Kepala Kantor Kementerian Agama Kab.Temanggung u.p Penyelenggara',
                    'Proposal permohonan bantuan yang minimal memuat latar belakang permasalahan, identitas pemohon bantuan, tujuan penggunaan bantuan, jumlah bantuan/bentuk bantuan yang diminta',
                    'Tanda Daftar untuk Rumah Ibadah/Ijin Operasional untuk Lembaga Pendidikan Keagamaan',
                    'Surat Keterangan Domisili',
                    'Bukti Kepemilikan tanah & bangunan untuk rumah ibadah',
                    'Syarat tambahan sesuai juknis masing-masing bantuan',
                    'Ditujukan ke alamat: Kantor Kementerian Agama Kabupaten Temanggung Jalan Jenderal Sudirman No.121 Temanggung 56218',
                    'Jenis Bantuan di Penyelenggara Buddha :',
                    'Bantuan Rumah Ibadah Bersih dan Sehat',
                    'Bantuan Rehabilitasi Rumah Ibadah/Sekolah Minggu Buddha',
                    'Bantuan Sarana Prasarana Lembaga Pendidikan Keagamaan Buddha',
                    'Bantuan Operasional Lembaga Keagamaan Buddha',
                    'Bantuan Pembangunan Rumah Ibadah'
                ],
            ],
            [
                'kode_layanan' => 'BUDDHA-02',
                'nama_layanan' => 'Rekomendasi Izin Operasional Lembaga Pendidikan Keagamaan (Penyelenggara Buddha)',
                'seksi_kode' => 'BUDDHA',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Syarat Izin Operasional Lembaga Pendidikan Keagamaan Buddha',
                    'Profil Lembaga (Nama Penanggung Jawab, Nama Badan Penyelenggara,	AlamatBadan Penyelenggara, Nama Lembaga, No. telp Lembaga, Nama Kepala Lembaga, Jenis Pendidikan, Jenis Lembaga, Status Lembaga, Tanggal Berdiri)',
                    'Data Kependidikan (Dokumen Kurikulum, Daftar Calon Guru, SK Pengangkatan Calon Kepala Sekolah dan Riwayat Hidup, Daftar Calon Tenaga Kependidikan)',
                    'Alamat Lembaga',
                    'Sarana dan Prasarana',
                    'Foto Lembaga',
                    'Nomor Rekening Lembaga',
                    'Pengguna layanan mengajukan surat permohonan ditujukan kepada Dirjen Bimas Buddha Kementrian Agama RI',
                    'Hadir langsung ke Kantor Kemenag Kab.Temanggung dengan mengajukan permohonan melalui PTSP, dengan membawa dokumen persyaratan Penerbitan Nomor   Registrasi   rumah   ibadah/   tempat peribadahan dan Organisasi Keagamaan Buddha'
                ],
            ],
            [
                'kode_layanan' => 'BUDDHA-03',
                'nama_layanan' => 'Verifikasi Dan Validasi Tanda Daftar Lembaga Keagamaan (Penyelenggara Buddha)',
                'seksi_kode' => 'BUDDHA',
                'tipe_pelaksanaan' => 'full_digital',
                'persyaratan' => [
                    'Syarat Tanda Daftar Rumah Ibadah',
                    'Surat Permohonan Kepada Ditjen Bimas Buddha',
                    'Surat Pernyataan Tidak Sengketa',
                    'Salinan Akta Tanah/Bukti Status Tanah',
                    'Salinan KTP Pengurus Rumah Ibadah Agama Buddha',
                    'Deskripsi Rumah Ibadah (tahun	Berdiri dan Jumlah Umat)',
                    'Photo Rumah Ibadah (Tampak Depan, Kanan, Kiri, Altar)',
                    'Surat Keterangan Domisili RIAB',
                    'Pas Photo Ketua (4x6 cm) Memakai Baju Berkerah',
                    'Syarat Tanda Daftar Organisasi Keagamaan Buddha',
                    'Surat Permohonan Kepada Ditjen Bimas Buddha',
                    'Surat Pernyataan Tidak Sengketa',
                    'Salinan Akta Notaris',
                    'Salinan Surat Pengesahan dari Kemenkumham',
                    'Diskripsi OKB',
                    'Photo Kantor Sekretariat (Tampak Depan dan Dalam Ruangan)',
                    'Surat Keterangan Domisili OKB',
                    'Pas Photo Ketua (4x6 cm) Memakai Baju Berkerah',
                    'Pengguna layanan mengajukan surat permohonan ditujukan kepada Dirjen Bimas Buddha Kementrian Agama RI',
                    'Hadir langsung ke Kemenag Kab. Temanggung dengan mengajukan permohonan melalui PTSP, dengan membawa dokumen persyaratan Penerbitan Nomor Registrasi rumah   ibadah/tempat peribadahan dan Organisasi Keagamaan Buddha'
                ],
            ],
        ];

        foreach ($data as $item) {
            // Ambil klasifikasi kebutuhan untuk kode_layanan ini. Kalau
            // belum dipetakan di layanan_klasifikasi.php, biarkan null
            // (layanan tetap tersimpan tapi tidak akan tampil di grid
            // kategori/wizard sampai dipetakan lewat panel admin).
            $klas = $klasifikasi[$item['kode_layanan']] ?? [];

            $layanan = Layanan::updateOrCreate(
                ['kode_layanan' => $item['kode_layanan']],
                [
                    'nama_layanan' => $item['nama_layanan'],
                    'seksi_id' => $seksi[$item['seksi_kode']] ?? null,
                    'tipe_pelaksanaan' => $item['tipe_pelaksanaan'],
                    'deskripsi' => null,
                    'kategori' => $klas['kategori'] ?? null,
                    'subkategori' => $klas['subkategori'] ?? null,
                    'target_pengguna' => $klas['target_pengguna'] ?? null,
                    'tag_pencarian' => $klas['tag_pencarian'] ?? null,
                    'jenis_layanan' => $klas['jenis_layanan'] ?? null,
                ]
            );

            foreach ($item['persyaratan'] as $namaPersyaratan) {
                Persyaratan::updateOrCreate([
                    'layanan_id' => $layanan->id,
                    'nama_persyaratan' => $namaPersyaratan,
                ], ['wajib' => true]);
            }
        }
    }
}
