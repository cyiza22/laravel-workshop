<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class NotificationAuthService
{
    public function getAccessToken(Order $order)
    {
       
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-SERVICE-KEY' => config('services.notification.client_secret'),
        ])
        ->post(
            config('services.notification.base_url') . '/api/orders/delivered',
            [
                // 'grant_type'    => 'client_credentials',
                // 'client_id'     => config('services.notification.client_id'),
                // 'client_secret' => config('services.notification.client_secret'),
                // 'scope'         => '',
                
            
                'order_id' =>$order->id,
                'order_number' => $order->number,
                'user_email'   => $order->user->email,
                'user_name'    => $order->user->name,
            
            ]
        );
        Log::info($response->body());

        if (! $response->successful()) {
            throw new \Exception('Failed to get notification service token');
        }

        return $response;
    }
}
