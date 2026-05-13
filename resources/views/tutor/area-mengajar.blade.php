@extends('layouts.tutor')

@section('title', 'Area Mengajar')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Area Mengajar</h1>
        <p class="text-sm text-gray-500 mt-1">
            Tambahkan kecamatan atau kota yang bisa kamu jangkau untuk les <strong>offline</strong>.
            Maksimal <strong>10 area</strong>.
        </p>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Form tambah area --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5 mb-6 shadow-sm">
        <h2 class="text-base font-medium text-gray-700 mb-4">Tambah Area Baru</h2>

        <form action="{{ route('tutor.area.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Provinsi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Provinsi</label>
                    <input type="text" name="provinsi" value="{{ old('provinsi') }}"
                        placeholder="cth: Jawa Timur"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Kota / Kabupaten --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">
                        Kota / Kabupaten <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kota_kabupaten" value="{{ old('kota_kabupaten') }}"
                        placeholder="cth: Kota Kediri"
                        required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500
                               @error('kota_kabupaten') border-red-400 @enderror">
                    @error('kota_kabupaten')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kecamatan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Kecamatan</label>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan') }}"
                        placeholder="cth: Mojoroto (opsional)"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika mencakup seluruh kota.</p>
                </div>

                {{-- Radius --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Radius (km)</label>
                    <input type="number" name="radius_km" value="{{ old('radius_km', 5) }}"
                        min="1" max="50" step="0.5"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-400 mt-1">Jarak maksimal kamu bersedia datang.</p>
                </div>

            </div>

            {{-- Set primary --}}
            <div class="mt-4 flex items-center gap-2">
                <input type="checkbox" name="is_primary" id="is_primary" value="1"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    {{ old('is_primary') ? 'checked' : '' }}>
                <label for="is_primary" class="text-sm text-gray-600">
                    Jadikan sebagai area utama
                </label>
            </div>

            <div class="mt-5">
                <button type="submit"
                    class="w-full sm:w-auto px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    + Tambah Area
                </button>
            </div>
        </form>
    </div>

    {{-- Daftar area --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-base font-medium text-gray-700">Area Terdaftar</h2>
            <span class="text-xs text-gray-400">{{ $areas->count() }} / 10</span>
        </div>

        @if($areas->isEmpty())
            <div class="px-5 py-10 text-center text-gray-400 text-sm">
                Belum ada area mengajar. Tambahkan area pertamamu di atas.
            </div>
        @else
            <ul class="divide-y divide-gray-100">
                @foreach($areas as $area)
                <li class="flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition">

                    {{-- Info area --}}
                    <div class="flex items-start gap-3">
                        {{-- Badge primary --}}
                        <div class="mt-0.5">
                            @if($area->is_primary)
                                <span class="inline-block w-2.5 h-2.5 rounded-full bg-blue-500 mt-1" title="Area utama"></span>
                            @else
                                <span class="inline-block w-2.5 h-2.5 rounded-full bg-gray-300 mt-1"></span>
                            @endif
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-800">
                                {{ $area->label }}
                                @if($area->is_primary)
                                    <span class="ml-2 text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-normal">Utama</span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $area->provinsi ? $area->provinsi . ' · ' : '' }}Radius {{ $area->radius_km }} km
                            </p>
                        </div>
                    </div>

                    {{-- Aksi --}}
                    <div class="flex items-center gap-2 ml-4 shrink-0">

                        {{-- Set primary --}}
                        @unless($area->is_primary)
                        <form action="{{ route('tutor.area.primary', $area) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="text-xs text-blue-600 hover:underline"
                                title="Jadikan area utama">
                                Set Utama
                            </button>
                        </form>
                        @endunless

                        {{-- Hapus --}}
                        <form action="{{ route('tutor.area.destroy', $area) }}" method="POST"
                            onsubmit="return confirm('Hapus area ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="text-xs text-red-500 hover:underline"
                                title="Hapus area">
                                Hapus
                            </button>
                        </form>

                    </div>
                </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Info tambahan --}}
    <p class="text-xs text-gray-400 mt-4 text-center">
        Area mengajar hanya berlaku untuk sesi <strong>offline</strong>.
        Untuk sesi online, kamu bisa menerima siswa dari mana saja.
    </p>

</div>
@endsection
