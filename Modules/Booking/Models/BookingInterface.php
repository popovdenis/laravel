<?php

namespace Modules\Booking\Models;

use App\Enums\PaymentMethod;
use Modules\Payment\Services\PaymentMethodInterface;
use Modules\User\Models\User;

/**
 * Interface BookingInterface
 *
 * @package App\Models\Booking
 */
interface BookingInterface
{
    public function setStreamId(int $streamId): BookingInterface;

    public function getStreamId(): int;

    public function setSlotId(int $slotId): BookingInterface;

    public function getSlotId(): int;

    public function setStudent(User $student): BookingInterface;

    public function getStudent(): User;

    public function setPaymentMethod(PaymentMethod $paymentMethod): BookingInterface;

    public function setPayment(PaymentMethodInterface $payment): BookingInterface;

    public function getPayment(): PaymentMethodInterface;

    public function toArray(): array;
}
