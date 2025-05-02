<?php
declare(strict_types=1);

namespace Modules\Order\Services;

use Illuminate\Support\Facades\Log;
use Modules\Order\Contracts\OrderInterface;
use Modules\Order\Contracts\OrderPlacementServiceInterface;
use Modules\Order\Contracts\PurchasableInterface;
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

    public function __construct(OrderTransactionManager $transactionManager)
    {
        $this->transactionManager = $transactionManager;
    }

    public function getStatus($id)
    {
        // TODO: Implement getStatus() method.
    }

    public function place(OrderInterface $order): OrderInterface
    {
        // do payment
        try {
            $order->place();
        } catch (\Exception $e) {
            Log::error(__('Payment of an order is failed: ' . $e->getMessage()));
        }
dd('ok');
        // save order
        try {
            $model = $order->getQuote()->save();
            $order->purchasable()->associate($model);
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
