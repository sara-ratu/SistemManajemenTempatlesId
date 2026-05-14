<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Dashboard Tutor</h2>
    </x-slot>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Status Verifikasi --}}
        @if(!$profile || $profile->status_verifikasi !== 'verified')
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6 text-sm text-yellow-800">
                Profil kamu belum terverifikasi. <a href="{{ route('tutor.profil') }}" class="font-semibold underline">Lengkapi profil</a> untuk mulai menerima Member.
            </div>
        @endif

        {{-- Stat Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
                <p class="text-2xl font-semibold text-blue-600">{{ $profile?->total_Member ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">Total Member</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
                <p class="text-2xl font-semibold text-yellow-500">{{ $profile?->rating_rata ?? '-' }}</p>
                <p class="text-xs text-gray-500 mt-1">Rating</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
                <p class="text-2xl font-semibold text-green-600">{{ $bookings->where('status','completed')->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Sesi Selesai</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
                <p class="text-2xl font-semibold text-orange-500">{{ $bookings->where('status','pending')->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Menunggu</p>
            </div>
        </div>

        {{-- Booking Terbaru --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-700">Booking Terbaru</h3>
                <a href="{{ route('tutor.jadwal.index') }}" class="text-sm text-blue-600 hover:underline">Kelola Jadwal</a>
            </div>
            @forelse($bookings as $b)
                <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $b->Member->name }}</p>
                        <p class="text-xs text-gray-400">{{ $b->subject->nama_mapel }} · {{ $b->tanggal->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs px-2 py-1 rounded-full
                            {{ $b->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $b->status === 'confirmed' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $b->status === 'completed' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $b->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($b->status) }}
                        </span>
                        @if($b->status === 'pending')
                            <form method="POST" action="{{ route('tutor.booking.aksi', [$b->id, 'confirm']) }}">
                                @csrf @method('PATCH')
                                <button class="text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">Terima</button>
                            </form>
                            <form method="POST" action="{{ route('tutor.booking.aksi', [$b->id, 'cancel']) }}">
                                @csrf @method('PATCH')
                                <button class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Tolak</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 py-4 text-center">Belum ada booking masuk</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
