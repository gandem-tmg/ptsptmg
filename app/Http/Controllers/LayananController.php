<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index(Request $request)
    {
        $query = Layanan::with('persyaratan');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nama_layanan', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhere('kode_layanan', 'like', '%' . $search . '%');
        }

        $layanans = $query->paginate(20);
        $prefix = request()->route()->getPrefix();
        if (str_contains($prefix, 'petugas')) {
            return view('petugas.layanan.index', compact('layanans'));
        }
        return view('admin.layanan.index', compact('layanans'));
    }

    public function create()
    {
        return view('admin.layanan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kode_layanan' => 'required|string|max:50|unique:layanan',
        ]);

        Layanan::create($request->only(['nama_layanan', 'deskripsi', 'kode_layanan']));

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan created successfully.');
    }

    public function show(Layanan $layanan)
    {
        $prefix = request()->route()->getPrefix();
        if (str_contains($prefix, 'petugas')) {
            return view('petugas.layanan.show', compact('layanan'));
        }
        return view('admin.layanan.show', compact('layanan'));
    }

    public function edit(Layanan $layanan)
    {
        return view('admin.layanan.edit', compact('layanan'));
    }

    public function update(Request $request, Layanan $layanan)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kode_layanan' => 'required|string|max:50|unique:layanan,kode_layanan,' . $layanan->id,
        ]);

        $layanan->update($request->only(['nama_layanan', 'deskripsi', 'kode_layanan']));

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan updated successfully.');
    }

    public function destroy(Layanan $layanan)
    {
        $layanan->delete();
        return redirect()->route('admin.layanan.index')->with('success', 'Layanan deleted successfully.');
    }
}
