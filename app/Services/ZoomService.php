<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Zoom;
use Firebase\JWT\JWT;

class ZoomService
{
    protected function getAccessToken(): ?string
    {
        $response = Http::asForm()->withBasicAuth(
            config('services.zoom.client_id'),
            config('services.zoom.client_secret')
        )->post('https://zoom.us/oauth/token', [
            'grant_type' => 'account_credentials',
            'account_id' => config('services.zoom.account_id'),
        ]);

        if ($response->failed()) {
            return null;
        }

        return $response->json('access_token');
    }

    public function create(Schedule $schedule, string $topic, int $duration): array
    {
        $response = Zoom::createMeeting([
            'topic' => $topic,
            'type' => 2,
            'start_time' => Carbon::parse($schedule->start_time)->toIso8601String(),
            'duration' => $duration,
            'pre_schedule' => true,
//            'schedule_for' => 'denispopov2112@gmail.com',
            'timezone' => 'Europe/Warsaw',
            'password' => $data['password'] ?? '123456',
            'settings' => [
                'join_before_host' => false,
                'host_video' => true,
                'participant_video' => true,
            ],
        ]);

        if ($response['status'] && $response['data']) {
            return [
                'zoom_meeting_id' => $response['data']['id'],
                'zoom_join_url' => $response['data']['join_url'],
                'zoom_start_url' => $response['data']['start_url'],
                'passcode' => $response['data']['password'],
                'custom_link' => null,
            ];
        }

        return [];
    }

    public function createMeeting(Schedule $schedule): bool
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return false;
        }

        $userEmail = $schedule->teacher->email ?? config('mail.from.address');

        $response = Http::withToken($token)->post("https://api.zoom.us/v2/users/{$userEmail}/meetings", [
            'topic'      => 'Scheduled Lesson',
            'type'       => 2, // scheduled
            'start_time' => $schedule->starts_at->toIso8601String(),
            'duration'   => $schedule->duration ?? 60,
            'timezone'   => 'UTC',
            'settings'   => [
                'join_before_host' => false,
                'approval_type'    => 0,
                'registration_type' => 1,
            ],
        ]);

        if ($response->failed()) {
            return false;
        }

        $data = $response->json();
        $schedule->update([
            'zoom_join_url'  => $data['join_url'],
            'zoom_start_url' => $data['start_url'],
        ]);

        return true;
    }

    public function getJoinUrl(Schedule $schedule): ?string
    {
        return $schedule->zoom_join_url ?? $schedule->custom_link;
    }

    public function getStartUrl(Schedule $schedule): ?string
    {
        return $schedule->zoom_start_url ?? $schedule->custom_link;
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
