{{-- resources/views/Member/review/create.blade.php --}}
@extends('layouts.Member')

@section('title', 'Beri Ulasan')

@section('content')
<div class="max-w-xl mx-auto py-8 px-4">

    <h1 class="text-2xl font-bold text-gray-800 mb-1">Beri Ulasan</h1>
    <p class="text-gray-500 text-sm mb-6">
        Sesi bersama <strong>{{ $booking->tutor->name }}</strong>
        — {{ $booking->tanggal->isoFormat('D MMMM Y') }}
        pukul {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
    </p>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 mb-6 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('Member.review.store', $booking) }}" method="POST"
          class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 space-y-6">
        @csrf

        {{-- Star rating --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Rating</label>

            <div class="flex gap-1" id="star-container">
                @for ($i = 1; $i <= 5; $i++)
                    <button type="button" data-value="{{ $i }}"
                            class="star-btn text-5xl leading-none text-gray-200 hover:text-yellow-400 transition-colors focus:outline-none"
                            aria-label="{{ $i }} bintang">★</button>
                @endfor
            </div>

            <input type="hidden" name="rating" id="rating-input" value="{{ old('rating', 0) }}">

            <p class="text-sm text-gray-400 mt-2" id="rating-label">Pilih rating di atas</p>

            @error('rating')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Komentar --}}
        <div>
            <label for="komentar" class="block text-sm font-medium text-gray-700 mb-1">
                Komentar <span class="text-gray-400 font-normal">(opsional)</span>
            </label>
            <textarea id="komentar" name="komentar" rows="4" maxlength="1000"
                      placeholder="Ceritakan pengalaman belajarmu dengan tutor ini..."
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm
                             focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none">{{ old('komentar') }}</textarea>
            <p class="text-xs text-gray-400 mt-1" id="char-count">0 / 1000 karakter</p>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl transition">
            Kirim Ulasan
        </button>
    </form>
</div>

<script>
const labels = ['', 'Sangat Kurang', 'Kurang', 'Cukup', 'Puas', 'Sangat Puas'];
const stars   = document.querySelectorAll('.star-btn');
const input   = document.getElementById('rating-input');
const label   = document.getElementById('rating-label');
const textarea = document.getElementById('komentar');
const charCount = document.getElementById('char-count');

let current = parseInt(input.value) || 0;

function paint(n) {
    stars.forEach((s, i) => {
        s.classList.toggle('text-yellow-400', i < n);
        s.classList.toggle('text-gray-200',   i >= n);
    });
}

stars.forEach(btn => {
    const v = parseInt(btn.dataset.value);

    btn.addEventListener('mouseenter', () => paint(v));
    btn.addEventListener('mouseleave', () => paint(current));
    btn.addEventListener('click', () => {
        current = v;
        input.value = v;
        label.textContent = labels[v];
        paint(v);
    });
});

// Init jika ada old value
if (current > 0) { paint(current); label.textContent = labels[current]; }

// Char counter
textarea.addEventListener('input', () => {
    charCount.textContent = textarea.value.length + ' / 1000 karakter';
});
</script>
@endsection
