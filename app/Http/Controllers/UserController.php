<?php

namespace App\Http\Controllers;

use App\Models\Seksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Daftar role yang valid — HARUS sinkron dengan enum kolom users.role
     * (lihat migration add_seksi_and_new_roles_to_users_table). Sebelumnya
     * validasi di sini cuma mengizinkan admin/petugas/pemohon, padahal
     * sistem sudah lama punya role petugas_seksi & pimpinan (dipakai di
     * seluruh alur disposisi ke seksi) — akibatnya admin TIDAK BISA bikin
     * akun petugas_seksi/pimpinan lewat halaman ini sama sekali.
     */
    private const ROLES = ['admin', 'petugas', 'petugas_seksi', 'pemohon', 'pimpinan'];

    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $seksis = Seksi::orderBy('nama_seksi')->get();
        return view('admin.users.create', compact('seksis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:' . implode(',', self::ROLES),
            // Wajib diisi kalau role = petugas_seksi (petugas seksi tanpa
            // seksi_id tidak akan pernah kebagian permohonan apa pun).
            'seksi_id' => 'required_if:role,petugas_seksi|nullable|exists:seksi,id',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'seksi_id' => $request->role === 'petugas_seksi' ? $request->seksi_id : null,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dibuat.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $seksis = Seksi::orderBy('nama_seksi')->get();
        return view('admin.users.edit', compact('user', 'seksis'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:' . implode(',', self::ROLES),
            'seksi_id' => 'required_if:role,petugas_seksi|nullable|exists:seksi,id',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'seksi_id' => $request->role === 'petugas_seksi' ? $request->seksi_id : null,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
