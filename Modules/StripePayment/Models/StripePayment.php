<?php
declare(strict_types=1);

namespace Modules\StripePayment\Models;

use Modules\Payment\Models\AbstractMethod;
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
        $quote = $this->getOrder()->getQuote();
        $user = $quote->getUser();

        $paymentMethod = 'pm_card_visa'; // тестовый метод Stripe (подставной)
        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($paymentMethod);
dd($paymentMethod);
        // TODO: implement swap
        if ($user->subscribed('default')) {
//            $user->subscription('default')->swap('price_id_for_pro'); // keep the current plan till it ends
            $user->subscription('default')->cancelNowAndInvoice();
            $subscription = $user->newSubscription('default', 'price_1RJIH504fVTImIORseJmgDpt')->create($paymentMethod);
//            $subscription = $user->newSubscription('default', 'price_1RJeW304fVTImIORrwg9xKbd')->create($paymentMethod);
//            $subscription = $user->subscription('default')->swapAndInvoice('price_1RJeW304fVTImIORrwg9xKbd')->skipTrial(); // switch now
        } else {
            $subscription = $user->newSubscription('default', 'price_1RJIH504fVTImIORseJmgDpt')->create($paymentMethod);
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
