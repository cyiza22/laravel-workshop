<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\MurugoAuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\MurugoRequest;
use RwandaBuild\MurugoAuth\Facades\MurugoAuth;

class MurugoAuthController extends Controller
{

    public function __construct(protected MurugoAuthService $murugoAuthService)
    {}
    
    public function loginWithMurugo(MurugoRequest $request)
    {
        $response = $this->murugoAuthService->login($request->validated());

        if(isset($response['redirect']))
        {
            return redirect($response['redirect']);
        }

        return response()->json($response, 200);
    }
}
