<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Riwayat Booking</h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto px-4">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl p-4 mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filter Status --}}
        <div class="flex gap-2 mb-6 flex-wrap">
            @foreach(['all' => 'Semua', 'pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'selesai' => 'Selesai', 'cancelled' => 'Dibatalkan', 'rejected' => 'Ditolak'] as $val => $label)
                <a href="{{ route('murid.riwayat', ['status' => $val]) }}"
                   class="px-4 py-1.5 rounded-full text-xs font-medium border transition
                          {{ $status === $val
                             ? 'bg-blue-600 text-white border-blue-600'
                             : 'bg-white text-gray-600 border-gray-200 hover:border-blue-300' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Daftar Booking --}}
        @forelse($bookings as $b)
            <div class="bg-white rounded-xl border border-gray-100 p-5 mb-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold text-gray-800 truncate">{{ $b->tutor->name ?? '-' }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $b->statusBadge() }}">
                                {{ $b->statusLabel() }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-500 space-y-0.5">
                            <div>📚 {{ $b->subject->nama ?? '-' }}</div>
                            <div>📅 {{ $b->tanggal->translatedFormat('D, d M Y') }}
                                 &nbsp; ⏰ {{ substr($b->jam_mulai,0,5) }}–{{ substr($b->jam_selesai,0,5) }}
                                 ({{ $b->durasi }} jam)</div>
                            <div>💰 Rp {{ number_format($b->harga, 0, ',', '.') }}</div>
                            @if($b->catatan)
                                <div class="text-gray-400 italic text-xs">{{ $b->catatan }}</div>
                            @endif
                            @if($b->status === 'rejected' && $b->rejection_reason)
                                <div class="text-red-500 text-xs mt-1">
                                    Alasan ditolak: {{ $b->rejection_reason }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Aksi --}}
                    <div class="flex flex-col gap-2 shrink-0">
                        @if($b->isPending())
                            <form method="POST" action="{{ route('murid.booking.cancel', $b) }}"
                                  onsubmit="return confirm('Batalkan booking ini?')">
                                @csrf @method('PATCH')
                                <button class="text-xs text-red-500 border border-red-200 px-3 py-1.5 rounded-lg hover:bg-red-50">
                                    Batalkan
                                </button>
                            </form>
                        @endif
                        @if($b->isSelesai() && ! $b->review)
                            <a href="{{ route('murid.review.create', $b) }}"
                               class="text-xs text-blue-600 border border-blue-200 px-3 py-1.5 rounded-lg hover:bg-blue-50">
                                Beri Ulasan
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-100 p-12 text-center">
                <div class="text-4xl mb-3">📋</div>
                <p class="text-gray-400 text-sm">Belum ada booking.</p>
                <a href="{{ route('murid.cari-tutor') }}"
                   class="mt-4 inline-block text-sm text-blue-600 hover:underline">
                    Cari tutor sekarang →
                </a>
            </div>
        @endforelse

        {{-- Paginasi --}}
        @if($bookings->hasPages())
            <div class="mt-6">{{ $bookings->appends(request()->query())->links() }}</div>
        @endif

    </div>
</x-app-layout>
