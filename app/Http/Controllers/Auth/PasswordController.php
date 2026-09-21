<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * Khusus user yang belum pernah set password sendiri (password_set_at
     * masih null — umumnya akun yang dibuat otomatis lewat Google login,
     * passwordnya random & tidak diketahui siapapun), syarat
     * "current_password" DILEWATI. Selain kasus itu, current_password tetap
     * wajib seperti biasa. Setelah berhasil, password_set_at diisi supaya
     * perubahan berikutnya kembali wajib current_password.
     */
    public function update(Request $request): RedirectResponse
    {
        $belumPernahSetPassword = $request->user()->belumPernahSetPassword();

        $rules = [
            'password' => ['required', Password::defaults(), 'confirmed'],
        ];
        if (!$belumPernahSetPassword) {
            $rules['current_password'] = ['required', 'current_password'];
        }

        $validated = $request->validateWithBag('updatePassword', $rules);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'password_set_at' => now(),
        ]);

        return back()->with('status', 'password-updated');
    }
}
