<?php

namespace Modules\Booking\Contracts;

use Modules\Payment\Contracts\PaymentMethodInterface;
use Modules\Payment\Models\Enums\PaymentMethod;
use Modules\User\Models\User;

/**
 * Interface BookingInterface
 *
 * @package Modules\Booking\Models
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

    public function getPaymentMethod(): ?PaymentMethod;

    public function setPayment(PaymentMethodInterface $payment): BookingInterface;

    public function getPayment(): ?PaymentMethodInterface;

    public function toArray(): array;
}
