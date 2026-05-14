<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Honor Tutor</h2>
    </x-slot>

    <div class="px-6 py-8 max-w-2xl mx-auto">
        <div class="bg-white rounded-3xl shadow-sm border p-8">
            <form method="POST" action="{{ route('admin.honor.update', $honor->id) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Tutor</label>
                        <input type="text" value="{{ $honor->tutor->name ?? '-' }}"
                               class="w-full bg-gray-100 border border-gray-300 rounded-2xl px-4 py-3" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Honor (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_honor" value="{{ old('jumlah_honor', $honor->jumlah_honor) }}"
                               class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500"
                               min="1000" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Periode <span class="text-red-500">*</span></label>
                        <input type="text" name="periode" value="{{ old('periode', $honor->periode) }}"
                               class="w-full border border-gray-300 rounded-2xl px-4 py-3" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="catatan" rows="4"
                                  class="w-full border border-gray-300 rounded-2xl px-4 py-3">{{ old('catatan', $honor->catatan) }}</textarea>
                    </div>

                    <div class="flex gap-4 pt-6">
                        <a href="{{ route('admin.honor.index') }}"
                           class="flex-1 text-center py-3.5 border border-gray-300 rounded-2xl font-medium hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 rounded-2xl">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
