<?php
declare(strict_types=1);

namespace Modules\Booking\Services;

use Modules\Booking\Data\BookingData;
use Modules\Booking\Factories\BookingFactoryInterface;
use Modules\Payment\Services\PaymentMethodResolver;

/**
 * Class BookingManagementService
 *
 * @package App\Services\Booking
 */
class BookingManagementService implements BookingManagementInterface
{
    public function __construct(
        private BookingFactoryInterface $bookingFactory,
        private SubmitBookingValidatorInterface $bookingValidator,
        private SlotAvailabilityValidatorInterface $slotValidator,
        private PaymentMethodResolver $paymentMethodResolver,
        private BookingPlacementServiceInterface $placementService,
    ) {}

    public function submit(BookingData $bookingData)
    {
        $booking = $this->bookingFactory->create();

        $booking->setStudent($bookingData->student);
        $booking->setStreamId($bookingData->streamId);
        $booking->setSlotId($bookingData->slotId);
        $booking->setPaymentMethod($bookingData->paymentMethod);

        $this->bookingValidator->validate($booking);
        $this->slotValidator->validate($booking);

        // Validate and authorize payment
        $paymentMethod = $this->paymentMethodResolver->resolve($bookingData->paymentMethod, $booking);
        $booking->setPayment($paymentMethod);

        $paymentMethod->validate($booking);
        $paymentMethod->authorize($booking);

        // Дальше создание букинга, диспатч событий и т.д.
        $booking = $this->placementService->place($booking);
    }
}
