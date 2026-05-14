<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Honor Tutor
        </h2>
    </x-slot>
<div class="px-6 py-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Honor Tutor</h1>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5">
            <p class="text-xs text-yellow-700 font-medium uppercase tracking-widest">Belum Ditransfer</p>
            <p class="text-3xl font-bold text-yellow-800 mt-2">{{ $summary['pending'] ?? 0 }}</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-2xl p-5">
            <p class="text-xs text-green-700 font-medium uppercase tracking-widest">Sudah Ditransfer</p>
            <p class="text-3xl font-bold text-green-800 mt-2">{{ $summary['ditransfer'] ?? 0 }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5">
            <p class="text-xs text-blue-700 font-medium uppercase tracking-widest">Total Pending</p>
            <p class="text-3xl font-bold text-blue-800 mt-2">Rp {{ number_format($summary['total'] ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="flex flex-wrap gap-2 mb-6">
        @foreach(['pending' => 'Belum Transfer', 'ditransfer' => 'Sudah Transfer', 'all' => 'Semua'] as $val => $label)
            <a href="{{ route('admin.honor.index', ['status' => $val]) }}"
               class="px-5 py-2 rounded-full text-sm font-medium transition-all
                      {{ $status === $val
                          ? 'bg-blue-600 text-white shadow-sm'
                          : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl p-4 mb-6 flex items-center gap-2">
            <span class="text-xl">✅</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6">
            {{ session('error') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left font-medium text-gray-500">Tutor</th>
                    <th class="px-6 py-4 text-left font-medium text-gray-500">Rekening</th>
                    <th class="px-6 py-4 text-left font-medium text-gray-500">Bruto</th>
                    <th class="px-6 py-4 text-left font-medium text-gray-500">Komisi</th>
                    <th class="px-6 py-4 text-left font-medium text-gray-500">Honor</th>
                    <th class="px-6 py-4 text-left font-medium text-gray-500">Status</th>
                    <th class="px-6 py-4 text-left font-medium text-gray-500 w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($honors as $h)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $h->tutor->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600 text-xs leading-tight">
                            {{ $h->rekening_bank ?? '-' }}<br>
                            <span class="font-mono">{{ $h->no_rekening ?? '-' }}</span><br>
                            <span class="text-gray-500">{{ $h->nama_rekening ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">Rp {{ number_format($h->jumlah_bruto ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $h->komisi_platform ?? 0 }}%</td>
                        <td class="px-6 py-4 font-bold text-emerald-700">
                            Rp {{ number_format($h->jumlah_honor ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($h->status === 'ditransfer')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    ✓ Ditransfer
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                    ⏳ Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if ($h->status === 'pending')
                                <button onclick="openTransferModal({{ $h->id }}, '{{ addslashes($h->tutor->name ?? '') }}', 'Rp {{ number_format($h->jumlah_honor ?? 0, 0, ',', '.') }}')"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition">
                                    Transfer
                                </button>
                            @else
                                @if ($h->bukti_transfer)
                                    <a href="{{ Storage::url($h->bukti_transfer) }}" target="_blank"
                                       class="text-blue-600 hover:text-blue-700 hover:underline text-sm flex items-center gap-1">
                                        📄 Lihat Bukti
                                    </a>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                            Tidak ada data honor.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $honors->withQueryString()->links() }}</div>
</div>

{{-- Transfer Modal --}}
<div id="modal-transfer" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50">
    <div class="bg-white rounded-3xl shadow-2xl p-6 w-full max-w-md mx-4">
        <h2 class="text-xl font-semibold text-gray-800 mb-1">Konfirmasi Transfer</h2>
        <p class="text-gray-600 mb-6" id="modal-desc"></p>

        <form id="modal-form" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Bukti Transfer <span class="text-red-500">*</span>
                </label>
                <input type="file" name="bukti_transfer" id="bukti_transfer"
                       required accept=".jpg,.jpeg,.png,.pdf"
                       class="w-full text-sm file:mr-4 file:py-3 file:px-6 file:rounded-2xl
                              file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p id="file-name" class="text-xs text-gray-500 mt-1"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (opsional)</label>
                <textarea name="catatan" rows="3" maxlength="255"
                    class="w-full border border-gray-300 rounded-2xl px-4 py-3 text-sm resize-y"></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal()"
                        class="flex-1 py-3.5 border border-gray-300 rounded-2xl text-sm font-medium hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" id="submit-btn"
                        class="flex-1 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-2xl text-sm transition">
                    Konfirmasi Transfer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openTransferModal(id, nama, honor) {
    document.getElementById('modal-desc').innerHTML = `
        <strong>${nama}</strong><br>
        <span class="text-emerald-600">${honor}</span>
    `;

    // Action sesuai route yang baru
    document.getElementById('modal-form').action = `{{ url('admin/honor') }}/${id}/transfer`;

    document.getElementById('modal-transfer').classList.remove('hidden');
    document.getElementById('bukti_transfer').value = '';
    document.getElementById('file-name').textContent = '';
}

function closeModal() {
    document.getElementById('modal-transfer').classList.add('hidden');
}

// Preview nama file
document.getElementById('bukti_transfer').addEventListener('change', function() {
    const fileName = this.files[0] ? this.files[0].name : '';
    document.getElementById('file-name').textContent = fileName;
});

// Loading state saat submit
document.getElementById('modal-form').addEventListener('submit', function() {
    const btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.innerHTML = `
        <span class="inline-block animate-spin mr-2">⟳</span>
        Memproses...
    `;
});
</script>
</x-app-layout>
