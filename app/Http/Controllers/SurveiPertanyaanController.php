<?php

namespace App\Http\Controllers;

use App\Models\SurveiPertanyaan;
use Illuminate\Http\Request;

/**
 * CRUD pertanyaan survei — admin-only. Dibuat dinamis supaya begitu
 * aturan/indikator SKM resmi yang mau dipakai sudah dikoordinasikan,
 * tinggal disesuaikan di sini tanpa ubah kode.
 */
class SurveiPertanyaanController extends Controller
{
    public function index()
    {
        $pertanyaans = SurveiPertanyaan::orderBy('urutan')->orderBy('id')->get();

        return view('admin.survei_pertanyaan.index', compact('pertanyaans'));
    }

    public function create()
    {
        return view('admin.survei_pertanyaan.create');
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);
        SurveiPertanyaan::create($data);

        return redirect()->route('admin.survei-pertanyaan.index')->with('success', 'Pertanyaan survei ditambahkan.');
    }

    public function edit(SurveiPertanyaan $surveiPertanyaan)
    {
        return view('admin.survei_pertanyaan.edit', ['pertanyaan' => $surveiPertanyaan]);
    }

    public function update(Request $request, SurveiPertanyaan $surveiPertanyaan)
    {
        $data = $this->validasi($request);
        $surveiPertanyaan->update($data);

        return redirect()->route('admin.survei-pertanyaan.index')->with('success', 'Pertanyaan survei diperbarui.');
    }

    public function destroy(SurveiPertanyaan $surveiPertanyaan)
    {
        // Soft-remove: dinonaktifkan saja kalau sudah pernah dipakai (ada jawaban
        // yang menempel), supaya histori jawaban lama tidak jadi yatim/rusak.
        if ($surveiPertanyaan->jawaban()->exists()) {
            $surveiPertanyaan->update(['aktif' => false]);
            return back()->with('success', 'Pertanyaan sudah pernah dijawab responden, jadi cuma dinonaktifkan (bukan dihapus) supaya histori jawaban tetap utuh.');
        }

        $surveiPertanyaan->delete();
        return back()->with('success', 'Pertanyaan survei dihapus.');
    }

    private function validasi(Request $request): array
    {
        $data = $request->validate([
            'teks_pertanyaan' => 'required|string|max:500',
            'tipe' => 'required|in:skala_4,pilihan_ganda,teks',
            'jenis_survei' => 'required|in:per_layanan,umum,keduanya',
            'urutan' => 'nullable|integer|min:0',
            'aktif' => 'nullable|boolean',
            'opsi_jawaban_text' => 'nullable|string',
        ]);

        $data['aktif'] = $request->boolean('aktif', true);
        $data['urutan'] = $data['urutan'] ?? 0;

        // opsi_jawaban cuma relevan untuk skala_4 (custom label 4 opsi) dan
        // pilihan_ganda (opsi bebas) — untuk tipe teks, dikosongkan. Admin
        // isi satu opsi per baris di textarea, di sini baru dipecah jadi array.
        if ($data['tipe'] === 'teks') {
            $data['opsi_jawaban'] = null;
        } else {
            $baris = preg_split('/\r\n|\r|\n/', (string) ($request->input('opsi_jawaban_text') ?? ''));
            $opsi = array_values(array_filter(array_map('trim', $baris), fn ($v) => $v !== ''));
            $data['opsi_jawaban'] = $opsi ?: null;
        }

        unset($data['opsi_jawaban_text']);

        return $data;
    }
}
