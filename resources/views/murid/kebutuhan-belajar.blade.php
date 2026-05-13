@extends('layouts.app')

@section('title', 'Kebutuhan Belajar')

@section('content')
<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-2xl mx-auto px-4">

        {{-- Progress indicator --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 text-sm">
                <div class="flex items-center gap-2 text-green-600 font-medium">
                    <span class="w-6 h-6 rounded-full bg-green-600 text-white flex items-center justify-center text-xs">✓</span>
                    Daftar Akun
                </div>
                <div class="flex-1 h-px bg-green-200"></div>
                <div class="flex items-center gap-2 text-blue-600 font-medium">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">2</span>
                    Kebutuhan Belajar
                </div>
                <div class="flex-1 h-px bg-gray-200"></div>
                <div class="flex items-center gap-2 text-gray-400">
                    <span class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs">3</span>
                    Temukan Tutor
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-8 py-6 text-white">
                <h1 class="text-xl font-bold">Isi Kebutuhan Belajarmu 📚</h1>
                <p class="text-blue-100 text-sm mt-1">Bantu kami merekomendasikan tutor yang paling cocok untukmu</p>
            </div>

            {{-- Form --}}
            <form action="{{ route('murid.kebutuhan-belajar.store') }}" method="POST" class="p-8 space-y-6">
                @csrf

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Mata pelajaran --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mata Pelajaran yang Dibutuhkan <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @php
                            $subjects = $subjects ?? [
                                'Matematika','Fisika','Kimia','Biologi',
                                'Bahasa Inggris','Bahasa Indonesia',
                                'IPA','IPS','Komputer','Akuntansi'
                            ];
                        @endphp
                        @foreach($subjects as $subject)
                        @php $subjectName = is_object($subject) ? $subject->name : $subject; @endphp
                        <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-colors has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500">
                            <input type="checkbox" name="mata_pelajaran[]" value="{{ $subjectName }}"
                                   class="w-4 h-4 text-blue-600 rounded"
                                   {{ in_array($subjectName, old('mata_pelajaran', [])) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $subjectName }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Jenjang pendidikan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenjang Pendidikan <span class="text-red-500">*</span>
                    </label>
                    <select name="jenjang" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="">-- Pilih jenjang --</option>
                        @foreach(['SD', 'SMP', 'SMA/SMK', 'Kuliah', 'Umum'] as $j)
                        <option value="{{ $j }}" {{ old('jenjang') == $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tipe pembelajaran --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tipe Pembelajaran <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach(['Online' => '💻', 'Offline (Datang ke Rumah)' => '🏠'] as $tipe => $icon)
                        <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-colors has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500">
                            <input type="radio" name="tipe_pembelajaran" value="{{ $tipe }}"
                                   class="w-4 h-4 text-blue-600"
                                   {{ old('tipe_pembelajaran') == $tipe ? 'checked' : '' }}>
                            <span class="text-xl">{{ $icon }}</span>
                            <span class="text-sm font-medium text-gray-700">{{ $tipe }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Target belajar --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Target / Tujuan Belajar
                    </label>
                    <textarea name="target_belajar" rows="3"
                              placeholder="Contoh: Ingin naik nilai Matematika dari 60 ke 80 untuk ujian semester..."
                              class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 resize-none">{{ old('target_belajar') }}</textarea>
                </div>

                {{-- Jadwal preferensi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Preferensi Jadwal Belajar
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                        <label class="flex items-center gap-2 p-2.5 border rounded-lg cursor-pointer hover:bg-blue-50 transition-colors has-[:checked]:bg-blue-50 has-[:checked]:border-blue-400">
                            <input type="checkbox" name="hari_belajar[]" value="{{ $hari }}"
                                   class="w-4 h-4 text-blue-600 rounded"
                                   {{ in_array($hari, old('hari_belajar', [])) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $hari }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Anggaran --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Budget per Sesi (opsional)
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach([
                            '< Rp 50.000' => 'under_50',
                            'Rp 50.000 – 100.000' => '50_100',
                            'Rp 100.000 – 200.000' => '100_200',
                            '> Rp 200.000' => 'above_200',
                        ] as $label => $value)
                        <label class="flex items-center gap-2 p-2.5 border rounded-lg cursor-pointer hover:bg-blue-50 transition-colors has-[:checked]:bg-blue-50 has-[:checked]:border-blue-400">
                            <input type="radio" name="budget" value="{{ $value }}"
                                   class="w-4 h-4 text-blue-600"
                                   {{ old('budget') == $value ? 'checked' : '' }}>
                            <span class="text-xs text-gray-700">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Catatan tambahan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Tambahan
                    </label>
                    <textarea name="catatan" rows="2"
                              placeholder="Ada hal lain yang perlu diketahui tutor?"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 resize-none">{{ old('catatan') }}</textarea>
                </div>

                <div class="pt-2 flex gap-3">
                    <a href="{{ route('murid.dashboard') }}"
                       class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-600 hover:bg-gray-50 text-center transition-colors">
                        Lewati dulu
                    </a>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-colors">
                        Simpan & Cari Tutor →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
