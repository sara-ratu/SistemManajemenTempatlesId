{{-- resources/views/admin/laporan/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Laporan Sesi Tutor')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Sesi Tutor</h1>
        <div class="flex gap-2">
            @foreach (['', 'submitted', 'approved', 'draft'] as $s)
                <a href="{{ route('admin.laporan.index', $s ? ['status' => $s] : []) }}"
                   class="text-xs px-3 py-1.5 rounded-lg border transition-colors
                          {{ request('status') === $s || ($s === '' && !request('status'))
                             ? 'bg-blue-600 text-white border-blue-600'
                             : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
                    {{ $s ?: 'Semua' }}
                </a>
            @endforeach
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Tutor</th>
                    <th class="px-4 py-3 text-left">Murid</th>
                    <th class="px-4 py-3 text-left">Materi</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($laporans as $laporan)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-700">
                        {{ $laporan->tanggal_sesi->isoFormat('D MMM Y') }}
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $laporan->tutor->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $laporan->booking->murid->name }}</td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs truncate">{{ $laporan->materi_diajarkan }}</td>
                    <td class="px-4 py-3 text-center">
                        @if ($laporan->status_laporan === 'approved')
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-medium">Disetujui</span>
                        @elseif ($laporan->status_laporan === 'submitted')
                            <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full font-medium">Menunggu</span>
                        @else
                            <span class="bg-gray-100 text-gray-500 text-xs px-2 py-1 rounded-full font-medium">Draft</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('admin.laporan.show', $laporan) }}"
                           class="text-blue-600 hover:underline text-xs mr-2">Detail</a>
                        @if ($laporan->status_laporan === 'submitted')
                            <form action="{{ route('admin.laporan.approve', $laporan) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <button class="text-green-600 hover:underline text-xs">Setujui</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada laporan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $laporans->links() }}</div>
</div>
@endsection
