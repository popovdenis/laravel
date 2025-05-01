<?php
declare(strict_types=1);

namespace Modules\Order\Factories;

use Modules\Order\Contracts\OrderFactoryInterface;
use Modules\Order\Contracts\OrderInterface;
use Modules\Order\Contracts\QuoteInterface;
use Modules\Order\Models\Order;

/**
 * Class OrderFactory
 *
 * @package Modules\Order\Factories
 */
class OrderFactory implements OrderFactoryInterface
{
    public function createFromQuote(QuoteInterface $quote): OrderInterface
    {
        $order = new Order();
        $order->setUserId($quote->getUser()->id);
        $order->setTotalAmount($quote->getAmount());
        $order->setPayment($quote->getPayment());
        $order->setQuote($quote);

        return $order;
    }
}
