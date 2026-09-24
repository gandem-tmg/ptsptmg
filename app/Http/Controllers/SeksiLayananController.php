<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Persyaratan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Petugas seksi hanya boleh mengelola layanan yang seksi_id-nya sama
 * dengan seksi_id akun yang login — bukan layanan seksi lain, dan bukan
 * field identitas/klasifikasi layanan (nama, kode, seksi penanggung
 * jawab, kategori publik), yang tetap kewenangan admin. Yang boleh
 * diubah: deskripsi, tipe pelaksanaan, status "menghasilkan dokumen",
 * poin standar pelayanan, dan daftar persyaratan (bebas tambah/hapus).
 *
 * Setiap update dicatat ke layanan_perubahan_log supaya bisa ditelusuri
 * admin — perubahan langsung tayang, tidak menunggu approval.
 */
class SeksiLayananController extends Controller
{
    /**
     * Field yang boleh diubah petugas seksi. Dipakai juga sebagai daftar
     * field yang dibandingkan saat membuat ringkasan log.
     */
    private const FIELD_LABELS = [
        'deskripsi' => 'Deskripsi',
        'tipe_pelaksanaan' => 'Tipe Pelaksanaan',
        'perlu_dokumen_hasil' => 'Status Menghasilkan Dokumen',
        'sistem_mekanisme_prosedur' => 'Sistem/Mekanisme/Prosedur',
        'jangka_waktu_pelayanan' => 'Jangka Waktu Pelayanan',
        'biaya_tarif' => 'Biaya/Tarif',
        'produk_pelayanan' => 'Produk Pelayanan',
    ];

    public function index(Request $request)
    {
        $seksiId = Auth::user()->seksi_id;

        $query = Layanan::where('seksi_id', $seksiId)->with('persyaratan');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_layanan', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('kode_layanan', 'like', "%{$search}%");
            });
        }

        $layanans = $query->orderBy('nama_layanan')->paginate(20)->withQueryString();

        return view('seksi.layanan.index', compact('layanans'));
    }

    public function show(Layanan $layanan)
    {
        $this->tolakJikaBukanSeksiSendiri($layanan);

        $layanan->load('persyaratan', 'perubahanLog.user');

        return view('seksi.layanan.show', compact('layanan'));
    }

    public function edit(Layanan $layanan)
    {
        $this->tolakJikaBukanSeksiSendiri($layanan);

        $layanan->load('persyaratan');

        return view('seksi.layanan.edit', compact('layanan'));
    }

    public function update(Request $request, Layanan $layanan)
    {
        $this->tolakJikaBukanSeksiSendiri($layanan);

        $request->validate([
            'deskripsi' => 'nullable|string',
            'tipe_pelaksanaan' => 'nullable|in:full_digital,perlu_fisik,sistem_eksternal',
            'perlu_dokumen_hasil' => 'nullable|boolean',
            'sistem_mekanisme_prosedur' => 'nullable|string',
            'jangka_waktu_pelayanan' => 'nullable|string',
            'biaya_tarif' => 'nullable|string',
            'produk_pelayanan' => 'nullable|string',
            'persyaratan' => 'nullable|array',
            'persyaratan.*.nama_persyaratan' => 'required_with:persyaratan|string|max:255',
        ]);

        $sebelum = $layanan->only(array_keys(self::FIELD_LABELS));
        $persyaratanSebelum = $layanan->persyaratan()->get(['id', 'nama_persyaratan', 'wajib'])->toArray();

        // Sengaja tidak memakai $request->only([...]) dari input mentah —
        // field di luar FIELD_LABELS (nama_layanan, kode_layanan, seksi_id,
        // kategori, dst) tidak pernah disentuh di sini sama sekali, jadi
        // walau ada yang mengirim payload dengan field itu (lewat devtools
        // misalnya), tidak akan berpengaruh ke data.
        $layanan->update([
            'deskripsi' => $request->input('deskripsi'),
            'tipe_pelaksanaan' => $request->filled('tipe_pelaksanaan') ? $request->input('tipe_pelaksanaan') : null,
            'perlu_dokumen_hasil' => $request->boolean('perlu_dokumen_hasil'),
            'sistem_mekanisme_prosedur' => $request->input('sistem_mekanisme_prosedur'),
            'jangka_waktu_pelayanan' => $request->input('jangka_waktu_pelayanan'),
            'biaya_tarif' => $request->input('biaya_tarif'),
            'produk_pelayanan' => $request->input('produk_pelayanan'),
        ]);

        // Sinkronkan persyaratan berdasar ID — pola sama seperti
        // Admin\LayananController: yang masih ada di form di-update, yang
        // baru (tanpa ID) dibuat, yang tidak dikirim lagi dianggap dihapus.
        // Ini yang membuat tambah/hapus baris bebas dari sisi seksi.
        $keepIds = [];
        foreach ($request->input('persyaratan', []) as $item) {
            if (empty($item['nama_persyaratan'])) {
                continue;
            }

            if (!empty($item['id'])) {
                $persyaratan = Persyaratan::where('layanan_id', $layanan->id)->find($item['id']);
                if ($persyaratan) {
                    $persyaratan->update([
                        'nama_persyaratan' => $item['nama_persyaratan'],
                        'wajib' => isset($item['wajib']),
                    ]);
                    $keepIds[] = $persyaratan->id;
                    continue;
                }
            }

            $baru = $layanan->persyaratan()->create([
                'nama_persyaratan' => $item['nama_persyaratan'],
                'wajib' => isset($item['wajib']),
            ]);
            $keepIds[] = $baru->id;
        }
        $layanan->persyaratan()->whereNotIn('id', $keepIds)->delete();

        $sesudah = $layanan->fresh()->only(array_keys(self::FIELD_LABELS));
        $persyaratanSesudah = $layanan->persyaratan()->get(['id', 'nama_persyaratan', 'wajib'])->toArray();

        $ringkasan = $this->buatRingkasanPerubahan($sebelum, $sesudah, $persyaratanSebelum, $persyaratanSesudah);
        if ($ringkasan !== null) {
            $layanan->perubahanLog()->create([
                'user_id' => Auth::id(),
                'ringkasan' => $ringkasan,
            ]);
        }

        return redirect()->route('seksi.layanan.show', $layanan)
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    private function tolakJikaBukanSeksiSendiri(Layanan $layanan): void
    {
        if ($layanan->seksi_id !== Auth::user()->seksi_id) {
            abort(403, 'Layanan ini bukan tanggung jawab seksi Anda.');
        }
    }

    /**
     * Bangun ringkasan yang mudah dibaca untuk log, misal:
     * "Deskripsi diubah; Jangka Waktu Pelayanan diubah; 2 persyaratan
     * ditambahkan; 1 persyaratan dihapus". Return null kalau memang
     * tidak ada apa pun yang berubah (submit form tanpa mengubah apa-apa)
     * supaya tidak membuat entri log kosong.
     */
    private function buatRingkasanPerubahan(array $sebelum, array $sesudah, array $persyaratanSebelum, array $persyaratanSesudah): ?string
    {
        $baris = [];

        foreach (self::FIELD_LABELS as $field => $label) {
            $nilaiLama = $sebelum[$field] ?? null;
            $nilaiBaru = $sesudah[$field] ?? null;
            if ((string) $nilaiLama !== (string) $nilaiBaru) {
                $baris[] = "{$label} diubah";
            }
        }

        $idSebelum = collect($persyaratanSebelum)->keyBy('id');
        $idSesudah = collect($persyaratanSesudah)->keyBy('id');

        $ditambah = $idSesudah->keys()->diff($idSebelum->keys())->count();
        $dihapus = $idSebelum->keys()->diff($idSesudah->keys())->count();

        $diubah = 0;
        foreach ($idSebelum as $id => $lama) {
            $baru = $idSesudah->get($id);
            if ($baru && ($lama['nama_persyaratan'] !== $baru['nama_persyaratan'] || (bool) $lama['wajib'] !== (bool) $baru['wajib'])) {
                $diubah++;
            }
        }

        if ($ditambah > 0) {
            $baris[] = "{$ditambah} persyaratan ditambahkan";
        }
        if ($dihapus > 0) {
            $baris[] = "{$dihapus} persyaratan dihapus";
        }
        if ($diubah > 0) {
            $baris[] = "{$diubah} persyaratan diubah";
        }

        return $baris === [] ? null : implode('; ', $baris);
    }
}
