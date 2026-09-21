<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ auth()->user()->belumPernahSetPassword() ? 'Atur Kata Sandi' : 'Perbarui Kata Sandi' }}
        </h2>

        @if(auth()->user()->belumPernahSetPassword())
            <p class="mt-1 text-sm text-gray-600">
                Akun Anda dibuat lewat masuk dengan Google dan belum punya kata sandi sendiri. Atur kata sandi di sini supaya Anda tetap bisa masuk memakai email dan kata sandi kalau suatu saat masuk dengan Google bermasalah.
            </p>
        @else
            <p class="mt-1 text-sm text-gray-600">
                Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.
            </p>
        @endif
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        @unless(auth()->user()->belumPernahSetPassword())
        <div>
            <x-input-label for="update_password_current_password" value="Kata Sandi Saat Ini" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>
        @endunless

        <div>
            <x-input-label for="update_password_password" value="Kata Sandi Baru" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" value="Konfirmasi Kata Sandi" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Simpan</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >Tersimpan.</p>
            @endif
        </div>
    </form>
</section>
