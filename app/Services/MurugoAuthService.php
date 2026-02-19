<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RwandaBuild\MurugoAuth\Facades\MurugoAuth;

class MurugoAuthService
{
    public function login(array $request)
    {
       
        $tokens = [
            'access_token'  => $request['access_token'],
            'refresh_token' => $request['refresh_token'],
            'expires_in'    => $request['expires_in'],
        ];

        $murugoUser = MurugoAuth::userFromToken($tokens);

        
        $user = User::where('murugo_user_id', $murugoUser->id)->first();

        if ($user) {
            return $this->issueToken($user);
        }

        
        $privateData = $this->getPrivateData($murugoUser->name);

        $email = $privateData['email'] ?? null;
        $phone = $privateData['phone'] ?? null;

        Log::info($privateData);

        
        if ($email) {
            $user = User::where('email', $email)->first();

            if ($user) {
                $user->update([
                    'murugo_user_id' => $murugoUser->id,
                    'phone' => $user->phone ?? $phone,
                ]);

                return $this->issueToken($user);
            }
        }

        
        $user = User::create([
            'name' => $murugoUser->murugo_user_public_name,
            'email' => $email,
            'phone' => $phone,
            'murugo_user_id' => $murugoUser->id,
        ]);

        return $this->issueToken($user);
    }

   
    private function issueToken(User $user)
    {
        $token = $user->createToken('auth_token')->accessToken;

        return [
            'user' => $user,
            'access_token' => $token,
        ];
    }

    private function getClientAccessToken()
    {
        $response = Http::asForm()->post(
            config('services.murugo.murugo_url') . '/oauth/token',
            [
                'grant_type'    => 'client_credentials',
                'client_id'     => config('services.murugo.client_id'),
                'client_secret' => config('services.murugo.client_secret'),
                'scope'         => '',
            ]
        );

        if (! $response->successful()) {
            throw new \Exception('Failed to get Murugo client access token');
        }

        return $response->json()['access_token'];
    }

    private function getPrivateData(string $atname)
    {
        $accessToken = $this->getClientAccessToken();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'APPKEY'        => config('services.murugo.murugo_app_key'),
        ])->get(
            config('services.murugo.murugo_url') . '/api/user/get-private-data',
            ['atname' => $atname]
        );

        if (! $response->successful()) {
            throw new \Exception('Failed to fetch Murugo private data');
        }

        return $response->json();
    }
}
