<?php

namespace App\Observers;

use App\Models\order;
use App\Notifications\OrderCreatedNotification;
use App\Services\NotificationAuthService;
use App\Services\OneSignalService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OrderObserver
{
    /**
     * automatically set the slug value when an order is being created.
     */

    public function __construct(protected OneSignalService $oneSignalService)
    {}

    public function creating(Order $order): void
    {
        if (empty($order->slug)) {
            $order->slug = Str::slug(
                "order-{$order->user_id}-" . time()
            );
        }
    }

    /**
     * Handle the order "created" event.
     */
    
    public function created(Order $order): void
    {
        // $order->user->notify(new OrderCreatedNotification($order)
        // );
        $user = $order->user;

        if (!$user || !$user->onesignal_player_id) {
            return;
        }

        $this->oneSignalService->sendToUser(
            $user->onesignal_player_id,
            'Order Created',
            "Your order #{$order->id} was created"
        );
    }

    /**
     * Handle the order "updated" event.
     */
    public function delivered(NotificationAuthService $notificationAuthService, Order $order)
    {
        $order->update(['status' => 'delivered']);
        $notificationAuthService->getAccessToken($order);
        
                return response()->json([
                    'message' => 'Order completed'
                ]);
    }
    /**
     * Handle the order "deleted" event.
     */
    public function deleted(order $order): void
    {
        //
    }

    /**
     * Handle the order "restored" event.
     */
    public function restored(order $order): void
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     */
    public function forceDeleted(order $order): void
    {
        //
    }
}