<?php

namespace App\Observers;

use App\Models\order;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Support\Str;

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
    public function updated(order $order): void
    {
        //
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
