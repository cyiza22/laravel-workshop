<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
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

        // Log::info('Murugo User:', [
        //     'id' => $murugoUser->id,
        //     'name' => $murugoUser->name,
        //     'murugo_user_public_name' => $murugoUser->murugo_user_public_name ?? null,
        // ]);

        $privateData = $this->getPrivateData(
            $murugoUser->name
        );

        // Log::info('Private Data from Murugo:', $privateData);

        if (empty($privateData['email'])) {
            return [
                'error' => 'Email is required but not provided by Murugo. Please ensure your Murugo account has an email address.',
            ];
        }

    $searchCriteria = User::with('emails')->whereHas('emails', fn($q) => $q->where('email', $privateData['email'] ?? null))->exists()
        ? ['emails.email' => $privateData['email']]
        : ['murugo_user_id' => $murugoUser->id];

    $user = User::updateOrCreate(
        $searchCriteria,
        [
            'name'  => $murugoUser->murugo_user_public_name,
            'email' => $privateData['email'] ?? null,
            'phone' => $privateData['phone'] ?? null,
        ]
    );
        

        $token = $user->createToken('auth_token')->accessToken;

        return [
            'user' => $user,
            'access_token' => $token,
        ];
    }

    
    public function getClientAccessToken()
    {
        $response = Http::asForm()->post(config('services.murugo.murugo_url') . '/oauth/token', [
            'grant_type'    => 'client_credentials',
            'client_id'     => config('services.murugo.client_id'),
            'client_secret' => config('services.murugo.client_secret'),
            'scope'         => '',
        ]);

        if (! $response->successful()) {
            throw new \Exception('Failed to get access token from Murugo');
            // throw new \Exception(json_encode([
            //     'status' => $response->status(),
            //     'body'   => $response->body(),
            //     'headers'=> $response->headers(),
            // ]));
        }

        return $response->json()['access_token'];
    }

    public function getPrivateData(String $atname)
    {
        $accessToken = $this->getClientAccessToken();

        // Log::info('Murugo Private Data Request:', [
        //     'atname' => $atname,
        //     'url' => config('services.murugo.murugo_url') . '/api/user/get-private-data',
        //     'token_prefix' => substr($accessToken, 0, 50) . '...',
        //     'app_key' => config('services.murugo.murugo_app_key'),
        // ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'APPKEY' => config('services.murugo.murugo_app_key'),
        ])->get(config('services.murugo.murugo_url') . '/api/user/get-private-data', [
            'atname' => $atname,
        ]);

        // Log::info('Murugo Private Data Response:', [
        //     'status' => $response->status(),
        //     'headers' => $response->headers(),
        //     'body' => $response->body(),
        // ]);

        if (! $response->successful()) {
            throw new \Exception('Failed to get private data from Murugo');
            // throw new \Exception(json_encode([
            //     'status' => $response->status(),
            //     'body'   => $response->body(),
            //     'headers'=> $response->headers(),
            // ]));
        }
        
        return $response->json();
        // Log::info('Parsed Private Data:', [
        //     'has_phone' => isset($data['phone']),
        //     'phone' => $data['phone'] ?? null,
        //     'has_email' => isset($data['email']),
        //     'email' => $data['email'] ?? null,
        // ]);

    }
}