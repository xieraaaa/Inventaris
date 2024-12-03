<?php

namespace App\Events;

use App\Models\Peminjaman;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PeminjamanInvoked implements ShouldBroadcast
{
    public function __construct(
        public Peminjaman $peminjaman
    ) {}
    
    public function broadcastOn()
    {
        return new PrivateChannel('peminjaman.' . $this->peminjaman['id']);
    }
}