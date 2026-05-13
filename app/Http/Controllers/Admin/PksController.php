<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PksDocument;
use App\Models\TutorProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PksController extends Controller
{
    // ──────────────────────────────────────────────
    // List semua PKS
    // ──────────────────────────────────────────────

    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $pksList = PksDocument::with(['tutor', 'tutor.tutorProfile'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15);

        $stats = [
            'draft'   => PksDocument::where('status', 'draft')->count(),
            'sent'    => PksDocument::where('status', 'sent')->count(),
            'signed'  => PksDocument::where('status', 'signed')->count(),
            'expired' => PksDocument::where('status', 'expired')->count(),
        ];

        return view('admin.pks.index', compact('pksList', 'status', 'stats'));
    }

    // ──────────────────────────────────────────────
    // Form buat PKS baru
    // ──────────────────────────────────────────────

    public function create()
    {
        // Hanya tutor verified yang bisa dibuatkan PKS
        $tutors = User::whereHas('tutorProfile', fn($q) => $q->where('status', 'verified'))
            ->whereDoesntHave('pksDocuments', fn($q) => $q->whereIn('status', ['sent', 'signed']))
            ->orderBy('name')
            ->get();

        return view('admin.pks.create', compact('tutors'));
    }

    // ──────────────────────────────────────────────
    // Generate & simpan PKS baru
    // ──────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'tutor_id'        => 'required|exists:users,id',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'catatan'         => 'nullable|string|max:1000',
        ]);

        $tutor   = User::with('tutorProfile')->findOrFail($request->tutor_id);
        $profile = $tutor->tutorProfile;

        // Generate nomor PKS: PKS-YYYY-XXXXX
        $urutan   = PksDocument::whereYear('created_at', now()->year)->count() + 1;
        $nomorPks = 'PKS-' . now()->format('Y') . '-' . str_pad($urutan, 5, '0', STR_PAD_LEFT);

        // Generate PDF
        $pdf = Pdf::loadView('admin.pks.template', [
            'nomor_pks'       => $nomorPks,
            'tutor'           => $tutor,
            'profile'         => $profile,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'tanggal_cetak'   => now()->translatedFormat('d F Y'),
            'catatan'         => $request->catatan,
        ])->setPaper('a4', 'portrait');

        $fileName = 'pks/' . $nomorPks . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());

        $pks = PksDocument::create([
            'tutor_id'        => $request->tutor_id,
            'nomor_pks'       => $nomorPks,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status'          => 'draft',
            'file_path'       => $fileName,
            'catatan'         => $request->catatan,
            'generated_by'    => Auth::id(),
        ]);

        return redirect()->route('admin.pks.show', $pks)
            ->with('success', "PKS {$nomorPks} berhasil digenerate.");
    }

    // ──────────────────────────────────────────────
    // Detail PKS
    // ──────────────────────────────────────────────

    public function show(PksDocument $pks)
    {
        $pks->load('tutor.tutorProfile', 'generatedBy', 'signedBy');
        return view('admin.pks.show', compact('pks'));
    }

    // ──────────────────────────────────────────────
    // Preview / Download PDF
    // ──────────────────────────────────────────────

    public function download(PksDocument $pks)
    {
        abort_if(! $pks->file_path || ! Storage::disk('public')->exists($pks->file_path), 404);

        return response()->file(Storage::disk('public')->path($pks->file_path), [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $pks->nomor_pks . '.pdf"',
        ]);
    }

    // ──────────────────────────────────────────────
    // Kirim PKS ke tutor (status: draft → sent)
    // ──────────────────────────────────────────────

    public function send(PksDocument $pks)
    {
        abort_if($pks->status !== 'draft', 422, 'Hanya PKS berstatus draft yang bisa dikirim.');

        $pks->update(['status' => 'sent']);

        return back()->with('success', 'PKS berhasil dikirim ke tutor.');
    }

    // ──────────────────────────────────────────────
    // Admin tandatangani PKS (status: sent → signed)
    // ──────────────────────────────────────────────

    public function sign(PksDocument $pks)
    {
        abort_if(! in_array($pks->status, ['sent', 'draft']), 422, 'PKS tidak bisa ditandatangani.');

        $pks->update([
            'status'    => 'signed',
            'signed_at' => now(),
            'signed_by' => Auth::id(),
        ]);

        return back()->with('success', 'PKS berhasil ditandatangani.');
    }

    // ──────────────────────────────────────────────
    // Akhiri / terminate PKS
    // ──────────────────────────────────────────────

    public function terminate(Request $request, PksDocument $pks)
    {
        abort_if($pks->status !== 'signed', 422, 'Hanya PKS aktif yang bisa diakhiri.');

        $request->validate(['catatan' => 'nullable|string|max:500']);

        $pks->update([
            'status'  => 'terminated',
            'catatan' => $request->catatan,
        ]);

        return back()->with('success', 'PKS berhasil diakhiri.');
    }
}
