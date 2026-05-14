{{-- resources/views/admin/review/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Moderasi Ulasan')

@section('content')
<div class="px-6 py-8">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Moderasi Ulasan</h1>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg p-3 mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Member</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Tutor</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Rating</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Komentar</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Tgl</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($reviews as $r)
                    <tr class="{{ $r->is_visible ? '' : 'opacity-50 bg-gray-50' }}">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $r->Member->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $r->tutor->name }}</td>
                        <td class="px-4 py-3">
                            <span class="text-yellow-500 font-mono tracking-tighter text-base">
                                {{ str_repeat('★', $r->rating) }}<span class="text-gray-200">{{ str_repeat('★', 5 - $r->rating) }}</span>
                            </span>
                            <span class="text-gray-500 text-xs ml-1">{{ $r->rating }}/5</span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 max-w-xs">
                            <p class="truncate">{{ $r->komentar ?: '—' }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-400 text-xs whitespace-nowrap">
                            {{ $r->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3">
                            @if ($r->is_visible)
                                <span class="px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 font-medium">Tampil</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-500 font-medium">Disembunyikan</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                {{-- Toggle --}}
                                <form action="{{ route('admin.review.toggle', $r) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="text-xs px-2 py-1 rounded-lg border font-medium transition
                                                   {{ $r->is_visible
                                                       ? 'border-gray-300 text-gray-600 hover:bg-gray-100'
                                                       : 'border-green-300 text-green-700 hover:bg-green-50' }}">
                                        {{ $r->is_visible ? 'Sembunyikan' : 'Tampilkan' }}
                                    </button>
                                </form>

                                {{-- Hapus --}}
                                <form action="{{ route('admin.review.destroy', $r) }}" method="POST"
                                      onsubmit="return confirm('Hapus ulasan ini permanen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-xs px-2 py-1 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 font-medium">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">Belum ada ulasan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $reviews->links() }}</div>
</div>
@endsection
