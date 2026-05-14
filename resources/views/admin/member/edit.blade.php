<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Member</h2>
    </x-slot>

    <div class="px-6 py-8 max-w-2xl mx-auto">
        <div class="bg-white rounded-3xl shadow p-8">
            <form method="POST" action="{{ route('admin.member.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="w-full border border-gray-300 rounded-2xl px-4 py-3" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="w-full border border-gray-300 rounded-2xl px-4 py-3" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">No HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                               class="w-full border border-gray-300 rounded-2xl px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Alamat</label>
                        <textarea name="alamat" rows="3"
                                  class="w-full border border-gray-300 rounded-2xl px-4 py-3">{{ old('alamat', $user->alamat) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Kota</label>
                        <input type="text" name="kota" value="{{ old('kota', $user->kota) }}"
                               class="w-full border border-gray-300 rounded-2xl px-4 py-3">
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_verified" id="verified"
                               {{ old('is_verified', $user->is_verified) ? 'checked' : '' }}>
                        <label for="verified" class="text-sm">Terverifikasi</label>
                    </div>

                    <div class="flex gap-4 pt-6">
                        <a href="{{ route('admin.member.index') }}"
                           class="flex-1 py-3.5 text-center border border-gray-300 rounded-2xl hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit"
                                class="flex-1 py-3.5 bg-blue-600 text-white font-semibold rounded-2xl hover:bg-blue-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
