<?php

use App\Models\User;

Broadcast::channel('basket.{userId}', function (User $user, int $userId) {
    return $user->id === $userId;
});

Broadcast::channel('admin.notifications', function (User $user) {
    return $user->isAdmin();
});
