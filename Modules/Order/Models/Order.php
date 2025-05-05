<?php
declare(strict_types=1);

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Booking\Contracts\BookingQuoteInterface;
use Modules\Booking\Models\Booking;
use Modules\EventManager\Contracts\ManagerInterface;
use Modules\Order\Contracts\OrderInterface;
use Modules\Order\Contracts\PurchasableInterface;
use Modules\Order\Contracts\QuoteInterface;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Payment\Contracts\PaymentInterface;
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
    protected ?PaymentInterface $method = null;
    /**
     * @var \Modules\EventManager\Contracts\ManagerInterface
     */
    private static ?ManagerInterface $eventManager = null;

    public function getEventManager()
    {
        if (self::$eventManager === null) {
            self::$eventManager = app(ManagerInterface::class);
        }

        return self::$eventManager;
    }

    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(\Modules\Invoice\Models\Invoice::class);
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

    public function setPayment(PaymentInterface $payment)
    {
        $this->method = $payment;
        $payment->setOrder($this);

        return $payment;
    }

    public function getPayment(): PaymentInterface
    {
        return $this->method;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function place(): void
    {
        $this->getEventManager()->dispatch('sales_order_place_before', ['order' => $this]);
        $this->getPayment()->place();
        $this->getEventManager()->dispatch('sales_order_place_after', ['order' => $this]);
    }

    public function cancel(): void
    {
        $this->getEventManager()->dispatch('sales_order_cancel_before', ['order' => $this]);
        $this->getPayment()->cancel();
        $this->getEventManager()->dispatch('sales_order_cancel_after', ['order' => $this]);
    }

    public function getFormattedPrice($amount, $currency = 'usd'): bool|string
    {
        $formatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($amount, strtoupper($currency));
    }
}
