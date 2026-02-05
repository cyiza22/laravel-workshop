<?php

namespace App\CustomData;

use Kakaprodo\CustomData\CustomData;

class CreateUserData extends CustomData
{
    protected function expectedProperties(): array
    {
        return [
            'murugo_user_id' => $data->murugo_user_id,
            'name' => $data->name,
        ];
    }

    public function boot()
    {
        // make validation before data is injected to action
    }
}
