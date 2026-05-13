@extends('layouts.app')

@section('title', 'Pendapatan Saya')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Pendapatan Saya 💰</h1>
            <p class="text-sm text-gray-500 mt-1">Riwayat honor mengajar dan status pembayaran</p>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Total Diterima</div>
                <div class="text-2xl font-bold text-green-600">
                    Rp {{ number_format($totalDiterima ?? 0, 0, ',', '.') }}
                </div>
                <div class="text-xs text-gray-400 mt-1">Sepanjang waktu</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Bulan Ini</div>
                <div class="text-2xl font-bold text-blue-600">
                    Rp {{ number_format($totalBulanIni ?? 0, 0, ',', '.') }}
                </div>
                <div class="text-xs text-gray-400 mt-1">{{ now()->format('F Y') }}</div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Menunggu Transfer</div>
                <div class="text-2xl font-bold text-yellow-600">
                    Rp {{ number_format($pending ?? 0, 0, ',', '.') }}
                </div>
                <div class="text-xs text-gray-400 mt-1">Akan diproses segera</div>
            </div>
        </div>

        {{-- Info split billing --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl px-5 py-4 mb-8 flex gap-3 items-start">
            <span class="text-xl">ℹ️</span>
            <div class="text-sm text-blue-800">
                <strong>Sistem Pembayaran Tempatles:</strong> Kamu mendapat <strong>90%</strong> dari total tarif sesi.
                Platform mengambil <strong>10%</strong> sebagai biaya layanan. Pembayaran diproses setiap hari Jumat.
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('tutor.pendapatan') }}" class="flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Bulan</label>
                    <select name="bulan" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="">Semua Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                        </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Tahun</label>
                    <select name="tahun" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                        @for($y = now()->year; $y >= now()->year - 2; $y--)
                        <option value="{{ $y }}" {{ request('tahun', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['bulan', 'tahun', 'status']))
                <a href="{{ route('tutor.pendapatan') }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50 transition-colors">
                    Reset
                </a>
                @endif
            </form>
        </div>

        {{-- Tabel Riwayat Honor --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800 text-sm">Riwayat Honor Mengajar</h2>
            </div>

            @if(isset($honorList) && $honorList->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3">Tanggal Sesi</th>
                            <th class="px-6 py-3">Murid</th>
                            <th class="px-6 py-3">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-right">Tarif Bruto</th>
                            <th class="px-6 py-3 text-right">Potongan (10%)</th>
                            <th class="px-6 py-3 text-right">Honor Kamu (90%)</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($honorList as $honor)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-600">
                                {{ $honor->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $honor->booking->user->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $honor->booking->subject->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-right text-gray-600">
                                Rp {{ number_format($honor->jumlah_bruto ?? ($honor->jumlah / 0.9), 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right text-red-500">
                                - Rp {{ number_format(($honor->jumlah_bruto ?? ($honor->jumlah / 0.9)) * 0.1, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-green-600">
                                Rp {{ number_format($honor->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($honor->status === 'paid')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Dibayar
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> Menunggu
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <td colspan="5" class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Total Honor Halaman Ini:</td>
                            <td class="px-6 py-3 text-right text-sm font-bold text-green-600">
                                Rp {{ number_format($honorList->sum('jumlah'), 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $honorList->withQueryString()->links() }}
            </div>
            @else
            <div class="text-center py-16">
                <div class="text-5xl mb-3">💸</div>
                <p class="text-gray-500 text-sm">Belum ada riwayat honor mengajar</p>
                <p class="text-gray-400 text-xs mt-1">Honor akan muncul setelah sesi selesai dan dikonfirmasi</p>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
