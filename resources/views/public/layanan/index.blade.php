@auth
    <x-app-layout>
        <x-slot name="header">
            <h2 class="text-xl font-semibold text-slate-900">Ajukan Layanan Baru</h2>
        </x-slot>
        @include('public.layanan._index-content')
    </x-app-layout>
@else
    <x-public-layout>
        @include('public.layanan._index-content')
    </x-public-layout>
@endauth
