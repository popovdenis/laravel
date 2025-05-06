<?php
declare(strict_types=1);

namespace Modules\StripePayment\Models;

use Illuminate\Support\Facades\Log;
use Modules\Payment\Models\AbstractMethod;
use Modules\Subscription\Contracts\SubscriptionQuoteInterface;
use Modules\Subscription\Services\SubscriptionService;

/**
 * Class StripePayment
 *
 * @package Modules\StripePayment\Models
 */
class StripePayment extends AbstractMethod
{
    const PAYMENT_METHOD_STRIPE_CODE = 'stripe';

    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_STRIPE_CODE;

    public function getTitle()
    {
        $path = 'payment.' . $this->getCode() . '.title';

        return setting($path);
    }

    public function processAction()
    {
        /** @var SubscriptionQuoteInterface $quote */
        $quote = $this->getOrder()->getQuote();
        $user = $quote->getUser();
        $plan = $quote->getPlan();

        try {
            $transactionPrice = $quote->getTransactionPriceId();

            if ($user->subscribed()) {
                $user->subscription()->cancelNowAndInvoice(); // TODO: take in account to prolong the current subscription
                //$subscription = $user->subscription('default')->swapAndInvoice('price_1RJeW304fVTImIORrwg9xKbd')->skipTrial(); // switch now
            }

            $newSubscription = $user->newSubscription('default', $transactionPrice);
            $subscriptionOptions = [
                'collection_method' => 'send_invoice'
            ];

            if ($plan->isEnabledTrial()) {
                $newSubscription->trialUntil(now()->addDays($plan->getTrialDays()));
                $subscriptionOptions['days_until_due'] = $plan->getTrialDays();
            }

            $subscription = $newSubscription->create(null, [], $subscriptionOptions);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
        }

        /** @var SubscriptionService $subscriptionService */
        $subscriptionService = app(SubscriptionService::class);
        $plan = $subscriptionService->getSubscriptionPlan($quote->getSourceId());

        $subscription->update($subscriptionService->getUpdateUserSubscriptionOptions($plan));
        $subscriptionService->updateCreditBalance($user, $plan);

        $quote->setModel($subscription);

        return $this;
    }
}
