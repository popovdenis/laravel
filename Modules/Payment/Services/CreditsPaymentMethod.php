<?php
declare(strict_types=1);

namespace Modules\Payment\Services;

use Modules\Order\Contracts\OrderInterface;
use Modules\Payment\Contracts\PaymentMethodInterface;
use Modules\Payment\Contracts\TransactionServiceInterface;
use Modules\Payment\Exceptions\PaymentFailedException;
use Modules\Subscription\Models\ConfigProvider;

/**
 * Class CreditsPaymentMethod
 *
 * @package App\Services\Payment
 */
class CreditsPaymentMethod implements PaymentMethodInterface
{
    protected OrderInterface $order;

    public function __construct(
        protected ConfigProvider $configProvider,
        protected TransactionServiceInterface $transactionService,
    )
    {}

    public function getTitle()
    {
        return setting('payment.credits.title');
    }

    public function validate(OrderInterface $order): void
    {
        $user = $order->getQuote()->getUser();
        $requiredCredits = $this->configProvider->getGroupLessonPrice(); //TODO: add type

        if ($user->getCreditBalance() < $requiredCredits) {
            throw new PaymentFailedException(__('Not enough credits to book the lesson.'));
        }
    }

    public function authorize(OrderInterface $order): void
    {
        // Here we could log or prepare payment but not yet deduct (like authorize in Magento)
    }

    public function setOrder(OrderInterface $order): void
    {
        $this->order = $order;
    }

    public function getOrder(): OrderInterface
    {
        return $this->order;
    }

    public function place()
    {
//        $this->_eventManager->dispatch('sales_order_payment_place_start', ['payment' => $this]);
        $quote = $this->getOrder()->getQuote();
        $this->transactionService->spend($quote->getUser(), $quote->getAmount());
//        $this->_eventManager->dispatch('sales_order_payment_place_end', ['payment' => $this]);
        return $this;
    }

    public function cancel()
    {
//        $this->_eventManager->dispatch('sales_order_payment_refund_start', ['payment' => $this]);
        $student = $this->getOrder()->user;
        $this->transactionService->refund($student, $this->getOrder()->getTotalAmount());
//        $this->_eventManager->dispatch('sales_order_payment_refund_end', ['payment' => $this]);

        return $this;
    }
}
