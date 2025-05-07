<?php
declare(strict_types=1);

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\EventManager\Contracts\ManagerInterface;
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
        protected ManagerInterface $eventManager
    )
    {
    }

    public function place(QuoteInterface $quote): OrderInterface
    {
        $this->quoteValidator->validateBeforeSubmit($quote);

        $order = $this->orderFactory->createFromQuote($quote);
        $order->setPayment($quote->getPayment());

        $this->eventManager->dispatch(
            'sales_model_service_quote_submit_before',
            ['order' => $order, 'quote' => $quote]
        );

        $order = $this->placementService->place($order);

        $this->eventManager->dispatch(
            'sales_model_service_quote_submit_success',
            ['order' => $order, 'quote' => $quote]
        );

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
