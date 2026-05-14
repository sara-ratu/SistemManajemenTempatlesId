@extends('layouts.app')

@section('title', 'Verifikasi Tutor & Member')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Verifikasi Pengguna</h1>
            <p class="text-gray-500 mt-1">Kelola pendaftaran tutor baru dan permintaan member</p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-3xl">👨‍🏫</div>
                    <div>
                        <p class="text-3xl font-semibold">{{ $pendingTutors->total() ?? 0 }}</p>
                        <p class="text-sm text-gray-500">Tutor Menunggu Verifikasi</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-3xl">👨‍🎓</div>
                    <div>
                        <p class="text-3xl font-semibold">{{ $pendingMembers->total() ?? 0 }}</p>
                        <p class="text-sm text-gray-500">Member Menunggu</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs & Content --}}
        <div class="bg-white rounded-2xl shadow border">

            {{-- Tab Navigation --}}
            <div class="border-b px-6">
                <nav class="flex">
                    <a href="{{ route('admin.verification.index', ['tab' => 'tutor']) }}"
                       class="py-5 px-8 font-medium border-b-2 {{ request('tab', 'tutor') === 'tutor' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                        Pendaftaran Tutor
                    </a>
                    <a href="{{ route('admin.verification.index', ['tab' => 'member']) }}"
                       class="py-5 px-8 font-medium border-b-2 {{ request('tab') === 'member' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                        Permintaan Member
                    </a>
                </nav>
            </div>

            {{-- Tutor Tab --}}
            @if(request('tab', 'tutor') === 'tutor' || !request('tab'))
            <div class="p-6">
                @if($pendingTutors->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 text-xs font-medium text-gray-500 uppercase">
                                <th class="text-left py-4 px-4">Nama Lengkap</th>
                                <th class="text-left py-4 px-4">Email / WA</th>
                                <th class="text-left py-4 px-4">Mata Pelajaran</th>
                                <th class="text-left py-4 px-4">Tanggal Daftar</th>
                                <th class="text-left py-4 px-4">Status</th>
                                <th class="text-center py-4 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($pendingTutors as $reg)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 font-medium">{{ $reg->nama_lengkap }}</td>
                                <td class="px-4 py-4 text-sm">
                                    {{ $reg->email }}<br>
                                    <span class="text-gray-400">{{ $reg->no_wa }}</span>
                                </td>
                                <td class="px-4 py-4">{{ $reg->bidang_keahlian ?? '-' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-500">
                                    {{ $reg->created_at->format('d M Y') }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                        Menunggu
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <a href="{{ route('admin.verification.tutor.detail', $reg->id) }}"
                                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $pendingTutors->withQueryString()->links() }}
                </div>
                @else
                <div class="text-center py-20 text-gray-400">
                    Tidak ada pendaftaran tutor yang perlu diverifikasi saat ini.
                </div>
                @endif
            </div>
            @endif

            {{-- Member Tab (bisa kamu isi nanti) --}}
            @if(request('tab') === 'member')
            <div class="p-6 text-center py-20 text-gray-400">
                Halaman verifikasi Member sedang dalam pengembangan...
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
