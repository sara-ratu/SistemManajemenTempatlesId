<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Log Matching TutorMatch</h2></x-slot>
    <div class="py-8 max-w-7xl mx-auto px-4">
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-3 text-xs text-gray-500 font-medium">Murid</th>
                        <th class="text-left px-4 py-3 text-xs text-gray-500 font-medium">Tutor</th>
                        <th class="text-center px-4 py-3 text-xs text-gray-500 font-medium">Mapel</th>
                        <th class="text-center px-4 py-3 text-xs text-gray-500 font-medium">Lokasi</th>
                        <th class="text-center px-4 py-3 text-xs text-gray-500 font-medium">Harga</th>
                        <th class="text-center px-4 py-3 text-xs text-gray-500 font-medium">Jadwal</th>
                        <th class="text-center px-4 py-3 text-xs text-gray-500 font-medium">Rating</th>
                        <th class="text-center px-4 py-3 text-xs text-gray-500 font-medium">Total</th>
                        <th class="text-left px-4 py-3 text-xs text-gray-500 font-medium">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr class="border-t border-gray-50 hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $log->murid->name }}</td>
                        <td class="px-4 py-3">{{ $log->tutor->name }}</td>
                        <td class="px-4 py-3 text-center">{{ $log->skor_mapel }}</td>
                        <td class="px-4 py-3 text-center">{{ $log->skor_lokasi }}</td>
                        <td class="px-4 py-3 text-center">{{ $log->skor_harga }}</td>
                        <td class="px-4 py-3 text-center">{{ $log->skor_jadwal }}</td>
                        <td class="px-4 py-3 text-center">{{ $log->skor_rating }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="font-semibold text-blue-600">{{ $log->skor_total }}</span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">{{ $logs->links() }}</div>
        </div>
    </div>
</x-app-layout>
