<?php

declare(strict_types=1);

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{uid}', function (User $user, $uid) {
    return $user->uid === $uid;
}, ['guards' => ['api']]);
Broadcast::channel('App.Models.Chat.{chat_uid}', function (User $user, $chat_uid) {
    $exists = Chat::where('uid', $chat_uid)
        ->where(function ($query) use ($user) {
            $query->where('user1_uid', $user->uid)
                ->orWhere('user2_uid', $user->uid);
        })->exists();

    return (bool) $exists;
}, ['guards' => ['api']]);
