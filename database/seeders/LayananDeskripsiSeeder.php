<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;

/**
 * Mengisi kolom deskripsi untuk 45 layanan yang sebelumnya kosong
 * (LayananSeeder awal cuma isi nama/seksi/persyaratan, belum deskripsi).
 * Draf 1-2 kalimat per layanan, boleh diedit lagi lewat panel admin
 * kalau ada yang kurang pas.
 *
 * Aman dijalankan berkali-kali: hanya update kolom deskripsi berdasar
 * kode_layanan, tidak bikin data baru/duplikat.
 */
class LayananDeskripsiSeeder extends Seeder
{
    public function run(): void
    {
        $deskripsi = [
            'TU-01' => 'Layanan penyampaian pengaduan, saran, dan masukan terkait pelayanan publik di Kantor Kemenag Kab. Temanggung.',
            'TU-02' => 'Layanan penerbitan surat izin untuk keperluan penelitian/wawancara/pengambilan data di lingkungan Kantor Kemenag.',
            'TU-03' => 'Layanan pengajuan praktik kerja lapangan (PKL)/magang di lingkungan Kantor Kemenag.',
            'TU-04' => 'Layanan legalisir/pengesahan salinan dokumen agar sesuai dengan aslinya — memerlukan dokumen asli sebagai pembanding saat proses berlangsung.',
            'TU-05' => 'Layanan permohonan petugas rohaniwan/pembaca doa untuk kegiatan resmi di lingkungan Kantor Kemenag.',
            'TU-06' => 'Layanan pengurusan administrasi kenaikan pangkat pegawai di lingkungan Kantor Kemenag.',
            'TU-07' => 'Layanan pengurusan administrasi persiapan pensiun pegawai di lingkungan Kantor Kemenag.',
            'TU-08' => 'Layanan pengajuan cuti pegawai di lingkungan Kantor Kemenag.',
            'TU-09' => 'Layanan pencantuman gelar akademik pada dokumen kepegawaian.',
            'TU-10' => 'Layanan pengusulan tanda kehormatan/penghargaan bagi pegawai di lingkungan Kantor Kemenag.',
            'TU-11' => 'Layanan pengajuan tugas belajar bagi pegawai di lingkungan Kantor Kemenag.',
            'TU-12' => 'Layanan advokasi Hukum, ditangani oleh Sub Bagian Tata Usaha.',
            'TU-13' => 'Layanan bimbingan/konsultasi terkait bimbingan / penanganan konflik berdimensi keagamaan, ditangani Sub Bagian Tata Usaha.',
            'PDPONTREN-01' => 'Layanan penerbitan surat rekomendasi untuk pengajuan bantuan Madin / LPQ / Pondok Pesantren, diproses oleh Seksi Pendidikan Diniyah dan Pondok Pesantren.',
            'PDPONTREN-02' => 'Layanan rekomendasi Ijin Operasional Lembaga Pendidikan Al-Qur’An (LPQ), ditangani oleh Seksi Pendidikan Diniyah dan Pondok Pesantren.',
            'PDPONTREN-03' => 'Layanan rekomendasi Ijin Operasional Madrasah Diniyah Takmiliyah (MDT), ditangani oleh Seksi Pendidikan Diniyah dan Pondok Pesantren.',
            'PDPONTREN-04' => 'Layanan rekomendasi Ijin Operasional Pondok Pesantren, ditangani oleh Seksi Pendidikan Diniyah dan Pondok Pesantren.',
            'PDPONTREN-05' => 'Layanan pendampingan dan konsultasi penggunaan aplikasi sistem terkait, oleh Seksi Pendidikan Diniyah dan Pondok Pesantren.',
            'PAIS-01' => 'Layanan pendampingan dan konsultasi penggunaan aplikasi sistem terkait, oleh Seksi Pendidikan Agama Islam.',
            'PAIS-02' => 'Layanan terkait proses pembayaran/pencairan tunjangan bagi guru, ditangani Seksi Pendidikan Agama Islam.',
            'PAIS-03' => 'Layanan penerbitan surat rekomendasi mutasi/perpindahan tugas, diproses oleh Seksi Pendidikan Agama Islam.',
            'PENMA-01' => 'Layanan penerbitan surat rekomendasi/izin operasional terkait penerbitan surat rekomendasi izin pendirian madrasah, diproses oleh Seksi Pendidikan Madrasah.',
            'PENMA-02' => 'Layanan penerbitan surat rekomendasi/izin operasional terkait perubahan, penggantian, atau kerusakan izin operasional madrasah, diproses oleh Seksi Pendidikan Madrasah.',
            'PENMA-03' => 'Layanan penutupan Madrasah, ditangani oleh Seksi Pendidikan Madrasah.',
            'PENMA-04' => 'Layanan rekomendasi Pengangkatan Kepala Madrasah Swasta, ditangani oleh Seksi Pendidikan Madrasah.',
            'PENMA-05' => 'Layanan penerbitan surat rekomendasi untuk pengajuan bantuan Madrasah, diproses oleh Seksi Pendidikan Madrasah.',
            'PENMA-06' => 'Layanan pengesahan Surat Keterangan Pengganti Ijazah Madrasah, ditangani oleh Seksi Pendidikan Madrasah.',
            'PENMA-07' => 'Layanan legalisir/pengesahan salinan dokumen agar sesuai dengan aslinya — memerlukan dokumen asli sebagai pembanding saat proses berlangsung.',
            'PENMA-08' => 'Layanan konsultasi dan pendampingan verifikasi-validasi data pada aplikasi EMIS, oleh Seksi Pendidikan Madrasah.',
            'PENMA-09' => 'Layanan konsultasi Layanan Pendidikan Madrasah, ditangani oleh Seksi Pendidikan Madrasah.',
            'BIMAS_ISLAM-01' => 'Layanan permohonan Data Keagamaan, ditangani oleh Seksi Bimbingan Masyarakat Islam.',
            'BIMAS_ISLAM-02' => 'Layanan pendataan dan rekomendasi terkait majelis taklim, diproses oleh Seksi Bimbingan Masyarakat Islam.',
            'BIMAS_ISLAM-03' => 'Layanan rekomendasi Permohonan Bantuan Masjid/Mushalla, ditangani oleh Seksi Bimbingan Masyarakat Islam.',
            'BIMAS_ISLAM-04' => 'Layanan pengukuran Arah Kiblat, ditangani oleh Seksi Bimbingan Masyarakat Islam.',
            'BIMAS_ISLAM-05' => 'Layanan bimbingan/konsultasi terkait konsultasi perkawinan, ditangani Seksi Bimbingan Masyarakat Islam.',
            'BIMAS_ISLAM-06' => 'Layanan bimbingan/konsultasi terkait bimbingan calon pengantin, ditangani Seksi Bimbingan Masyarakat Islam.',
            'BIMAS_ISLAM-07' => 'Layanan bimbingan/konsultasi terkait bimbingan remaja usia sekolah (brus), ditangani Seksi Bimbingan Masyarakat Islam.',
            'ZAWA-01' => 'Layanan penerbitan surat rekomendasi/izin operasional terkait rekomendasi izin pembukaan kantor perwakilan lembaga amil zakat (laz) skala kabupaten berbasis simzat, diproses oleh Penyelenggara Zakat Wakaf.',
            'ZAWA-02' => 'Layanan pengurusan izin tukar-menukar (ruislag) tanah wakaf sesuai ketentuan yang berlaku.',
            'ZAWA-03' => 'Layanan pendaftaran resmi terkait pendaftaran akta ikrar wakaf (aiw)/akta pengganti ikrar wakaf (apaiw) melalui aplikasi siwak, diproses oleh Penyelenggara Zakat Wakaf.',
            'KATOLIK-01' => 'Layanan penerbitan surat rekomendasi untuk pengajuan bantuan Sarana Prasarana, diproses oleh Penyelenggara Katolik.',
            'KATOLIK-02' => 'Layanan penerbitan surat rekomendasi untuk pengajuan bantuan Guru, diproses oleh Penyelenggara Katolik.',
            'BUDDHA-01' => 'Layanan penerbitan surat rekomendasi untuk pengajuan bantuan (Penyelenggara Buddha), diproses oleh Penyelenggara Buddha.',
            'BUDDHA-02' => 'Layanan penerbitan surat rekomendasi/izin operasional terkait rekomendasi izin operasional lembaga pendidikan keagamaan (penyelenggara buddha), diproses oleh Penyelenggara Buddha.',
            'BUDDHA-03' => 'Layanan verifikasi dan validasi data/dokumen terkait verifikasi dan validasi tanda daftar lembaga keagamaan (penyelenggara buddha), oleh Penyelenggara Buddha.',
        ];

        foreach ($deskripsi as $kodeLayanan => $teks) {
            Layanan::where('kode_layanan', $kodeLayanan)->update(['deskripsi' => $teks]);
        }
    }
}
