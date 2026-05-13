{{-- resources/views/tutor/laporan/index.blade.php --}}
@extends('layouts.tutor')

@section('title', 'Laporan Sesi Saya')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Sesi</h1>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-6 text-sm">
            {{ session('success') }}
<div class="max-w-screen-2xl mx-auto px-4 py-6">

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <strong>✅ Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="fas fa-chalkboard-teacher me-3"></i> Daftar Pendaftar Tutor
            </h2>
            <p class="text-muted">Manajemen Data Tutor TempatLes.id</p>
        </div>
        <a href="{{ route('tutor.create') }}" class="btn btn-primary btn-lg shadow-sm">
            <i class="fas fa-plus-circle"></i> Tambah Tutor Baru
        </a>
    </div>

    <!-- Search -->
    <div class="mb-4">
        <div class="input-group input-group-lg">
            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
            <input type="text" id="searchInput" class="form-control" placeholder="Cari nama tutor...">
        </div>
    </div>

    <!-- Tabel -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0" id="tutorTable">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis Kelamin</th>
                            <th>Tempat, Tgl Lahir</th>
                            <th>Alamat Domisili</th>
                            <th>No. WA Aktif</th>
                            <th>Email Aktif</th>
                            <th>Pendidikan Terakhir</th>
                            <th>Asal Sekolah/Kampus</th>
                            <th>Bidang Keahlian</th>
                            <th>Pengalaman Mengajar</th>
                            <th>Tingkat Siswa</th>
                            <th>Metode Mengajar</th>
                            <th>Hari Tersedia</th>
                            <th>Jam Mengajar</th>
                            <th>Area Mengajar</th>
                            <th>Pernyataan</th>
                            <th class="text-center">File Silabus</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tutors as $t)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $t->nama_lengkap }}</td>
                            <td>{{ $t->jenis_kelamin }}</td>
                            <td>{{ $t->tempat_lahir }}, {{ $t->tanggal_lahir?->format('d M Y') }}</td>
                            <td>{{ Str::limit($t->alamat_domisili, 50) }}</td>
                            <td><a href="https://wa.me/{{ $t->no_wa }}" target="_blank">{{ $t->no_wa }}</a></td>
                            <td>{{ $t->email }}</td>
                            <td>{{ $t->pendidikan_terakhir ?? '-' }}</td>
                            <td>{{ $t->asal_sekolah ?? '-' }}</td>
                            <td>{{ $t->bidang_keahlian ?? '-' }}</td>
                            <td>{{ Str::limit($t->pengalaman_mengajar, 40) ?? '-' }}</td>
                            <td>{{ $t->tingkat_siswa ?? '-' }}</td>
                            <td>{{ $t->metode_mengajar ?? '-' }}</td>
                            <td>{{ $t->hari_tersedia ?? '-' }}</td>
                            <td>{{ $t->jam_mengajar ?? '-' }}</td>
                            <td>{{ $t->area_mengajar ?? '-' }}</td>
                            <td>{{ Str::limit($t->pernyataan, 40) ?? '-' }}</td>
                            <td class="text-center">
                                @if($t->file_silabus)
                                    <a href="{{ Storage::url($t->file_silabus) }}" target="_blank" class="btn btn-sm btn-success">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('tutor.show', $t) }}" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="19" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                Belum ada data pendaftar tutor.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
                        {{ $laporan->booking->murid->name }}
                    </td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs truncate">
                        {{ $laporan->materi_diajarkan }}
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

    <div class="mt-4">{{ $laporans->links() }}</div>
</div>

</x-app-layout>

@push('scripts')
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll("#tutorTable tbody tr");
        rows.forEach(row => {
            let text = row.textContent.toUpperCase();
            row.style.display = text.indexOf(filter) > -1 ? "" : "none";
        });
    });
</script>
@endpush
