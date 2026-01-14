<?php

namespace App\Action;

use Kakaprodo\CustomData\Helpers\CustomActionBuilder;
use App\CustomData\CreateOrderData;
use App\Models\Order;

class CreateOrderAction extends CustomActionBuilder
{
    /**
     * The method that is going to process the logic
     */
    public function handle(CreateOrderData $data)
    {
        return Order::create([
            'number' => $data->number,
            'status' => $data->status,
            'user_id' => $data->user_id,
        ]);
    }
}
