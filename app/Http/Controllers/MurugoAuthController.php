<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RwandaBuild\MurugoAuth\Facades\MurugoAuth;

class MurugoAuthController extends Controller
{
    public function redirectToMurugo()
    {
        return MurugoAuth::redirect();
    }

    public function murugoCallback()
    { 
        $murugoUser = MurugoAuth::user();
        $user= $murugoUser->user;

        if(!$user)
        {
            $user = User::create([
                'murugo_user_id' => $murugoUser->id,
                'name' => $murugoUser->murugo_user_public_name,
            ]);
        }

        Auth::login($user);


        return redirect('/dashboard');
    }

    
    
}
