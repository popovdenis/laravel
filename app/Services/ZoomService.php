<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Support\Facades\Http;

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
}
