@extends('layouts.tutor')

@section('title', 'Laporan Sesi Saya')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Sesi</h1>
    </div>

    {{-- Alert Success --}}
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal Sesi</th>
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
                    <td class="px-4 py-3 text-gray-700">
                        {{ $laporan->booking->murid->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs truncate">
                        {{ $laporan->materi_diajarkan ?? '-' }}
                    </td>
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
                        <a href="{{ route('tutor.laporan.create', $laporan->booking_id) }}"
                           class="text-blue-600 hover:underline text-xs">
                            {{ $laporan->isApproved() ? 'Lihat' : 'Edit' }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                        Belum ada laporan sesi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $laporans->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Kosongkan dulu kalau tidak butuh search di halaman ini
</script>
@endpush
