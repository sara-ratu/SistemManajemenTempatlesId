{{-- resources/views/admin/pembayaran/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail Pembayaran #' . $pembayaran->id)

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.pembayaran.index') }}" class="text-gray-400 hover:text-gray-600">← Kembali</a>
        <h1 class="text-xl font-bold text-gray-800">Detail Pembayaran #{{ $pembayaran->id }}</h1>
        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $pembayaran->status_badge }}">
            {{ $pembayaran->status_label }}
        </span>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Info booking --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4 shadow-sm">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Info Booking</h2>
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div>
                <p class="text-gray-400">Member</p>
                <p class="font-medium text-gray-800">{{ $pembayaran->Member->name }}</p>
                <p class="text-gray-500 text-xs">{{ $pembayaran->Member->email }}</p>
            </div>
            <div>
                <p class="text-gray-400">Tutor</p>
                <p class="font-medium text-gray-800">{{ $pembayaran->booking->tutor->name }}</p>
            </div>
            <div>
                <p class="text-gray-400">Tanggal Sesi</p>
                <p class="font-medium text-gray-800">
                    {{ $pembayaran->booking->tanggal->isoFormat('D MMMM Y') }}
                    {{ \Carbon\Carbon::parse($pembayaran->booking->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($pembayaran->booking->jam_selesai)->format('H:i') }}
                </p>
            </div>
            <div>
                <p class="text-gray-400">Jumlah Tagihan</p>
                <p class="font-bold text-gray-800 text-base">{{ $pembayaran->jumlah_rp }}</p>
            </div>
            <div>
                <p class="text-gray-400">Metode</p>
                <p class="font-medium text-gray-800 capitalize">{{ str_replace('_', ' ', $pembayaran->metode) }}</p>
            </div>
            <div>
                <p class="text-gray-400">Dikirim</p>
                <p class="font-medium text-gray-800">{{ $pembayaran->created_at->isoFormat('D MMM Y HH:mm') }}</p>
            </div>
        </div>
    </div>

    {{-- Bukti transfer --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4 shadow-sm">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Bukti Transfer</h2>
        @if ($pembayaran->bukti_transfer)
            @php $ext = pathinfo($pembayaran->bukti_transfer, PATHINFO_EXTENSION); @endphp
            @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                <img src="{{ Storage::url($pembayaran->bukti_transfer) }}"
                     alt="Bukti Transfer"
                     class="max-w-full rounded-lg border border-gray-200">
            @else
                <a href="{{ Storage::url($pembayaran->bukti_transfer) }}" target="_blank"
                   class="inline-flex items-center gap-2 text-blue-600 hover:underline text-sm">
                    📄 Lihat file PDF
                </a>
            @endif
        @else
            <p class="text-gray-400 text-sm">Tidak ada bukti.</p>
        @endif
    </div>

    {{-- Aksi (hanya jika pending) --}}
    @if ($pembayaran->status === 'pending')
        <div class="flex gap-3">
            {{-- Verifikasi --}}
            <form action="{{ route('admin.pembayaran.verify', $pembayaran) }}" method="POST" class="flex-1">
                @csrf
                @method('PATCH')
                <button type="submit"
                        onclick="return confirm('Verifikasi pembayaran ini?')"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-xl transition">
                    ✓ Verifikasi
                </button>
            </form>

            {{-- Tolak --}}
            <button onclick="document.getElementById('form-tolak').classList.toggle('hidden')"
                    class="flex-1 bg-red-50 hover:bg-red-100 text-red-700 font-semibold py-2.5 rounded-xl border border-red-200 transition">
                ✕ Tolak
            </button>
        </div>

        <div id="form-tolak" class="hidden mt-4 bg-red-50 border border-red-200 rounded-xl p-4">
            <form action="{{ route('admin.pembayaran.reject', $pembayaran) }}" method="POST" class="space-y-3">
                @csrf
                @method('PATCH')
                <label class="block text-sm font-medium text-red-800">Alasan Penolakan</label>
                <textarea name="catatan_admin" rows="3" required
                          placeholder="Mis: bukti tidak jelas, nominal tidak sesuai..."
                          class="w-full border border-red-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-400"></textarea>
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                    Kirim Penolakan
                </button>
            </form>
        </div>
    @endif

    @if ($pembayaran->status === 'verified' && $pembayaran->verifiedBy)
        <p class="text-sm text-gray-400 mt-4">
            Diverifikasi oleh <strong>{{ $pembayaran->verifiedBy->name }}</strong>
            pada {{ $pembayaran->verified_at->isoFormat('D MMM Y HH:mm') }}
        </p>
    @endif

</div>
@endsection
