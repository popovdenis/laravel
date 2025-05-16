<?php
declare(strict_types=1);

namespace Modules\User\Data;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Modules\User\Contracts\AccountDataInterface;
use Modules\User\Models\User;
use Spatie\GoogleTimeZone\GoogleTimeZone;
use Spatie\LaravelData\Data;

/**
 * Class CustomerData
 *
 * @package Modules\User\Data
 */
class CustomerData extends Data
{
    public function __construct(
        public string $firstname,
        public string $lastname,
        public string $email,
        public string $password,
        public string $password_confirmation,
        public string $subscriptionPlanId,
        public ?string $timeZoneId,
    )
    {
    }

    public static function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'. User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'subscription_plan_id'  => ['required', 'exists:subscription_plans,id'],
        ];
    }

    public static function fromRequest(Request $request): static
    {
        $timezone = self::getGoogleTimeZone($request);

        return static::from([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
            'subscriptionPlanId' => $request->subscription_plan_id,
            'timeZoneId' => $timezone['timeZoneId'] ?? null,
        ]);
    }

    private static function getGoogleTimeZone(Request $request): array
    {
        try {
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');

            $googleTimeZone = new GoogleTimeZone();
            $googleTimeZone->setApiKey(config('google-time-zone.key'));

            return $googleTimeZone->getTimeZoneForCoordinates($latitude, $longitude);
        } catch (\Throwable $exception) {
            report($exception);
        }

        return [];
    }
}
