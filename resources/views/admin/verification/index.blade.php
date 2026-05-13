@extends('layouts.app')

@section('title', 'Verifikasi Tutor & Member')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Verifikasi Dokumen</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola permohonan tutor baru dan member request yang masuk</p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
                <div class="w-11 h-11 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $pendingTutors ?? 0 }}</div>
                    <div class="text-xs text-gray-500">Tutor Menunggu</div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
                <div class="w-11 h-11 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $pendingMembers ?? 0 }}</div>
                    <div class="text-xs text-gray-500">Member Request</div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
                <div class="w-11 h-11 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $approvedToday ?? 0 }}</div>
                    <div class="text-xs text-gray-500">Disetujui Hari Ini</div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px px-6 gap-6" x-data="{ tab: '{{ request('tab', 'tutor') }}' }">
                    <a href="{{ route('admin.verification.index', ['tab' => 'tutor']) }}"
                       class="py-4 text-sm font-medium border-b-2 transition-colors {{ request('tab', 'tutor') === 'tutor' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                        Pendaftaran Tutor
                        @if(($pendingTutors ?? 0) > 0)
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ $pendingTutors }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.verification.index', ['tab' => 'member']) }}"
                       class="py-4 text-sm font-medium border-b-2 transition-colors {{ request('tab') === 'member' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                        Member Request
                        @if(($pendingMembers ?? 0) > 0)
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $pendingMembers }}</span>
                        @endif
                    </a>
                </nav>
            </div>

            {{-- Tab: Tutor --}}
            @if(request('tab', 'tutor') === 'tutor')
            <div class="p-6">
                @if(isset($tutorRegistrations) && $tutorRegistrations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <th class="pb-3">Pendaftar</th>
                                <th class="pb-3">Mata Pelajaran</th>
                                <th class="pb-3">Kota</th>
                                <th class="pb-3">Daftar</th>
                                <th class="pb-3">Dokumen</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($tutorRegistrations as $reg)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 pr-4">
                                    <div class="font-medium text-gray-900">{{ $reg->user->name ?? $reg->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-400">{{ $reg->user->email ?? '-' }}</div>
                                </td>
                                <td class="py-3 pr-4 text-gray-600">{{ $reg->mata_pelajaran ?? '-' }}</td>
                                <td class="py-3 pr-4 text-gray-600">{{ $reg->kota ?? '-' }}</td>
                                <td class="py-3 pr-4 text-gray-400 text-xs">{{ $reg->created_at->format('d M Y') }}</td>
                                <td class="py-3 pr-4">
                                    @if($reg->foto_ktp)
                                        <a href="{{ Storage::url($reg->foto_ktp) }}" target="_blank" class="text-blue-600 hover:underline text-xs mr-2">KTP</a>
                                    @endif
                                    @if($reg->foto_ijazah)
                                        <a href="{{ Storage::url($reg->foto_ijazah) }}" target="_blank" class="text-blue-600 hover:underline text-xs">Ijazah</a>
                                    @endif
                                </td>
                                <td class="py-3 pr-4">
                                    @php
                                        $statusMap = [
                                            'pending'  => ['bg-yellow-100 text-yellow-800', 'Menunggu'],
                                            'approved' => ['bg-green-100 text-green-800', 'Disetujui'],
                                            'rejected' => ['bg-red-100 text-red-800', 'Ditolak'],
                                        ];
                                        [$cls, $label] = $statusMap[$reg->status] ?? ['bg-gray-100 text-gray-600', ucfirst($reg->status)];
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $cls }}">{{ $label }}</span>
                                </td>
                                <td class="py-3">
                                    @if($reg->status === 'pending')
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('admin.verification.approve', $reg->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-colors">
                                                ✓ Setuju
                                            </button>
                                        </form>
                                        <button onclick="openRejectModal({{ $reg->id }})"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-medium rounded-lg border border-red-200 transition-colors">
                                            ✕ Tolak
                                        </button>
                                    </div>
                                    @else
                                    <span class="text-xs text-gray-400">Sudah diproses</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $tutorRegistrations->withQueryString()->links() }}
                </div>
                @else
                <div class="text-center py-16">
                    <div class="text-4xl mb-3">🎉</div>
                    <p class="text-gray-500 text-sm">Tidak ada pendaftaran tutor yang perlu diverifikasi</p>
                </div>
                @endif
            </div>

            {{-- Tab: Member --}}
            @elseif(request('tab') === 'member')
            <div class="p-6">
                @if(isset($memberRequests) && $memberRequests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <th class="pb-3">Member</th>
                                <th class="pb-3">Kebutuhan Belajar</th>
                                <th class="pb-3">Tanggal</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($memberRequests as $req)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 pr-4">
                                    <div class="font-medium text-gray-900">{{ $req->user->name ?? '-' }}</div>
                                    <div class="text-xs text-gray-400">{{ $req->user->email ?? '-' }}</div>
                                </td>
                                <td class="py-3 pr-4 text-gray-600 max-w-xs truncate">{{ $req->kebutuhan_belajar ?? '-' }}</td>
                                <td class="py-3 pr-4 text-gray-400 text-xs">{{ $req->created_at->format('d M Y') }}</td>
                                <td class="py-3 pr-4">
                                    @php
                                        $statusMap = [
                                            'pending'  => ['bg-yellow-100 text-yellow-800', 'Menunggu'],
                                            'approved' => ['bg-green-100 text-green-800', 'Disetujui'],
                                            'rejected' => ['bg-red-100 text-red-800', 'Ditolak'],
                                        ];
                                        [$cls, $label] = $statusMap[$req->status] ?? ['bg-gray-100 text-gray-600', ucfirst($req->status)];
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $cls }}">{{ $label }}</span>
                                </td>
                                <td class="py-3">
                                    @if($req->status === 'pending')
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('admin.verification.approveMember', $req->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-colors">
                                                ✓ Setuju
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.verification.rejectMember', $req->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-medium rounded-lg border border-red-200 transition-colors">
                                                ✕ Tolak
                                            </button>
                                        </form>
                                    </div>
                                    @else
                                    <span class="text-xs text-gray-400">Sudah diproses</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $memberRequests->withQueryString()->links() }}
                </div>
                @else
                <div class="text-center py-16">
                    <div class="text-4xl mb-3">✅</div>
                    <p class="text-gray-500 text-sm">Tidak ada member request yang perlu diverifikasi</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Tolak Tutor --}}
<div id="rejectModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Tolak Pendaftaran Tutor</h3>
        <p class="text-sm text-gray-500 mb-4">Berikan alasan penolakan agar tutor bisa memperbaiki pendaftarannya.</p>
        <form id="rejectForm" method="POST">
            @csrf @method('PATCH')
            <textarea name="alasan_penolakan" rows="3" placeholder="Contoh: Foto KTP kurang jelas, silakan upload ulang..."
                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 resize-none"></textarea>
            <div class="flex gap-3 mt-4">
                <button type="button" onclick="closeRejectModal()" class="flex-1 px-4 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">Tolak</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal(id) {
    document.getElementById('rejectForm').action = `/admin/verification/${id}/reject`;
    const modal = document.getElementById('rejectModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endsection
