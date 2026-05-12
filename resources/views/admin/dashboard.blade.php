<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Dashboard Admin</h2></x-slot>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Stat Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            @foreach([['label'=>'Total Murid','val'=>$stats['total_murid'],'color'=>'blue'],['label'=>'Total Tutor','val'=>$stats['total_tutor'],'color'=>'green'],['label'=>'Menunggu Verifikasi','val'=>$stats['pending'],'color'=>'yellow'],['label'=>'Total Booking','val'=>$stats['total_booking'],'color'=>'purple']] as $s)
            <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
                <p class="text-2xl font-semibold text-{{ $s['color'] }}-600">{{ $s['val'] }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>
        {{-- Tutor Pending --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-700">Tutor Menunggu Verifikasi</h3>
                <a href="{{ route('admin.tutor') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
            </div>
            @forelse($tutorPending as $t)
                <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="text-sm font-medium">{{ $t->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $t->user->kota }} · Rp {{ number_format($t->harga_min) }}/jam</p>
                    </div>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('admin.verifikasi', [$t->id, 'approve']) }}">
                            @csrf @method('PATCH')
                            <button class="text-xs bg-green-600 text-white px-3 py-1.5 rounded-lg hover:bg-green-700">Verifikasi</button>
                        </form>
                        <form method="POST" action="{{ route('admin.verifikasi', [$t->id, 'reject']) }}">
                            @csrf @method('PATCH')
                            <button class="text-xs bg-red-500 text-white px-3 py-1.5 rounded-lg hover:bg-red-600">Tolak</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-4">Tidak ada tutor yang menunggu verifikasi</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
