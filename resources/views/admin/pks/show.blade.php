<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl">Detail PKS — {{ $pks->nomor_pks }}</h2>
            <a href="{{ route('admin.pks.index') }}" class="text-sm text-gray-500 hover:underline">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto px-4 space-y-5">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl p-4 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm">{{ session('error') }}</div>
        @endif

        {{-- Info PKS --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="font-mono text-sm text-gray-500">{{ $pks->nomor_pks }}</div>
                    <div class="font-semibold text-gray-800 text-lg mt-1">{{ $pks->tutor->name ?? '-' }}</div>
                    <div class="text-sm text-gray-400">{{ $pks->tutor->tutorProfile->kota ?? '' }}</div>
                </div>
                <span class="text-sm px-3 py-1 rounded-full {{ $pks->statusBadge() }}">
                    {{ $pks->statusLabel() }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                <div>
                    <div class="text-xs text-gray-400 mb-0.5">Masa Berlaku</div>
                    {{ $pks->tanggal_mulai->format('d M Y') }} – {{ $pks->tanggal_selesai->format('d M Y') }}
                </div>
                <div>
                    <div class="text-xs text-gray-400 mb-0.5">Digenerate Oleh</div>
                    {{ $pks->generatedBy->name ?? '-' }}
                </div>
                @if($pks->signed_at)
                <div>
                    <div class="text-xs text-gray-400 mb-0.5">Ditandatangani</div>
                    {{ $pks->signed_at->translatedFormat('d M Y, H:i') }}
                </div>
                <div>
                    <div class="text-xs text-gray-400 mb-0.5">Ditandatangani Oleh</div>
                    {{ $pks->signedBy->name ?? '-' }}
                </div>
                @endif
                @if($pks->catatan)
                <div class="col-span-2">
                    <div class="text-xs text-gray-400 mb-0.5">Catatan</div>
                    {{ $pks->catatan }}
                </div>
                @endif
            </div>
        </div>

        {{-- Aksi --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-700 mb-4 text-sm">Aksi</h3>
            <div class="flex flex-wrap gap-3">

                {{-- Preview PDF --}}
                @if($pks->file_path)
                    <a href="{{ route('admin.pks.download', $pks) }}" target="_blank"
                       class="text-sm border border-blue-200 text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50">
                        📄 Lihat PDF
                    </a>
                @endif

                {{-- Kirim ke tutor --}}
                @if($pks->status === 'draft')
                    <form method="POST" action="{{ route('admin.pks.send', $pks) }}">
                        @csrf @method('PATCH')
                        <button class="text-sm bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">
                            📤 Kirim ke Tutor
                        </button>
                    </form>
                @endif

                {{-- Tandatangani --}}
                @if(in_array($pks->status, ['draft', 'sent']))
                    <form method="POST" action="{{ route('admin.pks.sign', $pks) }}"
                          onsubmit="return confirm('Tandatangani PKS ini?')">
                        @csrf @method('PATCH')
                        <button class="text-sm bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            ✍️ Tandatangani
                        </button>
                    </form>
                @endif

                {{-- Akhiri --}}
                @if($pks->status === 'signed')
                    <button onclick="document.getElementById('form-terminate').classList.toggle('hidden')"
                            class="text-sm border border-red-200 text-red-500 px-4 py-2 rounded-lg hover:bg-red-50">
                        🚫 Akhiri PKS
                    </button>
                @endif
            </div>

            {{-- Form terminate --}}
            <div id="form-terminate" class="hidden mt-4 pt-4 border-t border-gray-100">
                <form method="POST" action="{{ route('admin.pks.terminate', $pks) }}">
                    @csrf @method('PATCH')
                    <label class="block text-xs text-gray-500 mb-1">Alasan Pengakhiran (opsional)</label>
                    <textarea name="catatan" rows="2"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-red-200"
                              placeholder="Tulis alasan…"></textarea>
                    <button class="mt-2 text-sm bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                        Konfirmasi Akhiri
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
