<?php
declare(strict_types=1);

namespace Modules\Payment\Services;

use Modules\Order\Contracts\OrderInterface;
use Modules\Payment\Contracts\PaymentMethodInterface;
use Modules\Payment\Enums\PaymentMethod;
use Modules\Payment\Models\Payment;
use Modules\User\Models\User;

/**
 * Class StripePaymentMethod
 *
 * @package App\Services\Payment
 */
class StripePaymentMethod implements PaymentMethodInterface
{
    protected OrderInterface $order;

    public function validate(OrderInterface $order): void
    {
        // TODO: Implement validate() method.
    }

    public function authorize(OrderInterface $order): void
    {
        // TODO: Implement authorize() method.
    }

    public function place()
    {
//        $user = $this->getOrder()->user;
//
//        $paymentMethod = 'pm_card_visa'; // тестовый метод Stripe (подставной)
//        $user->createOrGetStripeCustomer();
//        $user->updateDefaultPaymentMethod($paymentMethod);
//
//        $user->newSubscription('default', 'price_1RJIH504fVTImIORseJmgDpt')->create($paymentMethod);
    }

    public function cancel()
    {
    }

    public function getTitle()
    {
        return setting('payment.stripe.title');
    }

    public function pay(User $user, int $amount, string $description): Payment
    {
        // $this->_eventManager->dispatch('sales_order_payment_place_start', ['payment' => $this]);
//        $this->bookingCreditHistory->spend($user, $amount);

        $payment = Payment::create([
            'user_id' => $user->id,
            'method' => PaymentMethod::STRIPE->value,
            'amount' => $amount,
            'status' => 'paid'
        ]);

        //$this->_eventManager->dispatch('sales_order_payment_place_end', ['payment' => $payment]);

        return $payment;
    }

    public function setOrder(OrderInterface $order): void
    {
        $this->order = $order;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }
}
