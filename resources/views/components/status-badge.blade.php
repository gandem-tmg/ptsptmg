@props(['status'])

@php
$map = [
    'diajukan' => ['Diajukan', 'bg-yellow-100 text-yellow-800'],
    'verifikasi_ptsp' => ['Verifikasi PTSP', 'bg-blue-100 text-blue-800'],
    'dikembalikan' => ['Dikembalikan', 'bg-red-100 text-red-800'],
    'didisposisikan' => ['Didisposisikan', 'bg-emerald-100 text-emerald-800'],
    'diproses_seksi' => ['Diproses Seksi', 'bg-purple-100 text-purple-800'],
    'selesai_seksi' => ['Selesai di Seksi', 'bg-cyan-100 text-cyan-800'],
    'verifikasi_akhir' => ['Verifikasi Akhir', 'bg-sky-100 text-sky-800'],
    'selesai' => ['Selesai', 'bg-green-100 text-green-800'],
    'ditolak' => ['Ditolak', 'bg-red-100 text-red-800'],
    'dibatalkan' => ['Dibatalkan', 'bg-slate-100 text-slate-600'],
    // status lama, dipertahankan untuk kompatibilitas data existing
    'verifikasi' => ['Verifikasi', 'bg-blue-100 text-blue-800'],
    'proses' => ['Proses', 'bg-purple-100 text-purple-800'],
];
[$label, $classes] = $map[$status] ?? [ucfirst(str_replace('_', ' ', $status)), 'bg-gray-100 text-gray-800'];
@endphp

<span {{ $attributes->merge(['class' => "px-2 py-0.5 inline-flex text-[11px] leading-5 font-semibold rounded-full transition-colors duration-150 $classes"]) }}>
    {{ $label }}
</span>
