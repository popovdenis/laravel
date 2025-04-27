<?php
declare(strict_types=1);

namespace Modules\Booking\Services;

use Modules\Booking\Data\BookingData;
use Modules\Booking\Factories\BookingFactoryInterface;
use Modules\Booking\Models\BookingInterface;
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
        private BookingPlacementServiceInterface $placementService,
    ) {}

    public function place(BookingData $bookingData): BookingInterface
    {
        $booking = $this->bookingFactory->create();

        $booking->setStudent($bookingData->student);
        $booking->setStreamId($bookingData->streamId);
        $booking->setSlotId($bookingData->slotId);

        $this->bookingValidator->validate($booking);
        $this->slotValidator->validate($booking);

        // Validate and authorize payment
//        $paymentMethod = $this->paymentMethodResolver->resolve($bookingData->paymentMethod, $booking);
//        $booking->setPayment($paymentMethod);

        // Todo: move to payment
//        $paymentMethod->validate($booking);
//        $paymentMethod->authorize($booking);

        // TODO: create a booking, dispatch an event, etc.
        $booking = $this->placementService->place($booking);

        return $booking;
    }

    public function cancel(BookingInterface $booking): bool
    {
        return $this->placementService->cancel($booking);
    }
}
