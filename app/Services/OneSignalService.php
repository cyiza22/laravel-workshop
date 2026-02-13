<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OneSignalService
{
    public function sendToUser($playerId, $title, $message)
    {
        if (!$playerId) {
            return;
        }

        Http::withHeaders([
            'Authorization' => 'Basic ' . config('oneSignal.rest_api_key'),
            'app_id' => config('oneSignal.app_id'),
            'Content-Type' => 'application/json',
        ])->post('https://api.onesignal.com/notifications?c=push', [
            'player_ids' => [$playerId],
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
        ]);
    }
}
