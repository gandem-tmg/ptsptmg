<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl font-semibold text-slate-900">Daftar Pengguna</h2>
            <a href="{{ route('admin.users.create') }}" class="rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700 sm:px-3.5 sm:text-sm">
                + Tambah Pengguna
            </a>
        </div>
    </x-slot>

    <div class="space-y-3 py-2">
        @if(session('success'))
            <div class="flash-banner rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        @php
            $roleBadge = [
                'admin' => ['Admin', 'bg-rose-100 text-rose-700'],
                'petugas' => ['Petugas (PTSP)', 'bg-sky-100 text-sky-700'],
                'petugas_seksi' => ['Petugas Seksi', 'bg-violet-100 text-violet-700'],
                'pimpinan' => ['Pimpinan', 'bg-amber-100 text-amber-700'],
                'pemohon' => ['Pemohon', 'bg-emerald-100 text-emerald-700'],
            ];
        @endphp

        <x-data-table :empty="$users->isEmpty()" empty-message="Belum ada pengguna.">
            <thead>
                <tr>
                    <th class="w-10">No</th>
                    <th>Nama</th>
                    <th>Kontak</th>
                    <th>Role</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    @php [$roleLabel, $roleClass] = $roleBadge[$user->role] ?? [ucfirst(str_replace('_', ' ', $user->role)), 'bg-slate-100 text-slate-600']; @endphp
                    <tr class="row-clickable" onclick="location.href='{{ route('admin.users.show', $user) }}'">
                        <td class="text-slate-400">{{ $loop->iteration }}</td>
                        <td>
                            <p class="font-medium text-slate-900">{{ $user->name }}</p>
                            @if($user->alamat)
                            <p class="mt-0.5 text-xs text-slate-400">{{ Str::limit($user->alamat, 40) }}</p>
                            @endif
                        </td>
                        <td>
                            <p>{{ $user->email }}</p>
                            <p class="mt-0.5 text-xs text-slate-400">{{ $user->no_hp ?: '-' }}</p>
                        </td>
                        <td>
                            <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide {{ $roleClass }}">
                                {{ $roleLabel }}
                            </span>
                        </td>
                        <td class="text-right" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.users.show', $user) }}" title="Lihat" class="icon-action icon-action-view">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" title="Edit" class="icon-action icon-action-edit">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus" class="icon-action icon-action-danger">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-data-table>
    </div>
</x-app-layout>
