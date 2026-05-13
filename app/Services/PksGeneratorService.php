<?php

namespace App\Services;

use App\Models\PksDocument;
use App\Models\TutorProfile;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PksGeneratorService
{
    /**
     * Generate nomor surat PKS otomatis
     * Format: 001/PKS/TL/2026
     */
    public function generateNomorSurat(): string
    {
        $year = Carbon::now()->year;

        // Ambil nomor urut terakhir untuk tahun ini
        $lastDoc = PksDocument::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastDoc && $lastDoc->nomor_surat) {
            // Extract nomor urut dari format 001/PKS/TL/2026
            $parts = explode('/', $lastDoc->nomor_surat);
            $lastNumber = (int) $parts[0];
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('%03d/PKS/TL/%d', $nextNumber, $year);
    }

    /**
     * Buat PKS Document baru dan generate PDF-nya
     */
    public function createPks(User $tutor, array $data = []): PksDocument
    {
        $tutorProfile = $tutor->tutorProfile;

        $nomorSurat = $this->generateNomorSurat();
        $tanggalMulai = $data['tanggal_mulai'] ?? Carbon::now();
        $tanggalSelesai = $data['tanggal_selesai'] ?? Carbon::now()->addYear();

        // Simpan data PKS ke database
        $pks = PksDocument::create([
            'tutor_id'       => $tutor->id,
            'nomor_surat'    => $nomorSurat,
            'tanggal_mulai'  => $tanggalMulai,
            'tanggal_selesai'=> $tanggalSelesai,
            'status'         => 'draft',
            'keterangan'     => $data['keterangan'] ?? null,
        ]);

        // Generate PDF
        $pdfPath = $this->generatePdf($pks, $tutor, $tutorProfile);
        $pks->update(['file_path' => $pdfPath]);

        return $pks->fresh();
    }

    /**
     * Generate PDF kontrak PKS menggunakan DomPDF
     */
    public function generatePdf(PksDocument $pks, User $tutor, ?TutorProfile $tutorProfile): string
    {
        $data = [
            'pks'          => $pks,
            'tutor'        => $tutor,
            'tutorProfile' => $tutorProfile,
            'tanggal_cetak'=> Carbon::now()->translatedFormat('d F Y'),
        ];

        $pdf = Pdf::loadView('pdf.pks-template', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'    => 'DejaVu Sans',
                'isRemoteEnabled'=> true,
            ]);

        $fileName  = 'pks/' . $pks->nomor_surat_slug . '_' . $tutor->id . '.pdf';
        $pdfOutput = $pdf->output();

        Storage::disk('public')->put($fileName, $pdfOutput);

        return $fileName;
    }

    /**
     * Re-generate PDF (misal setelah data tutor diupdate)
     */
    public function regeneratePdf(PksDocument $pks): string
    {
        $tutor        = $pks->tutor;
        $tutorProfile = $tutor->tutorProfile;

        return $this->generatePdf($pks, $tutor, $tutorProfile);
    }

    /**
     * Tandai PKS sebagai aktif (setelah ditandatangani)
     */
    public function aktivasiPks(PksDocument $pks): PksDocument
    {
        $pks->update([
            'status'           => 'aktif',
            'tanggal_aktivasi' => Carbon::now(),
        ]);

        return $pks->fresh();
    }

    /**
     * Terminate/nonaktifkan PKS
     */
    public function terminasiPks(PksDocument $pks, string $alasan = ''): PksDocument
    {
        $pks->update([
            'status'    => 'nonaktif',
            'keterangan'=> $alasan,
        ]);

        return $pks->fresh();
    }

    /**
     * Cek apakah tutor sudah punya PKS aktif
     */
    public function hasActivePks(User $tutor): bool
    {
        return PksDocument::where('tutor_id', $tutor->id)
            ->where('status', 'aktif')
            ->where('tanggal_selesai', '>=', Carbon::now())
            ->exists();
    }

    /**
     * Ambil PKS aktif tutor
     */
    public function getActivePks(User $tutor): ?PksDocument
    {
        return PksDocument::where('tutor_id', $tutor->id)
            ->where('status', 'aktif')
            ->where('tanggal_selesai', '>=', Carbon::now())
            ->latest()
            ->first();
    }
}
