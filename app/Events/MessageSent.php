<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ChatMessage $message)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->message->chat_room_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id'          => $this->message->id,
            'chat_room_id'=> $this->message->chat_room_id,
            'sender_id'   => $this->message->sender_id,
            'sender_name' => $this->message->sender->name,
            'tipe'        => $this->message->tipe,
            'isi'         => $this->message->isi,
            'file_path'   => $this->message->file_path,
            'file_url'    => $this->message->fileUrl(),
            'created_at'  => $this->message->created_at->toISOString(),
        ];
    }
}
