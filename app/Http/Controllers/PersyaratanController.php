<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Persyaratan;
use Illuminate\Http\Request;

class PersyaratanController extends Controller
{
    public function index(Request $request)
    {
        $query = Persyaratan::with('layanan');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nama_persyaratan', 'like', '%' . $search . '%')
                  ->orWhereHas('layanan', function($q) use ($search) {
                      $q->where('nama_layanan', 'like', '%' . $search . '%');
                  });
        }

        $persyaratans = $query->paginate(20);
        return view('admin.persyaratan.index', compact('persyaratans'));
    }

    public function create()
    {
        $layanans = Layanan::all();
        return view('admin.persyaratan.create', compact('layanans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'nama_persyaratan' => 'required|string|max:255',
            'wajib' => 'required|boolean',
        ]);

        Persyaratan::create($request->only(['layanan_id', 'nama_persyaratan', 'wajib']));

        return redirect()->route('admin.persyaratan.index')->with('success', 'Persyaratan created successfully.');
    }

    public function show(Persyaratan $persyaratan)
    {
        return view('admin.persyaratan.show', compact('persyaratan'));
    }

    public function edit(Persyaratan $persyaratan)
    {
        $layanans = Layanan::all();
        return view('admin.persyaratan.edit', compact('persyaratan', 'layanans'));
    }

    public function update(Request $request, Persyaratan $persyaratan)
    {
        $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'nama_persyaratan' => 'required|string|max:255',
            'wajib' => 'required|boolean',
        ]);

        $persyaratan->update($request->only(['layanan_id', 'nama_persyaratan', 'wajib']));

        return redirect()->route('admin.persyaratan.index')->with('success', 'Persyaratan updated successfully.');
    }

    public function destroy(Persyaratan $persyaratan)
    {
        $persyaratan->delete();
        return redirect()->route('admin.persyaratan.index')->with('success', 'Persyaratan deleted successfully.');
    }
}
