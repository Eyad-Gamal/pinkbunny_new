<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

/**
 * Private admin orders channel.
 * Only users with the "admin" role (via Spatie Permission) may subscribe.
 */
Broadcast::channel('admin.orders', function ($user) {
    $authorized = $user->hasRole('admin');

    \Illuminate\Support\Facades\Log::info('[Channel] admin.orders auth check', [
        'user_id'    => $user->id,
        'authorized' => $authorized,
    ]);

    return $authorized;
});
