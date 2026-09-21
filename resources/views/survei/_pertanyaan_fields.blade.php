@php
    // Ikon & warna per level skala 1-4, dari "kurang" ke "sangat baik" —
    // dipakai supaya pengisian lebih cepat dibaca sekilas (bukan cuma teks).
    $skalaStyle = [
        1 => ['ring' => 'has-[:checked]:border-rose-400 has-[:checked]:bg-rose-50', 'dot' => 'bg-rose-400', 'text' => 'text-rose-700'],
        2 => ['ring' => 'has-[:checked]:border-amber-400 has-[:checked]:bg-amber-50', 'dot' => 'bg-amber-400', 'text' => 'text-amber-700'],
        3 => ['ring' => 'has-[:checked]:border-sky-400 has-[:checked]:bg-sky-50', 'dot' => 'bg-sky-400', 'text' => 'text-sky-700'],
        4 => ['ring' => 'has-[:checked]:border-emerald-400 has-[:checked]:bg-emerald-50', 'dot' => 'bg-emerald-500', 'text' => 'text-emerald-700'],
    ];
@endphp

@foreach($pertanyaans as $pertanyaan)
    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition sm:p-5" x-data="{ answered: false }">
        <div class="mb-4 flex items-start gap-3">
            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-xs font-bold transition"
                  :class="answered ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700'">
                <svg x-show="answered" x-cloak class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <span x-show="!answered">{{ $loop->iteration }}</span>
            </span>
            <p class="pt-0.5 text-sm font-semibold leading-snug text-slate-800">{{ $pertanyaan->teks_pertanyaan }}</p>
        </div>

        @if($pertanyaan->tipe === 'skala_4')
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4" @change="answered = true">
                @foreach($pertanyaan->labelSkala() as $i => $label)
                    @php $style = $skalaStyle[$i + 1] ?? $skalaStyle[4]; @endphp
                    <label class="group relative flex cursor-pointer flex-col items-center gap-1.5 rounded-xl border-2 border-slate-200 bg-slate-50 px-2 py-3 text-center transition hover:border-slate-300 {{ $style['ring'] }}">
                        <input type="radio" name="jawaban[{{ $pertanyaan->id }}]" value="{{ $i + 1 }}" required class="peer sr-only">
                        <span class="h-2 w-2 rounded-full {{ $style['dot'] }} opacity-40 peer-checked:opacity-100"></span>
                        <span class="text-[12px] leading-tight text-slate-600 peer-checked:font-semibold {{ $style['text'] }}">
                            {{ $label }}
                        </span>
                    </label>
                @endforeach
            </div>
        @elseif($pertanyaan->tipe === 'pilihan_ganda')
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2" @change="answered = true">
                @foreach(($pertanyaan->opsi_jawaban ?: []) as $opsi)
                    <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border-2 border-slate-200 bg-slate-50 p-3 text-sm text-slate-700 transition hover:border-emerald-300 has-[:checked]:border-emerald-400 has-[:checked]:bg-emerald-50 has-[:checked]:font-medium has-[:checked]:text-emerald-800">
                        <input type="radio" name="jawaban[{{ $pertanyaan->id }}]" value="{{ $opsi }}" required class="shrink-0 text-emerald-600 focus:ring-emerald-500">
                        {{ $opsi }}
                    </label>
                @endforeach
            </div>
        @else
            <textarea name="jawaban[{{ $pertanyaan->id }}]" rows="3" placeholder="Tulis komentar/masukan Anda (opsional)..."
                      @input="answered = $el.value.length > 0"
                      class="w-full rounded-xl border-slate-200 text-sm placeholder:text-slate-400 focus:border-emerald-500 focus:ring-emerald-200"></textarea>
        @endif
    </div>
@endforeach
