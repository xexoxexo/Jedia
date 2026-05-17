@extends('layouts.dashboard')

@section('title', 'IMK Visualization')

@section('content')
    <section class="rounded-xl border border-gray-light bg-white p-6 mb-8">
        <p class="text-xs tracking-wider text-gray uppercase mb-2">IMK - Visualisasi</p>
        <h1 class="text-2xl font-bold text-black mb-2">Ringkasan Hasil SUS</h1>
        <p class="text-gray mb-6">
            Gunakan halaman ini sebagai bahan presentasi internal. Untuk presentasi akhir, visualisasi utama tetap dari
            Google Sheets agar real-time terhadap response Google Form.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="rounded-lg border border-gray-light p-4">
                <p class="text-xs text-gray uppercase">SUS Mean Score</p>
                <p class="text-3xl font-bold text-black">{{ $summary['average_score'] }}</p>
                <p class="text-sm text-gray">Grade {{ $summary['grade'] }} - {{ $summary['adjective'] }}</p>
            </div>
            <div class="rounded-lg border border-gray-light p-4">
                <p class="text-xs text-gray uppercase">Total Data</p>
                <p class="text-3xl font-bold text-black">{{ $summary['total'] }}</p>
                <p class="text-sm text-gray">{{ $summary['real'] }} real / {{ $summary['dummy'] }} dummy</p>
            </div>
            <div class="rounded-lg border border-gray-light p-4">
                <p class="text-xs text-gray uppercase">Acceptance</p>
                <p class="text-3xl font-bold text-black">{{ $summary['adjective'] }}</p>
                <p class="text-sm text-gray">Interpretasi otomatis dari skor SUS rata-rata</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('imk.sus') }}" class="px-4 py-2 rounded-md border border-primary text-primary font-semibold hover:bg-primary hover:text-white">
                Kembali ke Halaman SUS
            </a>
            @if ($sheetUrl !== '')
                <a href="{{ $sheetUrl }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-md bg-primary text-white font-semibold hover:bg-white hover:text-primary border border-primary">
                    Buka Google Sheets Visual
                </a>
            @endif
            @if ($formUrl !== '')
                <a href="{{ $formUrl }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 font-semibold hover:border-primary hover:text-primary">
                    Buka Google Form
                </a>
            @endif
        </div>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="rounded-xl border border-gray-light bg-white p-6">
            <h2 class="text-xl font-bold text-black mb-4">Distribusi Skor Responden</h2>
            <div class="space-y-3">
                @foreach ($summary['score_buckets'] as $label => $value)
                    @php
                        $percentage = $summary['total'] > 0 ? round(($value / $summary['total']) * 100, 1) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-sm text-gray-700 mb-1">
                            <p>{{ $label }}</p>
                            <p>{{ $value }} respon ({{ $percentage }}%)</p>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="h-full bg-primary" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-xl border border-gray-light bg-white p-6">
            <h2 class="text-xl font-bold text-black mb-4">Rata-rata Tiap Pertanyaan (1-5)</h2>
            <div class="space-y-3">
                @foreach ($summary['question_averages'] as $question => $avg)
                    @php
                        $percentage = round(($avg / 5) * 100, 1);
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-sm text-gray-700 mb-1">
                            <p>{{ $question }}</p>
                            <p>{{ $avg }}</p>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="h-full bg-emerald-500" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="rounded-xl border border-gray-light bg-white p-6">
        <h2 class="text-xl font-bold text-black mb-4">Interpretasi Cepat untuk Slide Presentasi</h2>
        <ul class="list-disc ml-6 space-y-2 text-gray-700">
            <li>Skor SUS rata-rata {{ $summary['average_score'] }} menghasilkan grade {{ $summary['grade'] }} ({{ $summary['adjective'] }}).</li>
            <li>Jika skor < 68: rekomendasi redesign prioritas pada navigasi dan efisiensi alur checkout.</li>
            <li>Jika skor 68-79.9: lakukan peningkatan inkonsistensi UI minor dan perjelas feedback sistem.</li>
            <li>Jika skor >= 80: usability sudah sangat baik, fokus ke optimasi fitur dan retensi.</li>
        </ul>
    </section>
@endsection

