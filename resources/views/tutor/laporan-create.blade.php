{{-- resources/views/tutor/laporan/create.blade.php --}}
@extends('layouts.tutor')

@section('title', 'Laporan Sesi')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">

    <h1 class="text-2xl font-bold text-gray-800 mb-1">Laporan Sesi</h1>
    <p class="text-gray-500 mb-6">
        Murid: <strong>{{ $booking->murid->name }}</strong> —
        Booking #{{ $booking->id }}
    </p>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 mb-6 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    @if ($laporan?->isApproved())
        <div class="bg-blue-50 border border-blue-200 text-blue-700 rounded-lg px-4 py-3 mb-6 text-sm">
            ✅ Laporan ini sudah disetujui oleh admin.
        </div>
    @endif

    <form action="{{ route('tutor.laporan.store', $booking) }}" method="POST"
          class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Sesi</label>
            <input type="date" name="tanggal_sesi"
                   value="{{ old('tanggal_sesi', $laporan?->tanggal_sesi?->format('Y-m-d') ?? $booking->tanggal?->format('Y-m-d')) }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"
                   {{ $laporan?->isApproved() ? 'disabled' : '' }}>
            @error('tanggal_sesi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Materi yang Diajarkan <span class="text-red-500">*</span>
            </label>
            <textarea name="materi_diajarkan" rows="4" maxlength="2000"
                      placeholder="Contoh: Persamaan kuadrat, rumus abc, latihan soal UN 2024..."
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 resize-none"
                      {{ $laporan?->isApproved() ? 'disabled' : '' }}>{{ old('materi_diajarkan', $laporan?->materi_diajarkan) }}</textarea>
            @error('materi_diajarkan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Perkembangan Murid</label>
            <textarea name="perkembangan_murid" rows="3" maxlength="2000"
                      placeholder="Ceritakan kemajuan atau pemahaman murid selama sesi..."
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 resize-none"
                      {{ $laporan?->isApproved() ? 'disabled' : '' }}>{{ old('perkembangan_murid', $laporan?->perkembangan_murid) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kendala</label>
            <textarea name="kendala" rows="2" maxlength="1000"
                      placeholder="Kendala teknis, materi sulit, dll (opsional)..."
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 resize-none"
                      {{ $laporan?->isApproved() ? 'disabled' : '' }}>{{ old('kendala', $laporan?->kendala) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
            <textarea name="catatan_tambahan" rows="2" maxlength="1000"
                      placeholder="PR untuk murid, rencana sesi berikutnya, dll..."
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 resize-none"
                      {{ $laporan?->isApproved() ? 'disabled' : '' }}>{{ old('catatan_tambahan', $laporan?->catatan_tambahan) }}</textarea>
        </div>

        @unless ($laporan?->isApproved())
        <div class="flex gap-3 pt-2">
            <button type="submit" name="aksi" value="draft"
                    class="flex-1 border border-gray-300 text-gray-700 rounded-lg py-2 text-sm hover:bg-gray-50 transition-colors">
                Simpan Draft
            </button>
            <button type="submit" name="aksi" value="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg py-2 text-sm font-medium transition-colors">
                Kirim Laporan
            </button>
        </div>
        @endunless

    </form>
</div>
@endsection
