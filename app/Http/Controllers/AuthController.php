<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    public function publicLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $client = new Client;

        $response = $client->post( env('APP_URL').'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => env('CLIENT_PASSWORD_ID'),
                'client_secret' => env('CLIENT_PASSWORD_SECRET'),
                'username' => $request->username,
                'password' => $request->password,
            ]
        ]);

        return json_decode((string) $response->getBody(), true);
    }
    public function publicRefresh(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);

        $client = new Client;

        $response = $client->post( env('APP_URL').'/oauth/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'client_id' => env('CLIENT_PASSWORD_ID'),
                'client_secret' => env('CLIENT_PASSWORD_SECRET'),
                'refresh_token' => $request->token,
            ]
        ]);

        return json_decode((string) $response->getBody(), true);
    }
    public function fetchUser()
    {
        $user = auth()->user();
        return response()->json([
            'name' => $user->name, 
            'email' => $user->email,
        ]);
    }
}
