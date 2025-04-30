<?php
declare(strict_types=1);

namespace Modules\Subscription\Http\Controllers;

/**
 * Class StripeWebhookController
 *
 * @package Modules\Stripe\Http\Controllers
 */

use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;
use Modules\User\Models\User;

class StripeWebhookController extends CashierWebhookController
{
    protected function handleInvoicePaid(array $payload)
    {
        $stripeInvoice = $payload['data']['object'];
        $user = User::where('stripe_id', $stripeInvoice['customer'])->first();

        if (!$user) {
            Log::warning('Stripe invoice received for unknown customer.');
            return response()->json(['message' => 'User not found'], 404);
        }

        // Создание локального ордера
        $user->orders()->create([
            'stripe_invoice_id' => $stripeInvoice['id'],
            'type' => 'subscription',
            'total' => $stripeInvoice['amount_paid'] / 100,
            'status' => 'paid',
        ]);
    }
}
