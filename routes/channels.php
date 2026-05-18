<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('stories.{storyId}', function ($user, $storyId) {
    return [
        'id' => $user->id,
        'name' => $user->name,
    ];
});
