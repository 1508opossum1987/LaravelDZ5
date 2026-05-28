<?php

use App\Models\User;

Broadcast::channel('basket.{userId}', function (User $user, int $userId) {
    return $user->id === $userId;
});
