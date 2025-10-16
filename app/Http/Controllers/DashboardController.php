<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Permohonan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'pemohon') {
            $totalLayanan = Layanan::count();
            $totalPermohonan = Permohonan::where('user_id', $user->id)->count();
            $permohonanDiajukan = Permohonan::where('user_id', $user->id)->where('status', 'Diajukan')->count();
            $permohonanSelesai = Permohonan::where('user_id', $user->id)->where('status', 'Selesai')->count();

            return view('dashboard', compact(
                'totalLayanan',
                'totalPermohonan',
                'permohonanDiajukan',
                'permohonanSelesai'
            ));
        } else {
            $totalLayanan = Layanan::count();
            $totalPermohonan = Permohonan::count();
            $permohonanDiajukan = Permohonan::where('status', 'Diajukan')->count();
            $permohonanSelesai = Permohonan::where('status', 'Selesai')->count();
            $permohonanDitolak = Permohonan::where('status', 'Ditolak')->count();

            return view('dashboard', compact(
                'totalLayanan',
                'totalPermohonan',
                'permohonanDiajukan',
                'permohonanSelesai',
                'permohonanDitolak'
            ));
        }
    }
}
