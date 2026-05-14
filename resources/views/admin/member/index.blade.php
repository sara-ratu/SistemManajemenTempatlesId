<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Member / Member</h2>
    </x-slot>

    <div class="px-6 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Member</h1>
            <div class="text-sm text-gray-500">
                Total: {{ $members->total() }} member
            </div>
        </div>

        {{-- Search & Filter nanti bisa ditambah --}}

        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left">Nama</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">No HP</th>
                        <th class="px-6 py-4 text-center">Booking</th>
                        <th class="px-6 py-4 text-center">Verifikasi</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($members as $member)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">{{ $member->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $member->email }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $member->no_hp ?? '-' }}</td>
                        <td class="px-6 py-4 text-center font-semibold text-blue-600">
                            {{ $member->bookings_as_Member_count }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($member->is_verified)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">✓ Terverifikasi</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($member->is_active ?? true)
                                <span class="text-green-600 font-medium">Aktif</span>
                            @else
                                <span class="text-red-600 font-medium">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.member.edit', $member->id) }}"
                                   class="text-blue-600 hover:text-blue-700">
                                    ✏️ Edit
                                </a>
                                <button onclick="toggleStatus({{ $member->id }}, '{{ $member->name }}')"
                                        class="text-orange-600 hover:text-orange-700">
                                    🔄 Status
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            Belum ada data member.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $members->links() }}
        </div>
    </div>

    <script>
    function toggleStatus(id, name) {
        if (confirm(`Ubah status akun ${name}?`)) {
            window.location.href = `{{ url('admin/member') }}/${id}/toggle-status`;
        }
    }
    </script>
</x-app-layout>
