<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteChannel
{
    /**
     * Kirim notifikasi via Fonnte WhatsApp API.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        // Pastikan notifiable punya nomor WA
        if (! $to = $notifiable->routeNotificationFor('fonnte', $notification)) {
            return;
        }

        // Pastikan notification punya method toFonnte()
        if (! method_exists($notification, 'toFonnte')) {
            return;
        }

        $message = $notification->toFonnte($notifiable);

        try {
            $response = Http::withHeaders([
                'Authorization' => config('fonnte.token'),
            ])->post('https://api.fonnte.com/send', [
                'target'  => $to,
                'message' => $message,
            ]);

            if (! $response->successful()) {
                Log::warning('Fonnte WA gagal terkirim', [
                    'target'   => $to,
                    'status'   => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Fonnte exception: ' . $e->getMessage());
        }
    }
}
