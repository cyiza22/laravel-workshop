<?php

namespace App\CustomData;

use Kakaprodo\CustomData\CustomData;

class CreateOrderData extends CustomData
{
    protected function expectedProperties(): array
    {
        return [
            'number' => $this->property()->string(),
            'status' => $this->property()->string(),
            'user_id' => $this->property()->int(),
        ];
    }

    public function boot()
    {
        // make validation before data is injected to action
    }
}

