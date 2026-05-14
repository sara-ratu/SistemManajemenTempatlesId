{{-- resources/views/admin/pembayaran/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="px-6 py-8">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Verifikasi Pembayaran</h1>

    {{-- Summary cards --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
            <p class="text-xs text-yellow-700 font-medium uppercase tracking-wide">Menunggu</p>
            <p class="text-2xl font-bold text-yellow-800 mt-1">{{ $summary['pending'] }}</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <p class="text-xs text-green-700 font-medium uppercase tracking-wide">Terverifikasi</p>
            <p class="text-2xl font-bold text-green-800 mt-1">{{ $summary['verified'] }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <p class="text-xs text-blue-700 font-medium uppercase tracking-wide">Total Masuk</p>
            <p class="text-xl font-bold text-blue-800 mt-1">Rp {{ number_format($summary['total'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Filter tab --}}
    <div class="flex gap-2 mb-4">
        @foreach(['pending' => 'Menunggu', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak', 'all' => 'Semua'] as $val => $label)
            <a href="{{ route('admin.pembayaran.index', ['status' => $val]) }}"
               class="px-4 py-1.5 rounded-full text-sm font-medium transition
                      {{ $status === $val ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">#</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Member</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Tutor</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Jumlah</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Metode</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Tanggal</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($pembayarans as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-400">{{ $p->id }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $p->Member->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $p->booking->tutor->name }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $p->jumlah_rp }}</td>
                        <td class="px-4 py-3 text-gray-600 capitalize">{{ str_replace('_', ' ', $p->metode) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $p->status_badge }}">
                                {{ $p->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.pembayaran.show', $p) }}"
                               class="text-blue-600 hover:underline text-xs font-medium">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-400">Tidak ada data pembayaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pembayarans->withQueryString()->links() }}
    </div>

</div>
@endsection
