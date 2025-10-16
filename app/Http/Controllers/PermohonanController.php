<?php

namespace App\Http\Controllers;

use App\Models\LampiranPermohonan;
use App\Models\Layanan;
use App\Models\Permohonan;
use App\Models\Persyaratan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PermohonanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user->role === 'pemohon') {
            $query = Permohonan::where('user_id', $user->id)->with('layanan');
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where('no_tiket', 'like', '%' . $search . '%')
                      ->orWhere('status', 'like', '%' . $search . '%')
                      ->orWhereHas('layanan', function($q) use ($search) {
                          $q->where('nama_layanan', 'like', '%' . $search . '%');
                      });
            }
            $permohonans = $query->paginate(20);
            return view('pemohon.permohonan.index', compact('permohonans'));
        } elseif ($user->role === 'admin') {
            $query = Permohonan::with('layanan', 'user');
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where('no_tiket', 'like', '%' . $search . '%')
                      ->orWhere('status', 'like', '%' . $search . '%')
                      ->orWhereHas('layanan', function($q) use ($search) {
                          $q->where('nama_layanan', 'like', '%' . $search . '%');
                      })
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('name', 'like', '%' . $search . '%');
                      });
            }
            $permohonans = $query->paginate(20);
            return view('admin.permohonan.index', compact('permohonans'));
        } elseif ($user->role === 'petugas') {
            $query = Permohonan::with('layanan', 'user');
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where('no_tiket', 'like', '%' . $search . '%')
                      ->orWhere('status', 'like', '%' . $search . '%')
                      ->orWhereHas('layanan', function($q) use ($search) {
                          $q->where('nama_layanan', 'like', '%' . $search . '%');
                      })
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('name', 'like', '%' . $search . '%');
                      });
            }
            $permohonans = $query->paginate(20);
            return view('petugas.permohonan.index', compact('permohonans'));
        }
        abort(403);
    }

    public function create()
    {
        $layanans = Layanan::with('persyaratan')->get();
        $persyaratanByLayanan = $layanans->mapWithKeys(function ($layanan) {
            return [$layanan->id => $layanan->persyaratan];
        });
        return view('pemohon.permohonan.create', compact('layanans', 'persyaratanByLayanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'unit_kerja' => 'required|string|in:Sub bagian TU,Penma,PAIS,PdPontren,BIMAS Islam,PLHUT',
            'lampiran.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $permohonan = Permohonan::create([
            'user_id' => auth()->id(),
            'layanan_id' => $request->layanan_id,
            'unit_kerja' => $request->unit_kerja,
            'tanggal_pengajuan' => now(),
            'status' => 'diajukan',
            'no_tiket' => 'TKT-' . strtoupper(uniqid()),
        ]);

        $persyaratans = Persyaratan::where('layanan_id', $request->layanan_id)->get();
        foreach ($persyaratans as $index => $persyaratan) {
            if ($request->hasFile('lampiran.' . $index)) {
                $file = $request->file('lampiran.' . $index);
                $path = $file->store('lampiran', 'public');
                LampiranPermohonan::create([
                    'permohonan_id' => $permohonan->id,
                    'persyaratan_id' => $persyaratan->id,
                    'file_path' => $path,
                    'tanggal_unggah' => now(),
                ]);
            }
        }

        // Load lampiranPermohonan with persyaratan
        $permohonan->load('lampiranPermohonan.persyaratan');

        // Generate PDF as proof of submission
        $pdf = Pdf::loadView('pemohon.permohonan.pdf', compact('permohonan'));
        $pdfPath = 'permohonan_pdf/' . $permohonan->no_tiket . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        // Send notification to user (assuming notification system is set up)
        $user = auth()->user();
        $user->notify(new \App\Notifications\PermohonanSubmitted($permohonan, $pdfPath));

        // Return with warning message about ticket number
        return redirect()->route('pemohon.permohonan.index')->with('warning', 'Permohonan berhasil diajukan. No Tiket Anda: ' . $permohonan->no_tiket . '. Bukti pengajuan telah dikirim ke email Anda.');
    }

    public function show(Permohonan $permohonan)
    {
        $user = auth()->user();
        if ($user->role === 'pemohon' && $permohonan->user_id !== $user->id) {
            abort(403);
        }
        $permohonan->load('layanan.persyaratan', 'user', 'lampiranPermohonan.persyaratan');
        if ($user->role === 'pemohon') {
            return view('pemohon.permohonan.show', compact('permohonan'));
        } elseif ($user->role === 'petugas') {
            return view('petugas.permohonan.show', compact('permohonan'));
        } elseif ($user->role === 'admin') {
            return view('admin.permohonan.show', compact('permohonan'));
        }
        abort(403);
    }

    public function updateStatus(Request $request, Permohonan $permohonan)
    {
        $request->validate([
            'status' => 'required|in:Diajukan,Verifikasi,Proses,Selesai,Ditolak',
            'no_tiket_admin' => 'nullable|string|max:50',
            'catatan_admin' => 'nullable|string',
        ]);

        $permohonan->update($request->only(['status', 'no_tiket_admin', 'catatan_admin']));

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function downloadPdf(Permohonan $permohonan)
    {
        $user = auth()->user();
        if ($user && $user->role === 'pemohon' && $permohonan->user_id !== $user->id) {
            abort(403);
        }

        // Load lampiranPermohonan with persyaratan
        $permohonan->load('lampiranPermohonan.persyaratan');

        $pdfPath = storage_path('app/public/permohonan_pdf/' . $permohonan->no_tiket . '.pdf');

        if (!file_exists($pdfPath)) {
            // Generate PDF if not exists
            $pdf = Pdf::loadView('pemohon.permohonan.pdf', compact('permohonan'));
            Storage::disk('public')->put('permohonan_pdf/' . $permohonan->no_tiket . '.pdf', $pdf->output());
        }

        return response()->download($pdfPath, 'bukti_pengajuan_' . $permohonan->no_tiket . '.pdf');
    }

    public function edit(Permohonan $permohonan)
    {
        return view('admin.permohonan.edit', compact('permohonan'));
    }

    public function update(Request $request, Permohonan $permohonan)
    {
        $request->validate([
            'status' => 'required|in:Diajukan,Verifikasi,Proses,Selesai,Ditolak',
            'no_tiket_admin' => 'nullable|string|max:50',
            'catatan_admin' => 'nullable|string',
        ]);

        $permohonan->update($request->only(['status', 'no_tiket_admin', 'catatan_admin']));

        return redirect()->route('admin.permohonan.index')->with('success', 'Permohonan updated successfully.');
    }

    public function destroy(Permohonan $permohonan)
    {
        $user = auth()->user();

        // Delete related lampiran_permohonan and their files
        foreach ($permohonan->lampiranPermohonan as $lampiran) {
            Storage::disk('public')->delete($lampiran->file_path);
            $lampiran->delete();
        }

        // Delete the PDF file if exists
        $pdfPath = 'permohonan_pdf/' . $permohonan->no_tiket . '.pdf';
        Storage::disk('public')->delete($pdfPath);

        $permohonan->delete();

        if ($user->role === 'admin') {
            return redirect()->route('admin.permohonan.index')->with('success', 'Permohonan deleted successfully.');
        } elseif ($user->role === 'petugas') {
            return redirect()->route('petugas.permohonan.index')->with('success', 'Permohonan deleted successfully.');
        }

        abort(403);
    }

    // Guest methods
    public function guestBiodata()
    {
        return view('guest.biodata');
    }

    public function storeBiodata(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nik' => 'required|string|max:16|unique:permohonan,nik',
            'no_hp' => 'required|string|max:15',
            'ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $ktpPath = $request->file('ktp')->store('ktp_guest', 'public');

        session([
            'guest_biodata' => [
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'nik' => $request->nik,
                'no_hp' => $request->no_hp,
                'ktp_path' => $ktpPath,
            ]
        ]);

        return redirect()->route('guest.permohonan.create');
    }

    public function guestCreate()
    {
        if (!session()->has('guest_biodata')) {
            return redirect()->route('guest.permohonan.biodata');
        }

        $layanans = Layanan::with('persyaratan')->get();
        $persyaratanByLayanan = $layanans->mapWithKeys(function ($layanan) {
            return [$layanan->id => $layanan->persyaratan];
        });

        return view('guest.permohonan.create', compact('layanans', 'persyaratanByLayanan'));
    }

    public function guestStore(Request $request)
    {
        if (!session()->has('guest_biodata')) {
            return redirect()->route('guest.permohonan.biodata');
        }

        $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'unit_kerja' => 'required|string|in:Sub bagian TU,Penma,PAIS,PdPontren,BIMAS Islam,PLHUT',
            'deskripsi' => 'nullable|string',
            'lampiran.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $persyaratans = Persyaratan::where('layanan_id', $request->layanan_id)->get();

        // Validate that all required persyaratan have files
        foreach ($persyaratans as $index => $persyaratan) {
            if (!$request->hasFile('lampiran.' . $index)) {
                return back()->withErrors(['lampiran.' . $index => 'Lampiran untuk ' . $persyaratan->nama_persyaratan . ' diperlukan.']);
            }
        }

        $biodata = session('guest_biodata');

        $permohonan = Permohonan::create([
            'user_id' => null, // Guest user
            'layanan_id' => $request->layanan_id,
            'unit_kerja' => $request->unit_kerja,
            'tanggal_pengajuan' => now(),
            'status' => 'diajukan',
            'no_tiket' => 'TKT-' . strtoupper(uniqid()),
            'nama' => $biodata['nama'],
            'alamat' => $biodata['alamat'],
            'nik' => $biodata['nik'],
            'no_hp' => $biodata['no_hp'],
            'ktp_path' => $biodata['ktp_path'],
            'deskripsi' => $request->deskripsi,
        ]);

        foreach ($persyaratans as $index => $persyaratan) {
            if ($request->hasFile('lampiran.' . $index)) {
                $file = $request->file('lampiran.' . $index);
                $path = $file->store('lampiran', 'public');
                LampiranPermohonan::create([
                    'permohonan_id' => $permohonan->id,
                    'persyaratan_id' => $persyaratan->id,
                    'file_path' => $path,
                    'tanggal_unggah' => now(),
                ]);
            }
        }

        // Load lampiranPermohonan with persyaratan
        $permohonan->load('lampiranPermohonan.persyaratan');

        // Generate PDF as proof of submission
        $pdf = Pdf::loadView('pemohon.permohonan.pdf', compact('permohonan'));
        $pdfPath = 'permohonan_pdf/' . $permohonan->no_tiket . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        // Clear session
        session()->forget('guest_biodata');

        // Set session for warning notification and permohonan id for download
        session(['submitted_ticket' => $permohonan->no_tiket, 'submitted_permohonan_id' => $permohonan->id]);

        // Redirect to search ticket page with warning
        return redirect()->route('guest.searchTicket');
    }

    public function searchTicket()
    {
        return view('guest.search_ticket');
    }

    public function showTicket(Request $request)
    {
        $request->validate([
            'no_tiket' => 'required|string',
        ]);

        $permohonan = Permohonan::where('no_tiket', $request->no_tiket)->first();

        if (!$permohonan) {
            return back()->withErrors(['no_tiket' => 'Nomor tiket tidak ditemukan.']);
        }

        return view('guest.show_ticket', compact('permohonan'));
    }
}
