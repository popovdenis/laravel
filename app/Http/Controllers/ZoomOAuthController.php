<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ZoomOAuthController extends Controller
{
    public function handleCallback(Request $request)
    {
        $code = $request->get('code');

        if (!$code) {
            return redirect('/')->with('error', 'Authorization code not provided');
        }

        $response = Http::asForm()
            ->withHeaders([
                'Authorization' => 'Basic ' . base64_encode(config('services.zoom.client_id') . ':' . config('services.zoom.client_secret')),
            ])
            ->post('https://zoom.us/oauth/token', [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => config('services.zoom.redirect_uri'),
            ]);

        if ($response->failed()) {
            return redirect('/')->with('error', 'Failed to get access token from Zoom');
        }

        $data = $response->json();

        session([
            'zoom_access_token' => $data['access_token'],
            'zoom_refresh_token' => $data['refresh_token'],
        ]);

        return redirect('/profile')->with('success', 'Zoom connected successfully!');
    }
}
