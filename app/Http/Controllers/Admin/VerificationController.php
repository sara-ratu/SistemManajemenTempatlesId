<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TutorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    // ─── TUTOR ───────────────────────────────────────────────────────────

    public function index()
    {
        $pendingTutors = TutorProfile::with('user')
            ->where('status_verifikasi', 'pending')
            ->latest()
            ->paginate(10, ['*'], 'tutor_page');

        $pendingMembers = User::where('role', 'murid')
            ->where('is_verified', false)
            ->latest()
            ->paginate(10, ['*'], 'member_page');

        return view('admin.verification.index', compact('pendingTutors', 'pendingMembers'));
    }

    public function tutorDetail($id)
    {
        $tutor = TutorProfile::with(['user', 'subjects', 'areas', 'schedules'])
            ->findOrFail($id);

        return view('admin.verification.tutor-detail', compact('tutor'));
    }

    public function approveTutor(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        $profile = TutorProfile::with('user')->findOrFail($id);

        $profile->update([
            'status_verifikasi'  => 'verified',
            'verified_at'        => now(),
            'verified_by'        => auth()->id(),
            'catatan_verifikasi' => $request->catatan,
        ]);

        $profile->user->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        $this->kirimWA(
            $profile->no_wa ?? $profile->user->no_hp,
            $this->pesanApprove($profile->user->name, $request->catatan)
        );

        return redirect()->route('admin.verification.index')
            ->with('success', 'Tutor ' . $profile->user->name . ' berhasil diverifikasi.');
    }

    public function rejectTutor(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|max:500',
        ]);

        $profile = TutorProfile::with('user')->findOrFail($id);

        $profile->update([
            'status_verifikasi' => 'rejected',
            'rejection_reason'  => $request->alasan,
            'verified_by'       => auth()->id(),
            'verified_at'       => null,
        ]);

        $this->kirimWA(
            $profile->no_wa ?? $profile->user->no_hp,
            $this->pesanReject($profile->user->name, $request->alasan)
        );

        return redirect()->route('admin.verification.index')
            ->with('success', 'Tutor ' . $profile->user->name . ' telah ditolak.');
    }

    // ─── MEMBER ──────────────────────────────────────────────────────────

    public function approveMember($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Member ' . $user->name . ' berhasil diverifikasi.');
    }

    public function rejectMember(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|max:500',
        ]);

        $user = User::findOrFail($id);

        return back()->with('info', 'Member ' . $user->name . ' ditolak: ' . $request->alasan);
    }

    // ─── WHATSAPP CLOUD API (Meta) ────────────────────────────────────────

    /**
     * Kirim pesan teks via Meta WhatsApp Cloud API
     * Docs: https://developers.facebook.com/docs/whatsapp/cloud-api/messages/text-messages
     */
    private function kirimWA(?string $nomorWA, string $pesan): void
    {
        $token   = config('services.whatsapp.token');
        $phoneId = config('services.whatsapp.phone_number_id');

        if (! $nomorWA || ! $token || ! $phoneId) {
            Log::warning('WhatsApp Cloud API: konfigurasi belum lengkap atau nomor kosong.');
            return;
        }

        // Normalisasi: 08xxx → 628xxx, hapus non-digit
        $nomor = preg_replace('/^0/', '62', preg_replace('/\D/', '', $nomorWA));

        try {
            $response = Http::withToken($token)
                ->post("https://graph.facebook.com/v19.0/{$phoneId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'recipient_type'    => 'individual',
                    'to'                => $nomor,
                    'type'              => 'text',
                    'text'              => [
                        'preview_url' => false,
                        'body'        => $pesan,
                    ],
                ]);

            if (! $response->successful()) {
                Log::warning('WhatsApp Cloud API gagal: ' . $response->body());
            }
        } catch (\Throwable $e) {
            Log::warning('WhatsApp Cloud API exception: ' . $e->getMessage());
        }
    }

    private function pesanApprove(string $nama, ?string $catatan): string
    {
        $pesan  = "Halo *{$nama}*,\n\n";
        $pesan .= "✅ *Profil Anda telah DIVERIFIKASI* oleh tim Tempatles.\n\n";
        $pesan .= "Selamat! Profil Anda kini sudah tayang dan dapat ditemukan oleh calon murid.\n";

        if ($catatan) {
            $pesan .= "\n📝 *Catatan dari admin:*\n{$catatan}\n";
        }

        $pesan .= "\nSilakan login ke aplikasi untuk mulai menerima booking.\n";
        $pesan .= "— Tim Tempatles 🎓";

        return $pesan;
    }

    private function pesanReject(string $nama, string $alasan): string
    {
        $pesan  = "Halo *{$nama}*,\n\n";
        $pesan .= "❌ *Pengajuan verifikasi Anda BELUM DISETUJUI.*\n\n";
        $pesan .= "📋 *Alasan:*\n{$alasan}\n\n";
        $pesan .= "Silakan perbaiki data Anda dan ajukan ulang melalui aplikasi.\n";
        $pesan .= "Jika ada pertanyaan, balas pesan ini.\n\n";
        $pesan .= "— Tim Tempatles 🎓";

        return $pesan;
    }
}
