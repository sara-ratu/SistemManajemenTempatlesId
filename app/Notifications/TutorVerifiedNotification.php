<?php

namespace App\Notifications;

use App\Channels\FonnteChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TutorVerifiedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $tutorName,
        public readonly string $loginUrl,
    ) {}

    /**
     * Channel yang digunakan: email + WhatsApp Fonnte.
     */
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
            ->subject('🎉 Selamat! Akun Tutor Kamu Telah Diverifikasi — Tempatles.id')
            ->greeting('Halo, ' . $this->tutorName . '!')
            ->line('Kabar baik! Akun tutor kamu di **Tempatles.id** telah **diverifikasi** oleh tim admin kami.')
            ->line('Kamu sudah bisa masuk dan mulai menerima booking dari murid.')
            ->action('Login Sekarang', $this->loginUrl)
            ->line('Jika ada pertanyaan, hubungi kami melalui halaman kontak di website.')
            ->salutation('Salam, Tim Tempatles.id');
    }

    // ---------------------------------------------------------------
    // WHATSAPP (Fonnte)
    // ---------------------------------------------------------------
    public function toFonnte(object $notifiable): string
    {
        return
            "🎉 *Selamat, {$this->tutorName}!*\n\n" .
            "Akun tutor kamu di *Tempatles.id* telah *DIVERIFIKASI* oleh admin.\n\n" .
            "✅ Kamu sudah bisa login dan mulai menerima booking dari murid.\n\n" .
            "🔗 Login: {$this->loginUrl}\n\n" .
            "_Tim Tempatles.id_";
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'tutor_verified',
            'tutor_name' => $this->tutorName,
            'login_url'  => $this->loginUrl,
        ];
    }
}
