<?php
declare(strict_types=1);

namespace Modules\Order\Services;

use Illuminate\Support\Facades\Log;
use Modules\Order\Contracts\OrderInterface;
use Modules\Order\Contracts\OrderPlacementServiceInterface;
use Modules\Order\Contracts\PurchasableInterface;
use Modules\Order\Contracts\SequenceInterface;
use Modules\Order\Enums\OrderActionEnum;
use Modules\Order\Models\OrderTransactionManager;

/**
 * Class OrderPlacementService
 *
 * @package Modules\Order\Services
 */
class OrderPlacementService implements OrderPlacementServiceInterface
{
    /**
     * @var \Modules\Order\Models\OrderTransactionManager
     */
    private OrderTransactionManager $transactionManager;
    private SequenceInterface $sequence;

    public function __construct(
        OrderTransactionManager $transactionManager,
        SequenceInterface $sequence
    )
    {
        $this->transactionManager = $transactionManager;
        $this->sequence = $sequence;
    }

    public function place(OrderInterface $order): OrderInterface
    {
        // do payment
        try {
            $order->place();
        } catch (\Exception $e) {
            Log::error(__('Payment of an order is failed: ' . $e->getMessage()));
        }

        // save order
        try {
            $quote = $order->getQuote();
            $model = $quote->save();
            $order->purchasable()->associate($model);
            $order->save();

            $order->setIncrementId($this->sequence->getCurrentValue($order->id));
            $order->save();

            $this->transactionManager->handle($order, OrderActionEnum::ORDER_ACTION_PLACED);
        } catch (\Exception $e) {
            Log::error(__('Saving order ' . $order->id . ' failed: ' . $e->getMessage()));
//            $this->logger->critical(
//                'Saving order ' . $order->getIncrementId() . ' failed: ' . $e->getMessage()
//            );
            throw $e;
        }

        return $order;
    }

    public function cancel(OrderInterface $order): bool
    {
        // do payment refund
        try {
            $order->cancel();
        } catch (\Exception $e) {
        }

        try {
            if ($order->purchasable instanceof PurchasableInterface) {
                $order->purchasable->markAsCancelled();
            }

            $order->save();
            $this->transactionManager->handle($order, OrderActionEnum::ORDER_ACTION_CANCELLED);
        } catch (\Exception $e) {
            Log::error(__('Saving order ' . $order->id . ' failed: ' . $e->getMessage()));
//            $this->logger->critical(
//                'Saving order ' . $order->getIncrementId() . ' failed: ' . $e->getMessage()
//            );
            throw $e;
        }

        return true;
    }
}
