<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">Manajemen Pengguna</p>
                <h2 class="mt-0.5 text-lg font-semibold text-slate-900">{{ __('Edit Pengguna') }}</h2>
            </div>
            <a href="{{ route('admin.users.index') }}" class="secondary-btn">Kembali</a>
        </div>
    </x-slot>

    <div class="workspace-page">
        <div class="max-w-3xl">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="soft-card p-4 sm:p-5">
                @csrf
                @method('PUT')

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="field-label">Nama<span class="required-mark">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                        @error('name')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="field-label">Email<span class="required-mark">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                        @error('email')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-4" x-data="{ role: '{{ old('role', $user->role) }}' }">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="role" class="field-label">Role<span class="required-mark">*</span></label>
                            <select name="role" id="role" x-model="role" class="form-input" required>
                                <option value="">Pilih Role</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>Petugas (PTSP)</option>
                                <option value="petugas_seksi" {{ old('role', $user->role) == 'petugas_seksi' ? 'selected' : '' }}>Petugas Seksi</option>
                                <option value="pimpinan" {{ old('role', $user->role) == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                                <option value="pemohon" {{ old('role', $user->role) == 'pemohon' ? 'selected' : '' }}>Pemohon</option>
                            </select>
                            @error('role')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div x-show="role === 'petugas_seksi'" x-cloak>
                            <label for="seksi_id" class="field-label">Seksi<span class="required-mark">*</span></label>
                            <select name="seksi_id" id="seksi_id" class="form-input">
                                <option value="">Pilih Seksi</option>
                                @foreach($seksis as $seksi)
                                    <option value="{{ $seksi->id }}" {{ old('seksi_id', $user->seksi_id) == $seksi->id ? 'selected' : '' }}>{{ $seksi->nama_seksi }}</option>
                                @endforeach
                            </select>
                            @error('seksi_id')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <label for="no_hp" class="field-label">No HP</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $user->no_hp) }}" class="form-input">
                    @error('no_hp')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="mt-4">
                    <label for="alamat" class="field-label">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" class="form-input">{{ old('alamat', $user->alamat) }}</textarea>
                    @error('alamat')<p class="mt-2 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="mt-5 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('admin.users.index') }}" class="secondary-btn">Batal</a>
                    <button type="submit" class="primary-btn">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
