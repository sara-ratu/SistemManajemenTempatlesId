{{-- resources/views/murid/pembayaran/create.blade.php --}}
@extends('layouts.murid')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
<div class="max-w-xl mx-auto py-8 px-4">

    <h1 class="text-2xl font-bold text-gray-800 mb-1">Upload Bukti Pembayaran</h1>
    <p class="text-gray-500 text-sm mb-6">
        Booking bersama <strong>{{ $booking->tutor->name }}</strong>
        — {{ $booking->tanggal->isoFormat('D MMMM Y') }}
        pukul {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
    </p>

    {{-- Info rekening tujuan --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
        <p class="text-sm font-semibold text-blue-800 mb-2">Transfer ke:</p>
        <div class="text-sm text-blue-700 space-y-1">
            <p>Bank BCA — <strong>1234567890</strong></p>
            <p>a.n. <strong>Tempatles.id</strong></p>
            <p class="mt-2 font-semibold text-lg">{{ 'Rp ' . number_format($booking->harga, 0, ',', '.') }}</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 mb-6 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('murid.pembayaran.store', $booking) }}"
          method="POST" enctype="multipart/form-data"
          class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 space-y-5">
        @csrf

        {{-- Metode --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
            <select name="metode"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                <option value="transfer_bank" {{ old('metode') === 'transfer_bank' ? 'selected' : '' }}>Transfer Bank</option>
                <option value="qris"          {{ old('metode') === 'qris'          ? 'selected' : '' }}>QRIS</option>
                <option value="tunai"         {{ old('metode') === 'tunai'         ? 'selected' : '' }}>Tunai (titip admin)</option>
            </select>
        </div>

        {{-- Upload bukti --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Bukti Transfer <span class="text-gray-400">(JPG/PNG/PDF, maks 2MB)</span>
            </label>
            <input type="file" name="bukti_transfer" accept=".jpg,.jpeg,.png,.pdf"
                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                          file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700
                          hover:file:bg-blue-100">
            @error('bukti_transfer')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition">
            Kirim Bukti Pembayaran
        </button>
    </form>

</div>
@endsection
