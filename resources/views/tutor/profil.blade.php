<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Profil Tutor</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash --}}
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('tutor.profil.simpan') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                {{-- ======================== --}}
                {{-- BAGIAN 1: DATA DIRI --}}
                {{-- ======================== --}}
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-semibold text-gray-700 text-sm">Data Diri</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Foto Profil --}}
                        <div class="md:col-span-2 flex items-center gap-5">
                            <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-gray-200 bg-gray-100 flex-shrink-0">
                                @if($profile->foto_profil ?? null)
                                    <img src="{{ Storage::url($profile->foto_profil) }}" class="w-full h-full object-cover" alt="Foto Profil">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-2xl font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Foto Profil</label>
                                <input type="file" name="foto_profil" accept="image/jpg,image/jpeg,image/png"
                                       class="block text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border file:border-gray-200 file:text-sm file:bg-white file:text-gray-600 hover:file:bg-gray-50">
                                <p class="text-xs text-gray-400 mt-1">JPG/PNG, maks 2MB</p>
                                @error('foto_profil')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Nama Lengkap --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap <span class="text-red-400">*</span></label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror">
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Jenis Kelamin <span class="text-red-400">*</span></label>
                            <select name="jenis_kelamin"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jenis_kelamin') border-red-400 @enderror">
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin', $profile->jenis_kelamin ?? '') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $profile->jenis_kelamin ?? '') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- No WA --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">No. WhatsApp</label>
                            <input type="text" name="no_wa" value="{{ old('no_wa', auth()->user()->no_wa ?? '') }}"
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('no_wa')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Tempat Lahir --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $profile->tempat_lahir ?? '') }}"
                                   placeholder="contoh: Kediri"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir"
                                   value="{{ old('tanggal_lahir', isset($profile->tanggal_lahir) ? \Carbon\Carbon::parse($profile->tanggal_lahir)->format('Y-m-d') : '') }}"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        {{-- Kota --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Kota Domisili <span class="text-red-400">*</span></label>
                            <input type="text" name="kota" value="{{ old('kota', auth()->user()->kota ?? '') }}"
                                   placeholder="contoh: Kota Kediri"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kota') border-red-400 @enderror">
                            @error('kota')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Pendidikan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Pendidikan Terakhir</label>
                            <select name="pendidikan"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih --</option>
                                @foreach(['SMA/SMK','D3','S1','S2','S3'] as $p)
                                    <option value="{{ $p }}" {{ old('pendidikan', $profile->pendidikan ?? '') === $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Universitas --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Asal Sekolah / Kampus</label>
                            <input type="text" name="universitas" value="{{ old('universitas', $profile->universitas ?? '') }}"
                                   placeholder="contoh: Universitas Brawijaya"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        {{-- Pengalaman --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Pengalaman Mengajar (tahun)</label>
                            <input type="number" name="pengalaman" min="0" max="50"
                                   value="{{ old('pengalaman', $profile->pengalaman ?? 0) }}"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                    </div>
                </div>

                {{-- ======================== --}}
                {{-- BAGIAN 2: INFO MENGAJAR --}}
                {{-- ======================== --}}
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-semibold text-gray-700 text-sm">Info Mengajar</h3>
                    </div>
                    <div class="p-6 space-y-4">

                        {{-- Metode Mengajar --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Metode Mengajar</label>
                            @php
                                $selectedMetode = old('metode_mengajar', $profile->metode_mengajar ? explode(',', $profile->metode_mengajar) : []);
                            @endphp
                            <div class="flex flex-wrap gap-4">
                                @foreach(['Online','Offline','Online & Offline'] as $m)
                                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                                        <input type="checkbox" name="metode_mengajar[]" value="{{ $m }}"
                                               {{ in_array($m, $selectedMetode) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        {{ $m }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Jenjang --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Jenjang yang Diajarkan</label>
                            @php
                                $selectedJenjang = old('jenjang', $profile->jenjang ? explode(',', $profile->jenjang) : []);
                            @endphp
                            <div class="flex flex-wrap gap-4">
                                @foreach(['SD','SMP','SMA','Mahasiswa','Umum'] as $j)
                                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                                        <input type="checkbox" name="jenjang[]" value="{{ $j }}"
                                               {{ in_array($j, $selectedJenjang) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        {{ $j }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Harga --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Harga Min (Rp/jam) <span class="text-red-400">*</span></label>
                                <input type="number" name="harga_min" min="0"
                                       value="{{ old('harga_min', $profile->harga_min ?? 0) }}"
                                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('harga_min') border-red-400 @enderror">
                                @error('harga_min')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Harga Maks (Rp/jam) <span class="text-red-400">*</span></label>
                                <input type="number" name="harga_max" min="0"
                                       value="{{ old('harga_max', $profile->harga_max ?? 0) }}"
                                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('harga_max') border-red-400 @enderror">
                                @error('harga_max')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Mata Pelajaran --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Mata Pelajaran yang Diajarkan</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach($subjects as $s)
                                    <label class="flex items-center gap-2 text-sm cursor-pointer">
                                        <input type="checkbox" name="subject_ids[]" value="{{ $s->id }}"
                                               {{ in_array($s->id, $selectedMapel) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        {{ $s->nama_mapel }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Bio --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Bio Singkat</label>
                            <textarea name="bio" rows="3" maxlength="1000"
                                      placeholder="Ceritakan sedikit tentang dirimu dan pengalamanmu mengajar..."
                                      class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('bio', $profile->bio ?? '') }}</textarea>
                            <p class="text-xs text-gray-400 mt-1">Maks 1000 karakter</p>
                        </div>

                    </div>
                </div>

                {{-- ======================== --}}
                {{-- BAGIAN 3: DOKUMEN --}}
                {{-- ======================== --}}
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-semibold text-gray-700 text-sm">Dokumen</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Digunakan untuk verifikasi oleh admin</p>
                    </div>
                    <div class="p-6 space-y-4">

                        {{-- Ijazah --}}
                        <div class="flex items-start gap-4 p-4 border border-gray-100 rounded-lg">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ijazah Terakhir</label>
                                @if($profile->file_ijazah ?? null)
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded-full">✓ Sudah diupload</span>
                                        <a href="{{ Storage::url($profile->file_ijazah) }}" target="_blank"
                                           class="text-xs text-blue-600 hover:underline">Lihat file</a>
                                    </div>
                                @endif
                                <input type="file" name="file_ijazah" accept=".pdf,.jpg,.jpeg,.png"
                                       class="block text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border file:border-gray-200 file:text-sm file:bg-white file:text-gray-600 hover:file:bg-gray-50">
                                <p class="text-xs text-gray-400 mt-1">PDF atau gambar, maks 5MB</p>
                                @error('file_ijazah')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Sertifikat --}}
                        <div class="flex items-start gap-4 p-4 border border-gray-100 rounded-lg">
                            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sertifikat (opsional)</label>
                                @if($profile->file_sertifikat ?? null)
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded-full">✓ Sudah diupload</span>
                                        <a href="{{ Storage::url($profile->file_sertifikat) }}" target="_blank"
                                           class="text-xs text-blue-600 hover:underline">Lihat file</a>
                                    </div>
                                @endif
                                <input type="file" name="file_sertifikat" accept=".pdf,.jpg,.jpeg,.png"
                                       class="block text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border file:border-gray-200 file:text-sm file:bg-white file:text-gray-600 hover:file:bg-gray-50">
                                <p class="text-xs text-gray-400 mt-1">PDF atau gambar, maks 5MB</p>
                                @error('file_sertifikat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ======================== --}}
                {{-- BAGIAN 4: LOKASI --}}
                {{-- ======================== --}}
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-semibold text-gray-700 text-sm">Lokasi</h3>
                    </div>
                    <div class="p-6">
                        <input type="hidden" name="latitude" id="lat" value="{{ auth()->user()->latitude }}">
                        <input type="hidden" name="longitude" id="lon" value="{{ auth()->user()->longitude }}">
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="ambilLokasi()"
                                    class="text-sm bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-gray-600 hover:bg-gray-100 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Perbarui Lokasi Saya
                            </button>
                            <span class="text-xs text-gray-400" id="lok-status">
                                {{ auth()->user()->latitude ? 'Lokasi tersimpan ✓' : 'Belum ada lokasi' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('tutor.dashboard') }}"
                       class="px-5 py-2.5 text-sm border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-colors">
                        Simpan Profil
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
    function ambilLokasi() {
        if (!navigator.geolocation) {
            alert('Browser kamu tidak mendukung geolocation.');
            return;
        }
        navigator.geolocation.getCurrentPosition(
            function(p) {
                document.getElementById('lat').value = p.coords.latitude;
                document.getElementById('lon').value = p.coords.longitude;
                document.getElementById('lok-status').textContent = 'Lokasi diperbarui ✓';
            },
            function() {
                document.getElementById('lok-status').textContent = 'Gagal mengambil lokasi.';
            }
        );
    }
    </script>
</x-app-layout>
