<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">PKS Digital</h2>
            <a href="{{ route('admin.pks.create') }}"
               class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700">
                + Buat PKS Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl p-4 mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-4 gap-4 mb-6">
            @foreach(['draft' => ['Draft', 'gray'], 'sent' => ['Dikirim', 'yellow'], 'signed' => ['Aktif', 'green'], 'expired' => ['Kadaluarsa', 'red']] as $s => [$label, $color])
                <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
                    <div class="text-2xl font-bold text-{{ $color }}-600">{{ $stats[$s] }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ $label }}</div>
                </div>
            @endforeach
        </div>

        {{-- Filter --}}
        <div class="flex gap-2 mb-5 flex-wrap">
            @foreach(['all' => 'Semua', 'draft' => 'Draft', 'sent' => 'Dikirim', 'signed' => 'Aktif', 'expired' => 'Kadaluarsa', 'terminated' => 'Diakhiri'] as $val => $label)
                <a href="{{ route('admin.pks.index', ['status' => $val]) }}"
                   class="px-4 py-1.5 rounded-full text-xs font-medium border transition
                          {{ $status === $val
                             ? 'bg-blue-600 text-white border-blue-600'
                             : 'bg-white text-gray-600 border-gray-200 hover:border-blue-300' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Nomor PKS</th>
                        <th class="px-4 py-3 text-left">Tutor</th>
                        <th class="px-4 py-3 text-left">Berlaku</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($pksList as $pks)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-mono text-xs">{{ $pks->nomor_pks }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-800">{{ $pks->tutor->name ?? '-' }}</div>
                                <div class="text-xs text-gray-400">{{ $pks->tutor->tutorProfile->kota ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500">
                                {{ $pks->tanggal_mulai->format('d/m/Y') }}
                                –
                                {{ $pks->tanggal_selesai->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $pks->statusBadge() }}">
                                    {{ $pks->statusLabel() }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.pks.show', $pks) }}"
                                   class="text-xs text-blue-600 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-gray-400 text-sm">
                                Belum ada PKS.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pksList->hasPages())
            <div class="mt-5">{{ $pksList->appends(request()->query())->links() }}</div>
        @endif

    </div>
</x-app-layout>
