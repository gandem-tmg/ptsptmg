<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Persyaratan;
use App\Models\Seksi;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index(Request $request)
    {
        $query = Layanan::with('persyaratan', 'seksi');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nama_layanan', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhere('kode_layanan', 'like', '%' . $search . '%');
        }

        $layanans = $query->paginate(20);
        $belumDikategorikanCount = Layanan::whereNull('kategori')->count();
        $prefix = request()->route()->getPrefix();
        if (str_contains($prefix, 'petugas')) {
            return view('petugas.layanan.index', compact('layanans', 'belumDikategorikanCount'));
        }
        return view('admin.layanan.index', compact('layanans', 'belumDikategorikanCount'));
    }

    public function create()
    {
        $seksis = Seksi::orderBy('nama_seksi')->get();
        return view('admin.layanan.create', compact('seksis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kode_layanan' => 'required|string|max:50|unique:layanan',
            'seksi_id' => 'required|exists:seksi,id',
            'tipe_pelaksanaan' => 'nullable|in:full_digital,perlu_fisik,sistem_eksternal',
            'perlu_dokumen_hasil' => 'nullable|boolean',
            'sistem_mekanisme_prosedur' => 'nullable|string',
            'jangka_waktu_pelayanan' => 'nullable|string',
            'biaya_tarif' => 'nullable|string',
            'produk_pelayanan' => 'nullable|string',
            'kategori' => 'nullable|string|in:' . implode(',', array_keys(config('klasifikasi_layanan.kategori'))),
            'subkategori' => 'nullable|string',
            'target_pengguna' => 'nullable|array',
            'target_pengguna.*' => 'string|in:' . implode(',', array_keys(config('klasifikasi_layanan.target_pengguna'))),
            'tag_pencarian' => 'nullable|string',
            'jenis_layanan' => 'nullable|string|in:' . implode(',', array_keys(config('klasifikasi_layanan.jenis_layanan'))),
            'persyaratan' => 'nullable|array',
            'persyaratan.*.nama_persyaratan' => 'required_with:persyaratan|string|max:255',
        ]);

        $layanan = Layanan::create($request->only([
            'nama_layanan', 'deskripsi', 'kode_layanan', 'seksi_id',
            'sistem_mekanisme_prosedur', 'jangka_waktu_pelayanan', 'biaya_tarif', 'produk_pelayanan',
            'kategori', 'subkategori', 'tag_pencarian', 'jenis_layanan',
        ]) + [
            'tipe_pelaksanaan' => $request->filled('tipe_pelaksanaan') ? $request->input('tipe_pelaksanaan') : null,
            'perlu_dokumen_hasil' => $request->boolean('perlu_dokumen_hasil'),
            'target_pengguna' => $request->input('target_pengguna', []),
        ]);

        foreach ($request->input('persyaratan', []) as $item) {
            if (!empty($item['nama_persyaratan'])) {
                $layanan->persyaratan()->create([
                    'nama_persyaratan' => $item['nama_persyaratan'],
                    'wajib' => isset($item['wajib']),
                ]);
            }
        }

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan beserta persyaratannya berhasil dibuat.');
    }

    public function show(Layanan $layanan)
    {
        $layanan->load('seksi', 'persyaratan', 'perubahanLog.user');
        $prefix = request()->route()->getPrefix();
        if (str_contains($prefix, 'petugas')) {
            return view('petugas.layanan.show', compact('layanan'));
        }
        return view('admin.layanan.show', compact('layanan'));
    }

    public function edit(Layanan $layanan)
    {
        $layanan->load('persyaratan');
        $seksis = Seksi::orderBy('nama_seksi')->get();
        return view('admin.layanan.edit', compact('layanan', 'seksis'));
    }

    public function update(Request $request, Layanan $layanan)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kode_layanan' => 'required|string|max:50|unique:layanan,kode_layanan,' . $layanan->id,
            'seksi_id' => 'required|exists:seksi,id',
            'tipe_pelaksanaan' => 'nullable|in:full_digital,perlu_fisik,sistem_eksternal',
            'perlu_dokumen_hasil' => 'nullable|boolean',
            'sistem_mekanisme_prosedur' => 'nullable|string',
            'jangka_waktu_pelayanan' => 'nullable|string',
            'biaya_tarif' => 'nullable|string',
            'produk_pelayanan' => 'nullable|string',
            'kategori' => 'nullable|string|in:' . implode(',', array_keys(config('klasifikasi_layanan.kategori'))),
            'subkategori' => 'nullable|string',
            'target_pengguna' => 'nullable|array',
            'target_pengguna.*' => 'string|in:' . implode(',', array_keys(config('klasifikasi_layanan.target_pengguna'))),
            'tag_pencarian' => 'nullable|string',
            'jenis_layanan' => 'nullable|string|in:' . implode(',', array_keys(config('klasifikasi_layanan.jenis_layanan'))),
            'persyaratan' => 'nullable|array',
            'persyaratan.*.nama_persyaratan' => 'required_with:persyaratan|string|max:255',
        ]);

        $layanan->update($request->only([
            'nama_layanan', 'deskripsi', 'kode_layanan', 'seksi_id',
            'sistem_mekanisme_prosedur', 'jangka_waktu_pelayanan', 'biaya_tarif', 'produk_pelayanan',
            'kategori', 'subkategori', 'tag_pencarian', 'jenis_layanan',
        ]) + [
            'tipe_pelaksanaan' => $request->filled('tipe_pelaksanaan') ? $request->input('tipe_pelaksanaan') : null,
            'perlu_dokumen_hasil' => $request->boolean('perlu_dokumen_hasil'),
            'target_pengguna' => $request->input('target_pengguna', []),
        ]);

        // Sinkronkan persyaratan berdasar ID: yang masih ada di form di-update,
        // yang baru (tanpa ID) dibuat, yang tidak dikirim lagi dianggap dihapus.
        $keepIds = [];
        foreach ($request->input('persyaratan', []) as $item) {
            if (empty($item['nama_persyaratan'])) {
                continue;
            }

            $persyaratan = null;
            if (!empty($item['id'])) {
                $persyaratan = Persyaratan::where('id', $item['id'])->where('layanan_id', $layanan->id)->first();
            }

            if ($persyaratan) {
                $persyaratan->update([
                    'nama_persyaratan' => $item['nama_persyaratan'],
                    'wajib' => isset($item['wajib']),
                ]);
            } else {
                $persyaratan = $layanan->persyaratan()->create([
                    'nama_persyaratan' => $item['nama_persyaratan'],
                    'wajib' => isset($item['wajib']),
                ]);
            }

            $keepIds[] = $persyaratan->id;
        }
        $layanan->persyaratan()->whereNotIn('id', $keepIds)->delete();

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan beserta persyaratannya berhasil diperbarui.');
    }

    public function destroy(Layanan $layanan)
    {
        $layanan->delete();
        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil dihapus.');
    }
}
