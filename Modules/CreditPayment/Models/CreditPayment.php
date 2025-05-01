<?php
declare(strict_types=1);

namespace Modules\CreditPayment\Models;

use Modules\Order\Contracts\OrderInterface;
use Modules\Payment\Contracts\TransactionServiceInterface;
use Modules\Payment\Exceptions\PaymentFailedException;
use Modules\Payment\Models\AbstractMethod;
use Modules\Subscription\Models\ConfigProvider;

/**
 * Class Credit
 *
 * @package Modules\CreditPayment\Models
 */
class CreditPayment extends AbstractMethod
{
    const PAYMENT_METHOD_CREDITS_CODE = 'credits';

    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_CREDITS_CODE;

    public function __construct(
        protected ConfigProvider $configProvider,
        protected TransactionServiceInterface $transactionService,
    )
    {}

    public function getTitle()
    {
        $path = 'payment.' . $this->getCode() . '.title';

        return setting($path);
    }

    public function validate(OrderInterface $order): void
    {
        $user = $order->getQuote()->getUser();
        $requiredCredits = $this->configProvider->getGroupLessonPrice(); //TODO: add type

        if ($user->getCreditBalance() < $requiredCredits) {
            throw new PaymentFailedException(__('Not enough credits to book the lesson.'));
        }
    }

    public function processAction()
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
