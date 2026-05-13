<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Booking Les — {{ $tutor->name }}</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto px-4">

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6 text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Info Tutor --}}
        <div class="bg-white rounded-xl border border-gray-100 p-5 mb-6 flex items-center gap-4">
            @if($profile->foto_profil)
                <img src="{{ Storage::url($profile->foto_profil) }}"
                     class="w-16 h-16 rounded-full object-cover" alt="Foto {{ $tutor->name }}">
            @else
                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-2xl font-bold text-blue-600">
                    {{ strtoupper(substr($tutor->name,0,1)) }}
                </div>
            @endif
            <div>
                <div class="font-semibold text-gray-800">{{ $tutor->name }}</div>
                <div class="text-sm text-gray-500">{{ $profile->kota ?? '-' }}</div>
                <div class="text-sm text-blue-600 font-medium mt-1">
                    Rp {{ number_format($profile->harga_per_jam ?? 0, 0, ',', '.') }} / jam
                </div>
            </div>
        </div>

        {{-- Jadwal Tersedia --}}
        @if($jadwal->count())
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                <p class="text-xs font-semibold text-blue-700 mb-2">Jadwal Tersedia Tutor</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($jadwal as $j)
                        <span class="text-xs bg-white border border-blue-200 text-blue-700 rounded-lg px-3 py-1">
                            {{ $j->hari }}
                            {{ substr($j->jam_mulai,0,5) }}–{{ substr($j->jam_selesai,0,5) }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Form Booking --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-5">Isi Detail Booking</h3>

            <form method="POST" action="{{ route('murid.booking.store', $tutor) }}" class="space-y-4">
                @csrf

                {{-- Mata Pelajaran --}}
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Mata Pelajaran <span class="text-red-400">*</span></label>
                    <select name="subject_id" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('subject_id') border-red-400 @enderror">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Tanggal Les <span class="text-red-400">*</span></label>
                    <input type="date" name="tanggal" required
                           min="{{ date('Y-m-d') }}"
                           value="{{ old('tanggal') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('tanggal') border-red-400 @enderror">
                    @error('tanggal')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Jam --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Jam Mulai <span class="text-red-400">*</span></label>
                        <input type="time" name="jam_mulai" required
                               value="{{ old('jam_mulai') }}"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                        @error('jam_mulai')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Jam Selesai <span class="text-red-400">*</span></label>
                        <input type="time" name="jam_selesai" required
                               value="{{ old('jam_selesai') }}"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                        @error('jam_selesai')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Preview Harga --}}
                <div id="preview-harga" class="hidden bg-gray-50 border border-gray-100 rounded-lg p-3 text-sm text-gray-600">
                    Estimasi biaya: <span id="estimasi-harga" class="font-semibold text-blue-600"></span>
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Catatan (opsional)</label>
                    <textarea name="catatan" rows="3"
                              placeholder="Contoh: les di rumah murid, fokus materi tertentu, dll."
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 resize-none">{{ old('catatan') }}</textarea>
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                    Kirim Permintaan Booking
                </button>
            </form>
        </div>

    </div>

    {{-- Script preview harga --}}
    <script>
        const hargaPerJam = {{ $profile->harga_per_jam ?? 0 }};

        function hitungHarga() {
            const mulai   = document.querySelector('[name=jam_mulai]').value;
            const selesai = document.querySelector('[name=jam_selesai]').value;
            if (!mulai || !selesai) return;

            const [hm, mm] = mulai.split(':').map(Number);
            const [hs, ms] = selesai.split(':').map(Number);
            const durasi = ((hs * 60 + ms) - (hm * 60 + mm)) / 60;
            if (durasi <= 0) return;

            const total = hargaPerJam * durasi;
            const fmt   = new Intl.NumberFormat('id-ID').format(total);
            document.getElementById('estimasi-harga').textContent = `Rp ${fmt} (${durasi} jam)`;
            document.getElementById('preview-harga').classList.remove('hidden');
        }

        document.querySelector('[name=jam_mulai]').addEventListener('change', hitungHarga);
        document.querySelector('[name=jam_selesai]').addEventListener('change', hitungHarga);
    </script>
</x-app-layout>
