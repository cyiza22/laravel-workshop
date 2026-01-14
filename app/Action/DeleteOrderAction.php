<?php

namespace App\Action;
use App\Models\Order;

class DeleteOrderAction
{
    public function handle(Order $order)
    {
        return $order->delete();
    }
}