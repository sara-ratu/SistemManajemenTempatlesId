<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Buat PKS Baru</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <form method="POST" action="{{ route('admin.pks.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Tutor <span class="text-red-400">*</span></label>
                    <select name="tutor_id" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 @error('tutor_id') border-red-400 @enderror">
                        <option value="">-- Pilih Tutor --</option>
                        @foreach($tutors as $t)
                            <option value="{{ $t->id }}" {{ old('tutor_id') == $t->id ? 'selected' : '' }}>
                                {{ $t->name }}
                                @if($t->tutorProfile?->kota) — {{ $t->tutorProfile->kota }} @endif
                            </option>
                        @endforeach
                    </select>
                    @error('tutor_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    @if($tutors->isEmpty())
                        <p class="text-xs text-yellow-600 mt-1">
                            Semua tutor sudah memiliki PKS aktif atau belum ada tutor terverifikasi.
                        </p>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tanggal Mulai <span class="text-red-400">*</span></label>
                        <input type="date" name="tanggal_mulai" required
                               value="{{ old('tanggal_mulai', now()->format('Y-m-d')) }}"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                        @error('tanggal_mulai')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tanggal Selesai <span class="text-red-400">*</span></label>
                        <input type="date" name="tanggal_selesai" required
                               value="{{ old('tanggal_selesai', now()->addYear()->format('Y-m-d')) }}"
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                        @error('tanggal_selesai')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Catatan Tambahan (opsional)</label>
                    <textarea name="catatan" rows="3"
                              placeholder="Ketentuan khusus, dll."
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 resize-none">{{ old('catatan') }}</textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
                        Generate PKS
                    </button>
                    <a href="{{ route('admin.pks.index') }}"
                       class="px-6 py-2 rounded-lg text-sm border border-gray-200 text-gray-600 hover:bg-gray-50">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
