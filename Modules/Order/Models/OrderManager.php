<?php
declare(strict_types=1);

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Order\Contracts\OrderFactoryInterface;
use Modules\Order\Contracts\OrderInterface;
use Modules\Order\Contracts\OrderManagerInterface;
use Modules\Order\Contracts\OrderPlacementServiceInterface;
use Modules\Order\Contracts\QuoteInterface;

/**
 * Class OrderManager
 *
 * @package Modules\Order\Models
 */
class OrderManager implements OrderManagerInterface
{
    public function __construct(
        protected OrderPlacementServiceInterface $placementService,
        protected OrderFactoryInterface $orderFactory,
        protected QuoteValidator $quoteValidator,
    )
    {
    }

    public function place(QuoteInterface $quote): OrderInterface
    {
        $this->quoteValidator->validateBeforeSubmit($quote);

        $order = $this->orderFactory->createFromQuote($quote);
        $order->setPayment($quote->getPayment());
        dd($quote, $order);
        $order = $this->placementService->place($order);

        return $order;
    }

    public function cancel(OrderInterface $order): bool
    {
        return $this->placementService->cancel($order);
    }

    public function findOrderByEntity(Model $model): OrderInterface
    {
        return Order::where('purchasable_id', $model->id)
            ->where('purchasable_type', $model->getMorphClass())
            ->first();
    }
}
