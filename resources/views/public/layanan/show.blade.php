@auth
    <x-app-layout>
        <x-slot name="header">
            <h2 class="text-xl font-semibold text-slate-900">Detail Layanan</h2>
        </x-slot>
        @include('public.layanan._show-content')
    </x-app-layout>
@else
    <x-public-layout>
        @include('public.layanan._show-content')
    </x-public-layout>
@endauth
