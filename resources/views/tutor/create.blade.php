@extends('layouts.app')

@section('title', 'Tambah Pendaftar Tutor - TempatLes.id')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle"></i> Tambah Data Tutor Baru
                    </h4>
                </div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('tutor.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Domisili</label>
                            <textarea name="alamat_domisili" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">No. WA Aktif</label>
                                <input type="text" name="no_wa" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email Aktif</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Pendidikan Terakhir</label>
                                <input type="text" name="pendidikan_terakhir" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Asal Sekolah / Kampus</label>
                                <input type="text" name="asal_sekolah" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Bidang Keahlian / Mata Pelajaran</label>
                            <input type="text" name="bidang_keahlian" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jelaskan Pengalaman Mengajar Anda</label>
                            <textarea name="pengalaman_mengajar" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tingkat Siswa yang Bisa Diajar</label>
                                <input type="text" name="tingkat_siswa" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Metode Mengajar</label>
                                <input type="text" name="metode_mengajar" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Hari yang Tersedia</label>
                                <input type="text" name="hari_tersedia" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Jam Mengajar yang Diinginkan</label>
                                <input type="text" name="jam_mengajar" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Area Mengajar</label>
                            <input type="text" name="area_mengajar" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Pernyataan</label>
                            <textarea name="pernyataan" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Upload File Silabus (PDF)</label>
                            <input type="file" name="file_silabus" class="form-control" accept=".pdf">
                            <small class="text-muted">Format penamaan: NamaKursus_Metode (contoh: MatematikaSD_Online)</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-save"></i> Simpan Data Tutor
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection