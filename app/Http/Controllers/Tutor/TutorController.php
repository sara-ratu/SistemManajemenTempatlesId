<?php

namespace App\Http\Controllers\Tutor;

use App\Models\Subject;
use App\Models\Schedule;
use App\Models\TutorProfile;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TutorRegistration;
use App\Http\Controllers\Controller;

class TutorController extends Controller
{

    // nabela

    public function index()
    {
        $tutors = TutorRegistration::latest()->paginate(15);
        return view('tutor.index', compact('tutors'));
    }

    public function create()
    {
        return view('tutor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'        => 'required',
            'jenis_kelamin'       => 'required',
            'tempat_lahir'        => 'required',
            'tanggal_lahir'       => 'required|date',
            'alamat_domisili'     => 'required',
            'no_wa'               => 'required',
            'email'               => 'required|email',
            'pendidikan_terakhir' => 'required',
            'asal_sekolah'        => 'required',
            'bidang_keahlian'     => 'required',
            'file_silabus'        => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $data = $request->all();

        if ($request->hasFile('file_silabus')) {
            $file     = $request->file('file_silabus');
            $filename = time() . '_' . str_replace(' ', '_', $request->nama_lengkap) . '.pdf';
            $data['file_silabus'] = $file->storeAs('silabus', $filename, 'public');
        }

        TutorRegistration::create($data);

        return redirect()->route('tutor.index')
            ->with('success', '✅ Data tutor berhasil ditambahkan!');
    }

    // =========================================================
    // Dashboard tutor
    // =========================================================
    public function dashboard()
    {
        $user     = auth()->user();
        $profile  = $user->tutorProfile;
        $bookings = Booking::where('tutor_id', $user->id)
            ->with(['member', 'subject'])
            ->latest()
            ->take(5)
            ->get();

        return view('tutor.dashboard', compact('user', 'profile', 'bookings'));
    }

    // =========================================================
    // Form edit profil — field lengkap
    // =========================================================
    public function editProfil()
    {
        $user          = auth()->user();
        $profile       = $user->tutorProfile ?? new TutorProfile();
        $subjects      = Subject::where('is_active', true)->get();
        $selectedMapel = $profile->subjects?->pluck('id')->toArray() ?? [];

        return view('tutor.profil', compact('user', 'profile', 'subjects', 'selectedMapel'));
    }

    // =========================================================
    // Simpan profil — termasuk field baru + upload dokumen
    // =========================================================
    public function simpanProfil(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'no_wa'           => 'nullable|string|max:20',
            'jenis_kelamin'   => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir'    => 'nullable|string|max:100',
            'tanggal_lahir'   => 'nullable|date',
            'kota'            => 'required|string|max:100',
            'pendidikan'      => 'nullable|string|max:100',
            'universitas'     => 'nullable|string|max:150',
            'pengalaman'      => 'nullable|integer|min:0|max:50',
            'jenjang'         => 'nullable|array',
            'metode_mengajar' => 'nullable|array',
            'bio'             => 'nullable|string|max:1000',
            'harga_min'       => 'required|integer|min:0',
            'harga_max'       => 'required|integer|min:0',
            'subject_ids'     => 'nullable|array',
            'foto_profil'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'file_ijazah'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'latitude'        => 'nullable|numeric',
            'longitude'       => 'nullable|numeric',
        ], [
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'harga_min.required'     => 'Harga minimum wajib diisi.',
            'harga_max.required'     => 'Harga maksimum wajib diisi.',
            'foto_profil.image'      => 'Foto profil harus berupa gambar.',
            'foto_profil.max'        => 'Foto profil maksimal 2MB.',
            'file_ijazah.max'        => 'File ijazah maksimal 5MB.',
            'file_sertifikat.max'    => 'File sertifikat maksimal 5MB.',
        ]);

        // --- Update data user ---
        auth()->user()->update([
            'name'      => $request->name,
            'no_wa'     => $request->no_wa,
            'kota'      => $request->kota,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // --- Siapkan data profil ---
        $profileData = [
            'jenis_kelamin'   => $request->jenis_kelamin,
            'tempat_lahir'    => $request->tempat_lahir,
            'tanggal_lahir'   => $request->tanggal_lahir,
            'bio'             => $request->bio,
            'harga_min'       => $request->harga_min,
            'harga_max'       => $request->harga_max,
            'pendidikan'      => $request->pendidikan,
            'universitas'     => $request->universitas,
            'pengalaman'      => $request->pengalaman,
            'jenjang'         => $request->jenjang ? implode(',', $request->jenjang) : null,
            'metode_mengajar' => $request->metode_mengajar ? implode(',', $request->metode_mengajar) : null,
        ];

        // --- Upload foto profil ---
        if ($request->hasFile('foto_profil')) {
            $existing = TutorProfile::where('user_id', auth()->id())->value('foto_profil');
            if ($existing) Storage::disk('public')->delete($existing);

            $ext  = $request->file('foto_profil')->getClientOriginalExtension();
            $name = 'foto_' . auth()->id() . '_' . time() . '.' . $ext;
            $profileData['foto_profil'] = $request->file('foto_profil')
                ->storeAs('uploads/foto', $name, 'public');
        }

        // --- Upload ijazah ---
        if ($request->hasFile('file_ijazah')) {
            $existing = TutorProfile::where('user_id', auth()->id())->value('file_ijazah');
            if ($existing) Storage::disk('public')->delete($existing);

            $ext  = $request->file('file_ijazah')->getClientOriginalExtension();
            $name = 'ijazah_' . auth()->id() . '_' . time() . '.' . $ext;
            $profileData['file_ijazah'] = $request->file('file_ijazah')
                ->storeAs('uploads/dokumen', $name, 'public');
        }

        // --- Upload sertifikat ---
        if ($request->hasFile('file_sertifikat')) {
            $existing = TutorProfile::where('user_id', auth()->id())->value('file_sertifikat');
            if ($existing) Storage::disk('public')->delete($existing);

            $ext  = $request->file('file_sertifikat')->getClientOriginalExtension();
            $name = 'sertifikat_' . auth()->id() . '_' . time() . '.' . $ext;
            $profileData['file_sertifikat'] = $request->file('file_sertifikat')
                ->storeAs('uploads/dokumen', $name, 'public');
        }

        // --- Simpan/update profil ---
        $profile = TutorProfile::updateOrCreate(
            ['user_id' => auth()->id()],
            $profileData
        );

        // --- Sync mata pelajaran ---
        $profile->subjects()->sync($request->subject_ids ?? []);

        return redirect()->route('tutor.profil')
            ->with('success', 'Profil berhasil disimpan!');
    }

    // =========================================================
    // Jadwal — tetap di sini (belum dipindah ke ScheduleController)
    // =========================================================
    public function jadwal()
    {
        $profile   = auth()->user()->tutorProfile;
        $schedules = $profile ? $profile->schedules()->orderByRaw(
            "FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')"
        )->get() : collect();

        return view('tutor.jadwal', compact('schedules', 'profile'));
    }

    public function simpanJadwal(Request $request)
    {
        $request->validate([
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
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

    public function hapusJadwal(Schedule $schedule)
    {
        $schedule->delete();
        return back()->with('success', 'Jadwal dihapus.');
    }

    // =========================================================
    // Konfirmasi booking
    // =========================================================
    public function konfirmasiBooking(Booking $booking, string $aksi)
    {
        if ($booking->tutor_id !== auth()->id()) abort(403);

        $booking->update([
            'status' => $aksi === 'confirm' ? 'confirmed' : 'cancelled'
        ]);

        return back()->with('success', 'Status booking diperbarui.');
    }
}
