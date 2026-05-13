<?php

namespace App\Notifications;

use App\Channels\FonnteChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TutorRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $tutorName,
        public readonly string $alasan,
        public readonly string $registerUrl,
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
        return (new MailMessage)
            ->subject('Informasi Verifikasi Akun Tutor — Tempatles.id')
            ->greeting('Halo, ' . $this->tutorName . '!')
            ->line('Terima kasih sudah mendaftar sebagai tutor di **Tempatles.id**.')
            ->line('Setelah kami tinjau, pendaftaran kamu **belum dapat kami setujui** saat ini.')
            ->line('**Alasan:** ' . $this->alasan)
            ->line('Kamu bisa memperbaiki data dan mendaftar ulang kapan saja.')
            ->action('Daftar Ulang', $this->registerUrl)
            ->line('Jika ada pertanyaan atau keberatan, silakan hubungi tim kami.')
            ->salutation('Salam, Tim Tempatles.id');
    }

    // ---------------------------------------------------------------
    // WHATSAPP (Fonnte)
    // ---------------------------------------------------------------
    public function toFonnte(object $notifiable): string
    {
        return
            "Halo, *{$this->tutorName}*.\n\n" .
            "Terima kasih sudah mendaftar di *Tempatles.id*.\n\n" .
            "Mohon maaf, pendaftaran tutor kamu *belum dapat disetujui* saat ini.\n\n" .
            "📋 *Alasan:* {$this->alasan}\n\n" .
            "Kamu bisa memperbaiki data dan daftar ulang melalui:\n" .
            "🔗 {$this->registerUrl}\n\n" .
            "_Tim Tempatles.id_";
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'tutor_rejected',
            'tutor_name'  => $this->tutorName,
            'alasan'      => $this->alasan,
            'register_url' => $this->registerUrl,
        ];
    }
}
