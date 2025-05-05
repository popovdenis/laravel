<?php
declare(strict_types=1);

namespace Modules\StripeCard\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\User\Models\User;

/**
 * Class StripeWebhookController
 *
 * @package Modules\StripeCard\Http\Controllers
 */
class StripeWebhookController extends \Laravel\Cashier\Http\Controllers\WebhookController
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info("SUCCESS HANDLE STRIPE WEBKOOK");
        if ($payload['type'] === 'checkout.session.completed') {
            $session = $payload['data']['object'];

            Log::info("Object data: " . var_export($session, true));

            $stripeCustomerId = $session['customer'];

            $user = User::where('stripe_id', $stripeCustomerId)->first();

            if ($user) {
                // Выполняем нужную логику
                $user->update(['subscription_active' => true]); // пример
                Log::info("Payment successful for user #{$user->id}");
            }
        }

        return response()->json(['status' => 'ok']);
    }
}

