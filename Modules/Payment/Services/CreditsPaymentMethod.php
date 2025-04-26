<?php
declare(strict_types=1);

namespace Modules\Payment\Services;

use Modules\Booking\Exceptions\NotEnoughCreditsException;
use Modules\Booking\Models\BookingInterface;
use Modules\BookingCreditHistory\Services\BookingCreditHistoryInterface;
use Modules\Payment\Exceptions\PaymentFailedException;
use Modules\Subscription\Models\ConfigProvider;

/**
 * Class CreditsPaymentMethod
 *
 * @package App\Services\Payment
 */
class CreditsPaymentMethod implements PaymentMethodInterface
{
    protected BookingInterface $booking;

    public function __construct(
        protected ConfigProvider $configProvider,
        protected BookingCreditHistoryInterface $bookingCreditHistory,
    )
    {}

    public function validate(BookingInterface $booking): void
    {
        $user = $booking->getStudent();
        $requiredCredits = $this->configProvider->getGroupLessonPrice(); //TODO: add type

        if ($user->getCreditBalance() < $requiredCredits) {
            throw new PaymentFailedException(__('Not enough credits to book the lesson.'));
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
        $student = $booking->getStudent();
        $requiredCredits = $this->configProvider->getGroupLessonPrice();

        $this->bookingCreditHistory->spend($student, $requiredCredits);
        $transaction = $this->bookingCreditHistory->logTransaction($student, $requiredCredits, 'Booking a lesson');

        $booking->setLastPaymentTransaction($transaction);

//        $this->_eventManager->dispatch('sales_order_payment_place_end', ['payment' => $this]);
        return $this;
    }
}
