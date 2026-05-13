<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberRequest;
use App\Models\TutorProfile;
use App\Notifications\TutorVerifiedNotification;
use App\Notifications\TutorRejectedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'tutor');

        $tutorsPending = TutorProfile::with('user')
            ->where('is_verified', false)
            ->whereNotNull('ktp_path')
            ->latest()
            ->paginate(10, ['*'], 'tutor_page');

        $membersPending = MemberRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->paginate(10, ['*'], 'member_page');

        $stats = [
            'tutor_pending'   => TutorProfile::where('is_verified', false)->whereNotNull('ktp_path')->count(),
            'tutor_verified'  => TutorProfile::where('is_verified', true)->count(),
            'member_pending'  => MemberRequest::where('status', 'pending')->count(),
            'member_approved' => MemberRequest::where('status', 'approved')->count(),
        ];

        return view('admin.verification.index', compact(
            'tutorsPending',
            'membersPending',
            'stats',
            'tab'
        ));
    }

    public function showTutor(TutorProfile $tutorProfile)
    {
        $tutorProfile->load('user', 'tutorSubjects.subject', 'tutorAreas');

        return view('admin.verification.tutor-detail', compact('tutorProfile'));
    }

    public function approveTutor(Request $request, TutorProfile $tutorProfile)
    {
        DB::beginTransaction();

        try {
            $tutorProfile->update([
                'is_verified'       => true,
                'verification_note' => $request->catatan ?? 'Dokumen lengkap dan valid.',
                'verified_at'       => now(),
            ]);

            $tutorProfile->user->update([
                'role' => 'tutor'
            ]);

            try {
                $tutorProfile->user->notify(
                    new TutorVerifiedNotification($tutorProfile)
                );
            } catch (\Exception $e) {
                Log::warning('Notif approve gagal: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()
                ->route('admin.verification.index')
                ->with('success', 'Tutor berhasil diverifikasi!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Approve tutor error: ' . $e->getMessage());

            return back()->with('error', 'Gagal verifikasi tutor.');
        }
    }

    public function rejectTutor(Request $request, TutorProfile $tutorProfile)
    {
        $request->validate([
            'alasan_tolak' => 'required|string|min:10',
        ]);

        DB::beginTransaction();

        try {
            $tutorProfile->update([
                'is_verified'       => false,
                'verification_note' => $request->alasan_tolak,
                'verified_at'       => null,
            ]);

            try {
                $tutorProfile->user->notify(
                    new TutorRejectedNotification($tutorProfile, $request->alasan_tolak)
                );
            } catch (\Exception $e) {
                Log::warning('Notif reject gagal: ' . $e->getMessage());
            }

            DB::commit();

            return redirect()
                ->route('admin.verification.index')
                ->with('warning', 'Tutor ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Reject tutor error: ' . $e->getMessage());

            return back()->with('error', 'Gagal menolak tutor.');
        }
    }

    public function approveMember(MemberRequest $memberRequest)
    {
        DB::beginTransaction();

        try {
            $memberRequest->update([
                'status' => 'approved'
            ]);

            $memberRequest->user->update([
                'role' => 'murid'
            ]);

            DB::commit();

            return redirect()
                ->route('admin.verification.index', ['tab' => 'member'])
                ->with('success', 'Member disetujui!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Approve member error: ' . $e->getMessage());

            return back()->with('error', 'Gagal approve member.');
        }
    }

    public function rejectMember(Request $request, MemberRequest $memberRequest)
    {
        $request->validate([
            'alasan_tolak' => 'required|string|min:5',
        ]);

        DB::beginTransaction();

        try {
            $memberRequest->update([
                'status' => 'rejected',
                'catatan_admin' => $request->alasan_tolak,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.verification.index', ['tab' => 'member'])
                ->with('warning', 'Member ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Reject member error: ' . $e->getMessage());

            return back()->with('error', 'Gagal menolak member.');
        }
    }
}
