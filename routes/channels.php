<?php

use App\Models\{
    User,
    Peminjaman
};
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('peminjaman.{data}', function(User $user, Peminjaman $data) {
    return $data['id_user'] == $user['id'];
});
