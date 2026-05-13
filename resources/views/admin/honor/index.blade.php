{{-- resources/views/admin/honor/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Honor Tutor')

@section('content')
<div class="px-6 py-8">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Honor Tutor</h1>

    {{-- Summary --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
            <p class="text-xs text-yellow-700 font-medium uppercase tracking-wide">Belum Ditransfer</p>
            <p class="text-2xl font-bold text-yellow-800 mt-1">{{ $summary['pending'] }}</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
            <p class="text-xs text-green-700 font-medium uppercase tracking-wide">Sudah Ditransfer</p>
            <p class="text-2xl font-bold text-green-800 mt-1">{{ $summary['ditransfer'] }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <p class="text-xs text-blue-700 font-medium uppercase tracking-wide">Total Pending</p>
            <p class="text-xl font-bold text-blue-800 mt-1">Rp {{ number_format($summary['total'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="flex gap-2 mb-4">
        @foreach(['pending' => 'Belum Transfer', 'ditransfer' => 'Sudah Transfer', 'all' => 'Semua'] as $val => $label)
            <a href="{{ route('admin.honor.index', ['status' => $val]) }}"
               class="px-4 py-1.5 rounded-full text-sm font-medium transition
                      {{ $status === $val ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Tutor</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Rekening</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Bruto</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Komisi</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Honor</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($honors as $h)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $h->tutor->name }}</td>
                        <td class="px-4 py-3 text-gray-600 text-xs">
                            {{ $h->rekening_bank ?? '-' }}<br>
                            <span class="font-mono">{{ $h->no_rekening ?? '-' }}</span><br>
                            {{ $h->nama_rekening ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">Rp {{ number_format($h->jumlah_bruto, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $h->komisi_platform }}%</td>
                        <td class="px-4 py-3 font-bold text-gray-800">{{ $h->jumlah_honor_rp }}</td>
                        <td class="px-4 py-3">
                            @if ($h->status === 'ditransfer')
                                <span class="px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 font-medium">Ditransfer</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 font-medium">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if ($h->status === 'pending')
                                <button onclick="openTransferModal({{ $h->id }}, '{{ $h->tutor->name }}', '{{ $h->jumlah_honor_rp }}')"
                                        class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg font-medium">
                                    Transfer
                                </button>
                            @else
                                @if ($h->bukti_transfer)
                                    <a href="{{ Storage::url($h->bukti_transfer) }}" target="_blank"
                                       class="text-xs text-blue-600 hover:underline">Bukti</a>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">Tidak ada data honor.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $honors->withQueryString()->links() }}</div>
</div>

{{-- Modal Transfer --}}
<div id="modal-transfer" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md mx-4">
        <h2 class="text-lg font-bold text-gray-800 mb-1">Konfirmasi Transfer</h2>
        <p class="text-sm text-gray-500 mb-4" id="modal-desc">–</p>

        <form id="modal-form" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Bukti Transfer <span class="text-gray-400">(JPG/PNG/PDF)</span>
                </label>
                <input type="file" name="bukti_transfer" required accept=".jpg,.jpeg,.png,.pdf"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                <input type="text" name="catatan" maxlength="200"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeModal()"
                        class="flex-1 border border-gray-300 text-gray-600 py-2 rounded-xl text-sm hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-xl text-sm">
                    Konfirmasi Transfer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openTransferModal(id, nama, honor) {
    document.getElementById('modal-desc').textContent = nama + ' — ' + honor;
    document.getElementById('modal-form').action = '/admin/honor/' + id + '/transfer';
    document.getElementById('modal-transfer').classList.remove('hidden');
}
function closeModal() {
    document.getElementById('modal-transfer').classList.add('hidden');
}
</script>
@endsection
