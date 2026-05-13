<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-0 font-semibold flex items-center gap-2">
            <i class="fas fa-plus-circle"></i> 
            Tambah Data Tutor Baru
        </h4>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

            <!-- Header Form -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6 text-white">
                <h5 class="text-xl font-semibold">Form Pendaftaran Tutor</h5>
            </div>

            <div class="p-8">

                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-5 py-4 rounded-xl">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('tutor.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Domisili</label>
                            <textarea name="alamat_domisili" rows="3" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. WA Aktif</label>
                            <input type="text" name="no_wa" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Aktif</label>
                            <input type="email" name="email" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_terakhir" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Asal Sekolah / Kampus</label>
                            <input type="text" name="asal_sekolah" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bidang Keahlian / Mata Pelajaran</label>
                            <input type="text" name="bidang_keahlian" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pengalaman Mengajar</label>
                            <textarea name="pengalaman_mengajar" rows="4" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Siswa yang Bisa Diajar</label>
                            <input type="text" name="tingkat_siswa" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Metode Mengajar</label>
                            <input type="text" name="metode_mengajar" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hari yang Tersedia</label>
                            <input type="text" name="hari_tersedia" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mengajar yang Diinginkan</label>
                            <input type="text" name="jam_mengajar" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Area Mengajar</label>
                            <input type="text" name="area_mengajar" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pernyataan</label>
                            <textarea name="pernyataan" rows="3" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500"></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload File Silabus (PDF)</label>
                            <input type="file" name="file_silabus" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500" accept=".pdf">
                            <small class="text-gray-500 mt-1 block">Format: NamaKursus_Metode.pdf (contoh: MatematikaSD_Online)</small>
                        </div>

                    </div>

                    <div class="pt-8">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 transition text-white font-medium py-4 rounded-2xl text-lg">
                            <i class="fas fa-save"></i> Simpan Data Tutor
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>