<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Schedule;
use App\Models\TutorProfile;
use App\Models\Booking;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    // Dashboard tutor
    public function dashboard()
    {
        $user    = auth()->user();
        $profile = $user->tutorProfile;
        $bookings = Booking::where('tutor_id', $user->id)
            ->with(['murid', 'subject'])
            ->latest()
            ->take(5)
            ->get();

        return view('tutor.dashboard', compact('user', 'profile', 'bookings'));
    }

    // Form edit profil
    public function editProfil()
    {
        $user     = auth()->user();
        $profile  = $user->tutorProfile ?? new TutorProfile();
        $subjects = Subject::where('is_active', true)->get();
        $selectedMapel = $profile->subjects?->pluck('id')->toArray() ?? [];

        return view('tutor.profil', compact('user', 'profile', 'subjects', 'selectedMapel'));
    }

    // Simpan profil
    public function simpanProfil(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'no_hp'      => 'nullable|string|max:20',
            'kota'       => 'required|string|max:100',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
            'bio'        => 'nullable|string',
            'harga_min'  => 'required|integer|min:0',
            'harga_max'  => 'required|integer|min:0',
            'pendidikan' => 'nullable|string',
            'universitas'=> 'nullable|string',
            'subject_ids'=> 'nullable|array',
        ]);

        // Update data user
        auth()->user()->update([
            'name'      => $request->name,
            'no_hp'     => $request->no_hp,
            'kota'      => $request->kota,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // Update atau buat profil tutor
        $profile = TutorProfile::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'bio'         => $request->bio,
                'harga_min'   => $request->harga_min,
                'harga_max'   => $request->harga_max,
                'pendidikan'  => $request->pendidikan,
                'universitas' => $request->universitas,
            ]
        );

        // Sync mata pelajaran
        $profile->subjects()->sync($request->subject_ids ?? []);

        return redirect()->route('tutor.profil')
            ->with('success', 'Profil berhasil disimpan!');
    }

    // Halaman kelola jadwal
    public function jadwal()
    {
        $profile   = auth()->user()->tutorProfile;
        $schedules = $profile ? $profile->schedules()->orderByRaw(
            "FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')"
        )->get() : collect();

        return view('tutor.jadwal', compact('schedules', 'profile'));
    }

    // Simpan jadwal
    public function simpanJadwal(Request $request)
    {
        $request->validate([
            'hari'       => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'  => 'required|date_format:H:i',
            'jam_selesai'=> 'required|date_format:H:i|after:jam_mulai',
        ]);

        $profile = auth()->user()->tutorProfile;
        if (!$profile) {
            return back()->with('error', 'Lengkapi profil dulu sebelum mengatur jadwal.');
        }

        Schedule::create([
            'tutor_profile_id' => $profile->id,
            'hari'             => $request->hari,
            'jam_mulai'        => $request->jam_mulai,
            'jam_selesai'      => $request->jam_selesai,
            'is_available'     => true,
        ]);

        return back()->with('success', 'Jadwal berhasil ditambahkan!');
    }

    // Hapus jadwal
    public function hapusJadwal(Schedule $schedule)
    {
        $schedule->delete();
        return back()->with('success', 'Jadwal dihapus.');
    }

    // Konfirmasi booking
    public function konfirmasiBooking(Booking $booking, string $aksi)
    {
        if ($booking->tutor_id !== auth()->id()) abort(403);

        $booking->update([
            'status' => $aksi === 'confirm' ? 'confirmed' : 'cancelled'
        ]);

        return back()->with('success', 'Status booking diperbarui.');
    }
}
