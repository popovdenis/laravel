<?php
declare(strict_types=1);

namespace Modules\Subscription\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Modules\User\Models\User;

/**
 * Class StripeWebhookController
 *
 * @package Modules\Stripe\Http\Controllers
 */
class StripeWebhookController extends CashierWebhookController
{
    public function handle(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        $eventType = $payload['type'] ?? null;

        $intent = $payload['data']['object'];

        if ($eventType === 'checkout.session.completed') {
            $userId = $intent['client_reference_id'] ?? null;

            if ($userId) {
                Log::warning('User ID FOUND: '. $userId);
                $user = User::find($userId);

                if ($user) {
                    Log::warning('User FOUND: '. var_export($user, true));
                    // активируем подписку / даём доступ / сохраняем заказ и т.п.
                }
                Log::warning('Additional data: '. var_export($intent,true));
            }
        }

        if ($eventType === 'payment_intent.succeeded') {
            Log::info('Stripe Payment Success', [
                'payment_intent' => $intent['id'],
                'amount' => $intent['amount'],
                'currency' => $intent['currency'],
                // здесь можно найти customer и другие данные
                // https://english-academy.space/api/v1/stripe/webhook
            ]);
            Log::warning('Stripe data: '. var_export($intent,true));
            // Тут — логика: активируй подписку, начисли кредиты и т.д.
        }

        return $this->missingMethod($payload);
    }
}
