<?php
declare(strict_types=1);

namespace Modules\Payment\Models;

use Modules\EventManager\Contracts\ManagerInterface;
use Modules\Order\Contracts\OrderInterface;
use Modules\Order\Contracts\QuoteInterface;
use Modules\Order\Enums\OrderStateEnum;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Payment\Contracts\PaymentInterface;
use Modules\Payment\Contracts\RequestDataInterface;

/**
 * Class Payment
 *
 * @package Modules\Payment\Models
 */
class Payment extends Info implements PaymentInterface
{
    public ?string $method;
    public ?QuoteInterface $quote = null;
    public ?OrderInterface $order = null;

    /**
     * @var \Modules\EventManager\Contracts\ManagerInterface
     */
    private ManagerInterface $eventManager;

    public function __construct(ManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        return $this->method = $method;
    }

    /**
     * Declare quote model instance
     *
     * @param QuoteInterface $quote
     *
     * @return $this
     */
    public function setQuote(QuoteInterface $quote)
    {
        $this->quote = $quote;

        return $this;
    }

    /**
     * Retrieve quote model instance.
     *
     * @return QuoteInterface
     */
    public function getQuote()
    {
        return $this->quote;
    }

    /**
     * Declare order model object
     *
     * @param \Modules\Order\Contracts\OrderInterface $order
     * @return $this
     */
    public function setOrder(\Modules\Order\Contracts\OrderInterface $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Retrieve order model object
     *
     * @return \Modules\Order\Contracts\OrderInterface
     */
    public function getOrder()
    {
        return $this->order;
    }

    public function importData(RequestDataInterface $requestData)
    {
        $this->setMethod($requestData->method);
//        $method = $this->getMethodInstance();
//        $quote = $this->getQuote();
    }

    /**
     * Retrieve payment method model object
     *
     * @return \Modules\Payment\Contracts\MethodInterface|null
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getMethodInstance()
    {
        return parent::getMethodInstance();
    }

    public function place()
    {
        $this->eventManager->dispatch('sales_order_payment_place_start', ['payment' => $this]);
        $order = $this->getOrder();
        dd($order);
        $methodInstance = $this->getMethodInstance();
        $methodInstance->setOrder($order);
        $methodInstance->processAction();

        $orderState = OrderStateEnum::ORDER_STATE_NEW;
        $orderStatus = OrderStatusEnum::ORDER_STATUS_PENDING;

        if (!$order->getState()) {
            $order->setState($orderState);
        }
        if (!$order->getStatus()) {
            $order->setStatus($orderStatus);
        }

        $this->updateOrder($order, $orderState, $orderStatus);

        $this->eventManager->dispatch('sales_order_payment_place_end', ['payment' => $this]);

        return $this;
    }

    public function cancel()
    {
        $order = $this->getOrder();
        $methodInstance = $this->getMethodInstance();
        $methodInstance->setOrder($order);

        $methodInstance->cancel();

        $order->setState(OrderStateEnum::ORDER_STATE_CANCELLED);
        $order->setStatus(OrderStatusEnum::ORDER_STATUS_CANCELLED);

        return $this;
    }

    /**
     * Set appropriate state to order or add status to order history
     *
     * @param OrderInterface $order
     * @param string $orderState
     * @param string $orderStatus
     *
     * @return void
     */
    protected function updateOrder(OrderInterface $order, $orderState, $orderStatus)
    {

    }
}
