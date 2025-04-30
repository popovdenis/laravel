<?php
declare(strict_types=1);

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Booking\Contracts\BookingQuoteInterface;
use Modules\Booking\Models\Booking;
use Modules\Order\Contracts\OrderInterface;
use Modules\Order\Contracts\PurchasableInterface;
use Modules\Order\Contracts\QuoteInterface;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Payment\Contracts\PaymentMethodInterface;
use Modules\Payment\Models\Payment;
use Modules\Payment\Services\PaymentMethodResolver;
use Modules\Subscription\Models\Subscription;
use Modules\User\Models\User;

/**
 * Class Order
 *
 * @package Modules\Order\Models
 */
class Order extends Model implements OrderInterface
{
    protected $fillable = [
        'purchasable_type',
        'purchasable_id',
        'status',
        'total_amount',
    ];

    protected $casts = [
        'total_amount' => 'integer',
        'status' => OrderStatusEnum::class,
    ];

    protected QuoteInterface $quote;
    protected ?PaymentMethodInterface $paymentMethod = null;

    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setUserId(int $userId): void
    {
        $this->user_id = $userId;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setTotalAmount(int $totalAmount): void
    {
        $this->total_amount = $totalAmount;
    }

    public function getTotalAmount()
    {
        return $this->total_amount;
    }

    public function source()
    {
        return $this->morphTo();
    }

    public function setQuote(QuoteInterface $quote): void
    {
        $this->quote = $quote;
    }

    public function getQuote(): QuoteInterface
    {
        return $this->quote;
    }

    public function setPayment(PaymentMethodInterface $method): void
    {
        $this->paymentMethod = $method;
    }

    public function getPayment(): PaymentMethodInterface
    {
        if ($this->paymentMethod === null) {
            /** @var PaymentMethodResolver $paymentMethodResolver */
            $paymentMethodResolver = app(PaymentMethodResolver::class);

            if ($this->id && $this->purchasable instanceof PurchasableInterface) {
                $payment = $paymentMethodResolver->resolve($this->purchasable->getPaymentMethod(), $this);
                $this->setPayment($payment);
            } else {
                if ($paymentMethod = setting('subscription.applicable_payment_method')) {
                    $payment = $paymentMethodResolver->resolve($paymentMethod, $this);
                    $this->setPayment($payment);
                }
            }
        }

        return $this->paymentMethod;
    }

    public function place(): void
    {
        // $this->_eventManager->dispatch('sales_order_place_before', ['order' => $this]);
        $this->getPayment()->place();
        // $this->_eventManager->dispatch('sales_order_place_after', ['order' => $this]);
    }

    public function cancel(): void
    {
        // $this->_eventManager->dispatch('sales_order_cancel_before', ['order' => $this]);
        $this->getPayment()->cancel();
        // $this->_eventManager->dispatch('sales_order_cancel_after', ['order' => $this]);
    }
}
