<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\TemplateSurat;
use Illuminate\Http\Request;

class TemplateSuratController extends Controller
{
    public function index()
    {
        $templates = TemplateSurat::with('layanan')->latest()->paginate(20);
        return view('admin.template_surat.index', compact('templates'));
    }

    public function create()
    {
        $layanans = Layanan::orderBy('nama_layanan')->get();
        return view('admin.template_surat.create', compact('layanans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'judul_template' => 'required|string|max:255',
            'isi_template' => 'required|string',
            'aktif' => 'nullable|boolean',
        ]);

        TemplateSurat::create([
            'layanan_id' => $request->layanan_id,
            'judul_template' => $request->judul_template,
            'isi_template' => $request->isi_template,
            'aktif' => $request->boolean('aktif', true),
        ]);

        return redirect()->route('admin.template-surat.index')->with('success', 'Template surat berhasil dibuat.');
    }

    public function edit(TemplateSurat $templateSurat)
    {
        $layanans = Layanan::orderBy('nama_layanan')->get();
        return view('admin.template_surat.edit', ['template' => $templateSurat, 'layanans' => $layanans]);
    }

    public function update(Request $request, TemplateSurat $templateSurat)
    {
        $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'judul_template' => 'required|string|max:255',
            'isi_template' => 'required|string',
            'aktif' => 'nullable|boolean',
        ]);

        $templateSurat->update([
            'layanan_id' => $request->layanan_id,
            'judul_template' => $request->judul_template,
            'isi_template' => $request->isi_template,
            'aktif' => $request->boolean('aktif', true),
        ]);

        return redirect()->route('admin.template-surat.index')->with('success', 'Template surat berhasil diperbarui.');
    }

    public function destroy(TemplateSurat $templateSurat)
    {
        $templateSurat->delete();
        return redirect()->route('admin.template-surat.index')->with('success', 'Template surat berhasil dihapus.');
    }
}
