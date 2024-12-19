<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('admin', function(User $user) {
    return "No way!";
});
