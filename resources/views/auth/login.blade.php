<x-guest-layout>
    <div class="mb-5 text-center">
        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-600">Selamat Datang</p>
        <h2 class="mt-1 text-xl font-bold text-slate-900 sm:text-2xl">Masuk ke akun Anda</h2>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="mt-1 block w-full"
                type="password"
                name="password"
                required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div class="flex items-center justify-between gap-3">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                <span class="ms-2 text-sm text-slate-600">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-emerald-600 hover:text-emerald-700" href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <div class="space-y-2.5 pt-1">
            <x-primary-button class="w-full justify-center">
                Masuk
            </x-primary-button>

            <a href="{{ route('auth.google.redirect') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24"><path fill="#4285F4" d="M23.52 12.27c0-.85-.08-1.67-.22-2.45H12v4.64h6.48a5.54 5.54 0 01-2.4 3.63v3h3.88c2.27-2.09 3.56-5.17 3.56-8.82z"/><path fill="#34A853" d="M12 24c3.24 0 5.96-1.07 7.95-2.91l-3.88-3c-1.08.72-2.45 1.15-4.07 1.15-3.13 0-5.78-2.11-6.73-4.96H1.26v3.1A11.99 11.99 0 0012 24z"/><path fill="#FBBC05" d="M5.27 14.28A7.2 7.2 0 014.9 12c0-.79.14-1.56.37-2.28v-3.1H1.26A11.99 11.99 0 000 12c0 1.94.46 3.77 1.26 5.38l4.01-3.1z"/><path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.44-3.44C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.69 1.26 6.62l4.01 3.1C6.22 6.86 8.87 4.75 12 4.75z"/></svg>
                Masuk dengan Google
            </a>
        </div>
    </form>

    @if (Route::has('register'))
        <p class="mt-4 text-center text-sm text-slate-600">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="font-semibold text-emerald-600 hover:text-emerald-700">Daftar di sini</a>
        </p>
    @endif
</x-guest-layout>
