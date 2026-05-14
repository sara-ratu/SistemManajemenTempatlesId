<?php

namespace App\Notifications;

use App\Channels\FonnteChannel;
use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param  Booking  $booking
     * @param  string   $recipient  'tutor' | 'Member'
     */
    public function __construct(
        public readonly Booking $booking,
        public readonly string $recipient = 'tutor',
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', FonnteChannel::class];
    }

    // ---------------------------------------------------------------
    // EMAIL
    // ---------------------------------------------------------------
    public function toMail(object $notifiable): MailMessage
    {
        $booking  = $this->booking;
        $isTutor  = $this->recipient === 'tutor';

        $subject  = $isTutor
            ? '📚 Booking Baru Masuk — Tempatles.id'
            : '✅ Booking Kamu Berhasil — Tempatles.id';

        $greeting = $isTutor
            ? 'Halo, ' . $booking->tutor->name . '!'
            : 'Halo, ' . $booking->Member->name . '!';

        $intro = $isTutor
            ? 'Ada booking baru masuk untuk kamu dari **' . $booking->Member->name . '**.'
            : 'Booking kamu dengan tutor **' . $booking->tutor->name . '** berhasil dibuat.';

        $detailUrl = $isTutor
            ? route('tutor.booking.show', $booking->id)
            : route('Member.booking.show', $booking->id);

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($intro)
            ->line('**Mata Pelajaran:** ' . ($booking->subject->name ?? '-'))
            ->line('**Tanggal:** ' . $booking->tanggal?->format('d M Y'))
            ->line('**Jam:** ' . $booking->jam_mulai . ' – ' . $booking->jam_selesai)
            ->line('**Lokasi:** ' . $booking->lokasi)
            ->action('Lihat Detail Booking', $detailUrl)
            ->salutation('Salam, Tim Tempatles.id');
    }

    // ---------------------------------------------------------------
    // WHATSAPP (Fonnte)
    // ---------------------------------------------------------------
    public function toFonnte(object $notifiable): string
    {
        $booking = $this->booking;
        $isTutor = $this->recipient === 'tutor';

        if ($isTutor) {
            return
                "📚 *Booking Baru!*\n\n" .
                "Halo *{$booking->tutor->name}*, ada booking baru dari *{$booking->Member->name}*.\n\n" .
                "📖 Mapel  : {$booking->subject->name}\n" .
                "📅 Tanggal: {$booking->tanggal?->format('d M Y')}\n" .
                "🕐 Jam    : {$booking->jam_mulai} – {$booking->jam_selesai}\n" .
                "📍 Lokasi : {$booking->lokasi}\n\n" .
                "Segera cek dan konfirmasi di aplikasi Tempatles.id.\n\n" .
                "_Tim Tempatles.id_";
        }

        return
            "✅ *Booking Berhasil!*\n\n" .
            "Halo *{$booking->Member->name}*, booking kamu sudah masuk.\n\n" .
            "👨‍🏫 Tutor  : {$booking->tutor->name}\n" .
            "📖 Mapel  : {$booking->subject->name}\n" .
            "📅 Tanggal: {$booking->tanggal?->format('d M Y')}\n" .
            "🕐 Jam    : {$booking->jam_mulai} – {$booking->jam_selesai}\n" .
            "📍 Lokasi : {$booking->lokasi}\n\n" .
            "Tunggu konfirmasi dari tutor ya!\n\n" .
            "_Tim Tempatles.id_";
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'booking_' . $this->recipient,
            'booking_id' => $this->booking->id,
            'subject'    => $this->booking->subject->name ?? '-',
            'tanggal'    => $this->booking->tanggal?->format('d M Y'),
        ];
    }
}
