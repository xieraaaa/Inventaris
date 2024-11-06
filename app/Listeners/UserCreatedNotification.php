<?php

namespace App\Listeners;

use App\Events\UserCreated;

class UserCreatedNotification
{
    public function __construct()
    {}

    public function handle(UserCreated $event): void
    {
        $event->user->assignRole('user');
    }
}
