<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Dashboard Admin</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Stat Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-gray-100 p-6 text-center">
                <p class="text-3xl font-bold text-blue-600">{{ $stats['total_murid'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 mt-1">Total Murid</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-6 text-center">
                <p class="text-3xl font-bold text-green-600">{{ $stats['total_tutor'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 mt-1">Total Tutor</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-6 text-center">
                <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_tutor'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 mt-1">Menunggu Verifikasi</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 p-6 text-center">
                <p class="text-3xl font-bold text-purple-600">{{ $stats['total_booking'] ?? 0 }}</p>
                <p class="text-sm text-gray-500 mt-1">Total Booking</p>
            </div>
        </div>

        {{-- Tutor Pending --}}
        <div class="bg-white rounded-2xl shadow p-6">
            <div class="flex justify-between items-center mb-5">
                <h3 class="font-semibold text-lg">Tutor Menunggu Verifikasi</h3>
                <a href="{{ route('admin.tutor.index') }}"
                   class="text-blue-600 hover:underline text-sm">
                    Lihat Semua Tutor →
                </a>
            </div>

            @forelse($tutorPending as $t)
                <div class="flex items-center justify-between py-4 border-b last:border-0">
                    <div>
                        <p class="font-medium">{{ $t->nama_lengkap ?? $t->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $t->user->email ?? '' }}</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.verification.show', $t) }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                            Review
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 py-8 text-center">Tidak ada tutor yang menunggu verifikasi.</p>
            @endforelse
        </div>

    </div>
</x-app-layout>
