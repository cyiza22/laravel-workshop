<?php

namespace App\Action;

use App\Models\User;
use App\CustomData\CreateUserData;
use Kakaprodo\CustomData\Helpers\CustomActionBuilder;

class CreateUserAction extends CustomActionBuilder
{
    /**
     * The method that is going to process the logic
     */
    public function handle(CreateUserData $data)
    {
        return User::create($data);
    }
}
