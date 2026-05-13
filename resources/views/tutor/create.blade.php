<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-0 font-semibold">Tambah Laporan Sesi</h4>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

            <!-- Header Form -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6 text-white">
                <h5 class="text-xl font-semibold flex items-center gap-3">
                    <i class="fas fa-file-alt"></i>
                    Form Laporan Sesi
                </h5>
            </div>

            <div class="p-8">

                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-5 py-3 rounded-xl">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-5 py-3 rounded-xl">
                        <ul class="list-disc pl-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('tutor.laporan.store') }}"
                      method="POST"
                      class="space-y-6">

                    @csrf

                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Nama Murid --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Murid
                            </label>

                            <input type="text"
                                   value="{{ $booking->murid->name ?? '-' }}"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-gray-100"
                                   readonly>
                        </div>

                        {{-- Tanggal Sesi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tanggal Sesi
                            </label>

                            <input type="date"
                                   name="tanggal_sesi"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500"
                                   required>
                        </div>

                        {{-- Materi --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Materi Diajarkan
                            </label>

                            <textarea name="materi_diajarkan"
                                      rows="4"
                                      class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500"
                                      required></textarea>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Status Laporan
                            </label>

                            <select name="status_laporan"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500">

                                <option value="draft">Draft</option>
                                <option value="submitted">Submit</option>

                            </select>
                        </div>

                    </div>

                    <div class="pt-4">
                        <button type="submit"
                                class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium px-8 py-4 rounded-xl transition">
                            <i class="fas fa-save"></i>
                            Simpan Laporan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
