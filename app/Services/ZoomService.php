<?php
declare(strict_types=1);

namespace App\Services;

use App\Data\MeetingData;
use Illuminate\Support\Facades\Http;
use Zoom;
use Firebase\JWT\JWT;

class ZoomService
{
    protected function getAccessToken(): ?string
    {
        $response = Http::withHeaders([
            'Authorization' => sprintf('Basic %', config('services.zoom.basic_token')),
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->asForm()->post('https://zoom.us/oauth/token', [
            'grant_type' => 'account_credentials',
            'account_id' => config('services.zoom.account_id'),
        ]);

        if ($response->failed()) {
            logger()->error('Zoom token error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        }

        return $response->json('access_token');
    }

    public function create(MeetingData $data): ?array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        $response = Http::withToken($token)
            ->acceptJson()
            ->post("https://api.zoom.us/v2/users/denispopov2112@gmail.com/meetings", [
                'topic' => $data->topic,
                'type' => 2,
                'start_time' => $data->startTime->toIso8601String(),
                'duration' => $data->duration,
                'timezone' => 'Europe/Warsaw',
                'settings' => [
                    'join_before_host' => false,
                    'host_video' => true,
                    'participant_video' => true,
                ],
            ]);

        if ($response->failed()) {
            logger()->error('Zoom meeting create failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        }

        $res = $response->json();

        return [
            'zoom_meeting_id' => $res['id'],
            'zoom_join_url' => $res['join_url'] ?? null,
            'zoom_start_url' => $res['start_url'] ?? null,
            'passcode' => $res['password'] ?? null,
            'custom_link' => null,
        ];
    }

    public static function generateSignature(string $sdkKey, string $sdkSecret, string|int $meetingNumber, int $role = 0)
    {
        $issuedAt = time();
        $expire = $issuedAt + 60 * 60; // 1 hour

        $payload = [
            'sdkKey' => $sdkKey,
            'mn' => $meetingNumber,
            'role' => $role,
            'iat' => $issuedAt,
            'exp' => $expire,
            'appKey' => $sdkKey,
            'tokenExp' => $expire,
        ];

        return JWT::encode($payload, $sdkSecret, 'HS256');
    }
}
