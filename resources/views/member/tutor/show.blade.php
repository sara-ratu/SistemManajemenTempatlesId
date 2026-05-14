{{-- resources/views/Member/tutor/show.blade.php --}}
{{-- Partial: bagian rating & ulasan — sisipkan ke dalam halaman detail tutor yang sudah ada --}}
@extends('layouts.Member')

@section('title', $tutor->name . ' — Detail Tutor')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4 space-y-6">

    {{-- ── Header profil ──────────────────────────────── --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm flex gap-5">
        <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center text-2xl font-bold text-blue-600 shrink-0">
            {{ strtoupper(substr($tutor->name, 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
            <h1 class="text-xl font-bold text-gray-800">{{ $tutor->name }}</h1>

            {{-- Rating ringkas --}}
            @if ($profile && $profile->rating_count > 0)
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-yellow-400 text-lg leading-none">
                        {{ str_repeat('★', round($profile->rating_avg)) }}<span class="text-gray-200">{{ str_repeat('★', 5 - round($profile->rating_avg)) }}</span>
                    </span>
                    <span class="text-sm font-semibold text-gray-700">{{ number_format($profile->rating_avg, 1) }}</span>
                    <span class="text-sm text-gray-400">({{ $profile->rating_count }} ulasan)</span>
                </div>
            @else
                <p class="text-sm text-gray-400 mt-1">Belum ada ulasan</p>
            @endif

            <p class="text-gray-600 text-sm mt-2">{{ $profile?->bio ?? '-' }}</p>
        </div>

        <div class="shrink-0">
            <a href="{{ route('Member.booking.create', $tutor) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
                Booking Sekarang
            </a>
        </div>
    </div>

    {{-- ── Info tutor ──────────────────────────────────── --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Informasi Tutor</h2>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Harga per jam</p>
                <p class="font-semibold text-gray-800">Rp {{ number_format($profile?->harga_per_jam ?? 0, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Lokasi</p>
                <p class="text-gray-700">{{ $tutor->kota ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Mata Pelajaran</p>
                <div class="flex flex-wrap gap-1 mt-1">
                    @forelse ($profile?->subjects ?? [] as $subject)
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">{{ $subject->nama }}</span>
                    @empty
                        <span class="text-gray-400">—</span>
                    @endforelse
                </div>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Pengalaman</p>
                <p class="text-gray-700">{{ $profile?->pengalaman_tahun ?? 0 }} tahun</p>
            </div>
        </div>
    </div>

    {{-- ── Jadwal tersedia ─────────────────────────────── --}}
    @if ($profile && $profile->schedules->count())
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Jadwal Tersedia</h2>
            <div class="flex flex-wrap gap-2">
                @foreach ($profile->schedules->where('is_available', true) as $jadwal)
                    <span class="px-3 py-1 bg-green-50 border border-green-200 text-green-700 rounded-full text-xs font-medium">
                        {{ $jadwal->hari }}
                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ── Ulasan ──────────────────────────────────────── --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
        <h2 class="text-base font-semibold text-gray-800 mb-5">
            Ulasan Member
            @if ($reviews->count())
                <span class="text-gray-400 font-normal text-sm">({{ $reviews->total() }})</span>
            @endif
        </h2>

        @forelse ($reviews as $r)
            <div class="border-b border-gray-100 pb-4 mb-4 last:border-0 last:mb-0 last:pb-0">
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600">
                        {{ strtoupper(substr($r->Member->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $r->Member->name }}</p>
                        <p class="text-xs text-gray-400">{{ $r->created_at->isoFormat('D MMM Y') }}</p>
                    </div>
                    <div class="ml-auto text-yellow-400 tracking-tighter">
                        {{ str_repeat('★', $r->rating) }}<span class="text-gray-200">{{ str_repeat('★', 5 - $r->rating) }}</span>
                    </div>
                </div>
                @if ($r->komentar)
                    <p class="text-sm text-gray-600 mt-2 ml-11">{{ $r->komentar }}</p>
                @endif
            </div>
        @empty
            <p class="text-gray-400 text-sm">Belum ada ulasan untuk tutor ini.</p>
        @endforelse

        @if ($reviews->hasPages())
            <div class="mt-4">{{ $reviews->links() }}</div>
        @endif
    </div>

</div>
@endsection
