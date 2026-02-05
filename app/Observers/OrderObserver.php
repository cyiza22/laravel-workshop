<?php

namespace App\Observers;

use App\Models\order;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class OrderObserver
{
    /**
     * automatically set the slug value when an order is being created.
     */

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
    public function created(order $order): void
    {
        $order->user->notify(new OrderCreatedNotification($order)
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
