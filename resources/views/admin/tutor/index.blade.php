<x-app-layout>

<div class="max-w-screen-2xl mx-auto px-6 py-8">

    @if (session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-5 py-4 rounded-xl">
            <strong>✅ Berhasil!</strong> {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                <i class="fas fa-chalkboard-teacher"></i>
                Daftar Pendaftar Tutor
            </h2>
            <p class="text-gray-600">Manajemen Data Tutor TempatLes.id</p>
        </div>
        <a href="{{ route('tutor.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Tutor Baru
        </a>
    </div>

    <div class="mb-6 max-w-md">
        <div class="relative">
            <input type="text" id="searchInput"
                   class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:border-blue-500"
                   placeholder="Cari nama tutor...">
            <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full" id="tutorTable">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left">No</th>
                        <th class="px-6 py-4 text-left">Nama Lengkap</th>
                        <th class="px-6 py-4 text-left">Jenis Kelamin</th>
                        <th class="px-6 py-4 text-left">Tempat, Tgl Lahir</th>
                        <th class="px-6 py-4 text-left">Alamat Domisili</th>
                        <th class="px-6 py-4 text-left">No. WA Aktif</th>
                        <th class="px-6 py-4 text-left">Email Aktif</th>
                        <th class="px-6 py-4 text-left">Pendidikan Terakhir</th>
                        <th class="px-6 py-4 text-left">Asal Sekolah/Kampus</th>
                        <th class="px-6 py-4 text-left">Bidang Keahlian</th>
                        <th class="px-6 py-4 text-left">Pengalaman Mengajar</th>
                        <th class="px-6 py-4 text-left">Tingkat Siswa</th>
                        <th class="px-6 py-4 text-left">Metode Mengajar</th>
                        <th class="px-6 py-4 text-left">Hari Tersedia</th>
                        <th class="px-6 py-4 text-left">Jam Mengajar</th>
                        <th class="px-6 py-4 text-left">Area Mengajar</th>
                        <th class="px-6 py-4 text-left">Pernyataan</th>
                        <th class="px-6 py-4 text-center">File Silabus</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tutors as $t)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-center">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-medium">{{ $t->nama_lengkap }}</td>
                        <td class="px-6 py-4">{{ $t->jenis_kelamin }}</td>
                        <td class="px-6 py-4">{{ $t->tempat_lahir }}, {{ $t->tanggal_lahir?->format('d M Y') }}</td>
                        <td class="px-6 py-4">{{ Str::limit($t->alamat_domisili, 50) }}</td>
                        <td class="px-6 py-4">{{ $t->no_wa }}</td>
                        <td class="px-6 py-4">{{ $t->email }}</td>
                        <td class="px-6 py-4">{{ $t->pendidikan_terakhir ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $t->asal_sekolah ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $t->bidang_keahlian ?? '-' }}</td>
                        <td class="px-6 py-4">{{ Str::limit($t->pengalaman_mengajar, 40) ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $t->tingkat_siswa ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $t->metode_mengajar ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $t->hari_tersedia ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $t->jam_mengajar ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $t->area_mengajar ?? '-' }}</td>
                        <td class="px-6 py-4">{{ Str::limit($t->pernyataan, 40) ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($t->file_silabus)
                                <a href="{{ Storage::url($t->file_silabus) }}" target="_blank" class="text-green-600 hover:text-green-700">
                                    <i class="fas fa-download"></i>
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('tutor.show', $t) }}" class="text-blue-600 hover:text-blue-700">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="19" class="px-6 py-16 text-center text-gray-500">
                            Belum ada data pendaftar tutor.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

</x-app-layout>

@push('scripts')
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        document.querySelectorAll("#tutorTable tbody tr").forEach(row => {
            row.style.display = row.textContent.toUpperCase().indexOf(filter) > -1 ? "" : "none";
        });
    });
</script>
@endpush
