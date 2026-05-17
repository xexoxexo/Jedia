@extends('layouts.dashboard')

@section('title', 'IMK SUS Questionnaire')

@section('content')
    <section class="rounded-xl border border-gray-light bg-white p-6 mb-8">
        <p class="text-xs tracking-wider text-gray uppercase mb-2">IMK - UTS Toolkit</p>
        <h1 class="text-2xl font-bold text-black mb-2">System Usability Scale (SUS)</h1>
        <p class="text-gray mb-6">
            Halaman ini menyiapkan kebutuhan UTS IMK: kuesioner SUS 10 butir, target data minimal 50 responden,
            dan pengantar visualisasi untuk presentasi minggu depan.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <div class="rounded-lg border border-gray-light p-4">
                <p class="text-xs text-gray uppercase">Total Responses</p>
                <p class="text-2xl font-bold text-black">{{ $summary['total'] }}</p>
            </div>
            <div class="rounded-lg border border-gray-light p-4">
                <p class="text-xs text-gray uppercase">Real Responses</p>
                <p class="text-2xl font-bold text-black">{{ $summary['real'] }}</p>
            </div>
            <div class="rounded-lg border border-gray-light p-4">
                <p class="text-xs text-gray uppercase">Dummy Responses</p>
                <p class="text-2xl font-bold text-black">{{ $summary['dummy'] }}</p>
            </div>
            <div class="rounded-lg border border-gray-light p-4">
                <p class="text-xs text-gray uppercase">SUS Average</p>
                <p class="text-2xl font-bold text-black">{{ $summary['average_score'] }}</p>
                <p class="text-sm text-gray">Grade {{ $summary['grade'] }} ({{ $summary['adjective'] }})</p>
            </div>
        </div>

        <div class="rounded-lg bg-slate-50 border border-slate-200 p-4 mb-6">
            <p class="font-semibold text-black mb-2">Target Pengumpulan Data (50 Respon)</p>
            <p class="text-sm text-gray mb-2">
                Default rencana: 35 real responden + 15 dummy terlabel untuk menutup kekurangan sebelum deadline.
            </p>
            <div class="w-full bg-white border border-gray-light rounded-full h-3 mb-2 overflow-hidden">
                <div class="bg-primary h-full" style="width: {{ min(100, ($summary['total'] / 50) * 100) }}%"></div>
            </div>
            <p class="text-xs text-gray">{{ $summary['total'] }} / 50 data terkumpul</p>
        </div>

        <div class="flex flex-wrap gap-3">
            @if ($formUrl !== '')
                <a href="{{ $formUrl }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-md bg-primary text-white font-semibold hover:bg-white hover:text-primary border border-primary">
                    Buka Google Form SUS
                </a>
            @else
                <span class="px-4 py-2 rounded-md border border-yellow-300 bg-yellow-50 text-yellow-700 text-sm">
                    Set env `IMK_SUS_FORM_URL` untuk tombol Google Form.
                </span>
            @endif

            @if ($sheetUrl !== '')
                <a href="{{ $sheetUrl }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-md border border-primary text-primary font-semibold hover:bg-primary hover:text-white">
                    Buka Google Sheets Response
                </a>
            @else
                <span class="px-4 py-2 rounded-md border border-yellow-300 bg-yellow-50 text-yellow-700 text-sm">
                    Set env `IMK_SUS_SHEET_URL` untuk tombol visualisasi.
                </span>
            @endif

            <a href="{{ route('imk.download.question-bank') }}" class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 font-semibold hover:border-primary hover:text-primary">
                Download Bank Pertanyaan SUS
            </a>

            <a href="{{ route('imk.download.responses') }}" class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 font-semibold hover:border-primary hover:text-primary">
                Download Sample 50 Data
            </a>
        </div>
    </section>

    <section class="rounded-xl border border-gray-light bg-white p-6 mb-8">
        <h2 class="text-xl font-bold text-black mb-4">Daftar 10 Pertanyaan SUS</h2>
        <ol class="list-decimal ml-6 space-y-2 text-gray-700">
            <li>Saya ingin menggunakan website ini sesering mungkin.</li>
            <li>Saya merasa website ini terlalu rumit untuk digunakan.</li>
            <li>Saya merasa website ini mudah digunakan.</li>
            <li>Saya membutuhkan bantuan orang lain untuk menggunakan website ini.</li>
            <li>Fitur-fitur website ini terintegrasi dengan baik.</li>
            <li>Saya merasa ada banyak inkonsistensi pada website ini.</li>
            <li>Saya membayangkan kebanyakan orang dapat belajar menggunakan website ini dengan cepat.</li>
            <li>Saya merasa website ini membingungkan saat digunakan.</li>
            <li>Saya merasa percaya diri saat menggunakan website ini.</li>
            <li>Saya perlu belajar banyak hal sebelum bisa menggunakan website ini.</li>
        </ol>
    </section>

    <section class="rounded-xl border border-gray-light bg-white p-6">
        <h2 class="text-xl font-bold text-black mb-4">Pertanyaan Profil Tambahan (3-5 butir)</h2>
        <ul class="list-disc ml-6 space-y-2 text-gray-700">
            <li>Peran Anda: Mahasiswa / Pelajar / Umum.</li>
            <li>Frekuensi belanja online per bulan: 1-2x, 3-5x, >5x.</li>
            <li>Pengalaman menggunakan marketplace serupa: Rendah / Sedang / Tinggi.</li>
            <li>Perangkat utama saat mengakses: Mobile / Desktop.</li>
            <li>Koneksi internet yang biasa digunakan: WiFi / Data Seluler.</li>
        </ul>
    </section>
@endsection

