<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Kelola Jadwal</h2></x-slot>
    <div class="py-8 max-w-3xl mx-auto px-4">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl p-4 mb-4 text-sm">{{ session('success') }}</div>
        @endif
        {{-- Form Tambah Jadwal --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 mb-6">
            <h3 class="font-semibold text-gray-700 mb-4">Tambah Jadwal Baru</h3>
            <form method="POST" action="{{ route('tutor.jadwal.simpan') }}" class="flex flex-wrap gap-3 items-end">
                @csrf
                <div><label class="text-xs text-gray-500 block mb-1">Hari</label>
                    <select name="hari" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h)
                            <option>{{ $h }}</option>
                        @endforeach
                    </select></div>
                <div><label class="text-xs text-gray-500 block mb-1">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
                <div><label class="text-xs text-gray-500 block mb-1">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">Tambah</button>
            </form>
        </div>
        {{-- Daftar Jadwal --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-4">Jadwal Aktif</h3>
            @forelse($schedules as $s)
                <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                    <div>
                        <span class="font-medium text-sm">{{ $s->hari }}</span>
                        <span class="text-xs text-gray-400 ml-2">{{ substr($s->jam_mulai,0,5) }} – {{ substr($s->jam_selesai,0,5) }}</span>
                    </div>
                    <form method="POST" action="{{ route('tutor.jadwal.hapus', $s->id) }}">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-500 hover:underline">Hapus</button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-4">Belum ada jadwal</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
