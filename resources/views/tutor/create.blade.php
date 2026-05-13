<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-0 font-semibold">Tambah Data Tutor Baru</h4>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

            <!-- Header Form -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6 text-white">
                <h5 class="text-xl font-semibold flex items-center gap-3">
                    <i class="fas fa-user-plus"></i>
                    Form Pendaftaran Tutor
                </h5>
            </div>

            <div class="p-8">

                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-5 py-3 rounded-xl">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('tutor.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Domisili</label>
                            <textarea name="alamat_domisili" rows="3" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. WA Aktif</label>
                            <input type="text" name="no_wa" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Aktif</label>
                            <input type="email" name="email" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <!-- Kolom lainnya -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_terakhir" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Asal Sekolah / Kampus</label>
                            <input type="text" name="asal_sekolah" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bidang Keahlian / Mata Pelajaran</label>
                            <input type="text" name="bidang_keahlian" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Upload File Silabus (PDF)</label>
                            <input type="file" name="file_silabus" class="form-control" accept=".pdf">
                            <small class="text-muted">Format penamaan: NamaKursus_Metode (contoh: MatematikaSD_Online)</small>
                        </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium px-8 py-4 rounded-xl transition">
                            <i class="fas fa-save"></i> Simpan Data Tutor
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
