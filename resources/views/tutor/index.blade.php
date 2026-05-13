<x-app-layout>

<div class="container py-4">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>✅ Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark">
                <i class="fas fa-chalkboard-teacher me-3"></i> Daftar Pendaftar Tutor
            </h2>
            <p class="text-muted">Manajemen Data Tutor TempatLes.id</p>
        </div>
        <a href="#" class="btn btn-primary btn-lg shadow-sm">
            <i class="fas fa-plus-circle"></i> Tambah Tutor Baru
        </a>
    </div>

    <!-- Search -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                <input type="text" id="searchInput" class="form-control" placeholder="Cari nama tutor...">
            </div>
        </div>
    </div>

    <!-- Tabel Lengkap -->
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
                            <td>{{ $t->pendidikan_terakhir }}</td>
                            <td>{{ $t->asal_sekolah }}</td>
                            <td>{{ $t->bidang_keahlian }}</td>
                            <td>{{ Str::limit($t->pengalaman_mengajar, 50) }}</td>
                            <td>{{ $t->tingkat_siswa }}</td>
                            <td>{{ $t->metode_mengajar }}</td>
                            <td>{{ $t->hari_tersedia }}</td>
                            <td>{{ $t->jam_mengajar }}</td>
                            <td>{{ $t->area_mengajar }}</td>
                            <td>{{ Str::limit($t->pernyataan, 50) }}</td>
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
                                <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                Belum ada data pendaftar tutor.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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