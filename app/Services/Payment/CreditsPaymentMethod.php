<?php
declare(strict_types=1);

namespace App\Services\Payment;

use Modules\Booking\Exceptions\NotEnoughCreditsException;
use Modules\Booking\Models\BookingInterface;

/**
 * Class CreditsPaymentMethod
 *
 * @package App\Services\Payment
 */
class CreditsPaymentMethod implements PaymentMethodInterface
{
    protected BookingInterface $booking;

    public function validate(BookingInterface $booking): void
    {
        $user = $booking->getStudent();
        $requiredCredits = $user->subscription->plan->credits;

        if ($user->getCreditBalance() < $requiredCredits) {
            throw new NotEnoughCreditsException('Not enough credits to book the lesson.');
        }
    }

    public function authorize(BookingInterface $booking): void
    {
        // Here we could log or prepare payment but not yet deduct (like authorize in Magento)
    }

    public function setBooking(BookingInterface $booking): void
    {
        $this->booking = $booking;
    }

    public function getBooking(): BookingInterface
    {
        return $this->booking;
    }

    public function place()
    {
//        $this->_eventManager->dispatch('sales_order_payment_place_start', ['payment' => $this]);
        $booking = $this->getBooking();
        // decrease credits
//        $this->_eventManager->dispatch('sales_order_payment_place_end', ['payment' => $this]);
        return $this;
    }
}
