<?php
declare(strict_types=1);

namespace Modules\StripePayment\Models;

use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Modules\Payment\Models\AbstractMethod;
use Modules\Subscription\Contracts\SubscriptionQuoteInterface;
use Modules\Subscription\Exceptions\SubscriptionValidationException;
use Stripe\Exception\InvalidRequestException;

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

    /**
     * @throws IncompletePayment
     */
    public function processAction()
    {
        /** @var SubscriptionQuoteInterface $quote */
        $quote = $this->getOrder()->getQuote();
        $student = $quote->getStudent();
        $plan = $quote->getPlan();
        $activeSubscription = $student->getActiveSubscription();

        if ($activeSubscription && !$student->hasDefaultPaymentMethod()) {
            throw new SubscriptionValidationException('You don’t have an active payment method.');
        }

        try {
            if ($activeSubscription && $activeSubscription->valid()) {
                if ($activeSubscription->onTrial()) {
                    try {
                        $activeSubscription->cancelNow();
                    } catch (InvalidRequestException $exception) {
                        Log::error($exception->getMessage());
                        Log::error($exception->getTraceAsString());

                        $activeSubscription->delete();
                    }
                } else {
                    $activeSubscription->cancelNowAndInvoice();
                }
            }

            $newSubscription = $student->newSubscription('default', $quote->getTransactionPriceId());

            $subscriptionOptions = [];
            if ($plan->isEnabledTrial()) {
                $newSubscription->trialUntil(now()->addDays($plan->getTrialDays()));
            }

            $subscription = $newSubscription->create($student->defaultPaymentMethod()?->id, [], $subscriptionOptions);

            $quote->setModel($subscription);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw $exception;
        }

        return $this;
    }
}
