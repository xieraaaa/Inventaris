<?php

namespace App\Events;

use App\Models\Peminjaman;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewApprovedPeminjaman implements ShouldBroadcast
{
    use SerializesModels, InteractsWithSockets, Dispatchable;

    public function __construct(
        public Peminjaman $peminjaman,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin'),
        ];
    }
}
