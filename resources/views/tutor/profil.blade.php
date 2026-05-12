<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Edit Profil Tutor</h2></x-slot>
    <div class="py-8 max-w-3xl mx-auto px-4">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl p-4 mb-6 text-sm">{{ session('success') }}</div>
        @endif
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <form method="POST" action="{{ route('tutor.profil.simpan') }}">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="text-sm font-medium text-gray-600">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
                    <div><label class="text-sm font-medium text-gray-600">No. HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', auth()->user()->no_hp) }}" class="mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
                    <div><label class="text-sm font-medium text-gray-600">Kota</label>
                        <input type="text" name="kota" value="{{ old('kota', auth()->user()->kota) }}" class="mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
                    <div><label class="text-sm font-medium text-gray-600">Pendidikan Terakhir</label>
                        <input type="text" name="pendidikan" value="{{ old('pendidikan', $profile->pendidikan ?? '') }}" class="mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
                    <div><label class="text-sm font-medium text-gray-600">Harga Min (Rp/jam)</label>
                        <input type="number" name="harga_min" value="{{ old('harga_min', $profile->harga_min ?? 0) }}" class="mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
                    <div><label class="text-sm font-medium text-gray-600">Harga Maks (Rp/jam)</label>
                        <input type="number" name="harga_max" value="{{ old('harga_max', $profile->harga_max ?? 0) }}" class="mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
                </div>
                <div class="mt-4"><label class="text-sm font-medium text-gray-600">Bio Singkat</label>
                    <textarea name="bio" rows="3" class="mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">{{ old('bio', $profile->bio ?? '') }}</textarea></div>
                <div class="mt-4">
                    <label class="text-sm font-medium text-gray-600 block mb-2">Mata Pelajaran yang Diajarkan</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @foreach($subjects as $s)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="subject_ids[]" value="{{ $s->id }}"
                                    {{ in_array($s->id, $selectedMapel) ? 'checked' : '' }}>
                                {{ $s->nama_mapel }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <input type="hidden" name="latitude" id="lat" value="{{ auth()->user()->latitude }}">
                <input type="hidden" name="longitude" id="lon" value="{{ auth()->user()->longitude }}">
                <div class="mt-4">
                    <button type="button" onclick="ambilLokasi()" class="text-sm bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-gray-600 hover:bg-gray-100">Perbarui Lokasi Saya</button>
                    <span class="text-xs text-gray-400 ml-2" id="lok-status">{{ auth()->user()->latitude ? 'Lokasi tersimpan' : 'Belum ada lokasi' }}</span>
                </div>
                <div class="mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700">Simpan Profil</button>
                </div>
            </form>
        </div>
    </div>
    <script>
    function ambilLokasi(){
        navigator.geolocation.getCurrentPosition(p=>{
            document.getElementById('lat').value=p.coords.latitude;
            document.getElementById('lon').value=p.coords.longitude;
            document.getElementById('lok-status').textContent='Lokasi diperbarui!';
        });
    }
    </script>
</x-app-layout>
