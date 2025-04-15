<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Class CometChatService
 *
 * @package App\Services
 */
class CometChatService
{
    public string $appId;
    public string $apiKey;
    public string $region;
    public string $baseUrl;

    public function __construct()
    {
        $this->appId = config('services.cometchat.app_id');
        $this->apiKey = config('services.cometchat.api_key');
        $this->region = config('services.cometchat.region');
        $this->baseUrl = "https://{$this->appId}.api-{$this->region}.cometchat.io/v3";
    }

    public function headers(): array
    {
        return ['accept' => 'application/json', 'apikey' => $this->apiKey];
    }

    public function userExists(string $uid): bool
    {
        return Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/users/{$uid}")
            ->successful();
    }

    public function createUser(string $uid, string $name): void
    {
        if (! $this->userExists($uid)) {
            Http::withHeaders($this->headers())->post("{$this->baseUrl}/users", [
                'uid' => $uid,
                'name' => $name,
            ]);
        }
    }

    public function groupExists(string $guid): bool
    {
        return Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/groups/{$guid}")
            ->successful();
    }

    public function createGroup(string $guid, string $name): void
    {
        if (! $this->groupExists($guid)) {
            Http::withHeaders($this->headers())->post("{$this->baseUrl}/groups", [
                'guid' => $guid,
                'name' => $name,
                'type' => 'private',
            ]);
        }
    }

    public function userInGroup(string $guid, string $uid): bool
    {
        return Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/groups/{$guid}/members/{$uid}")
            ->successful();
    }

    public function addUsersToGroup(string $guid, array $admins, array $participants)
    {
        $params = [
            'admins' => $admins,
            'participants' => $participants
        ];
        Http::withHeaders($this->headers())->post("{$this->baseUrl}/groups/{$guid}/members", $params);
    }

    public function addUserToGroup(string $guid, string $uid, string $scope = 'participant'): void
    {
        if (! $this->userInGroup($guid, $uid)) {
            $params = [];
            if ($scope === 'admin') {
                $params = [
                    'admins' => [$uid],
                ];
            } elseif ($scope === 'participant') {
                $params = [
                    'participants' => [$uid]
                ];
            }
            Http::withHeaders($this->headers())->post("{$this->baseUrl}/groups/{$guid}/members", $params);
        }
    }
}
