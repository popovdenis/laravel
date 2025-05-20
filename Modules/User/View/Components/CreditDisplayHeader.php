<?php

namespace Modules\User\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Modules\Booking\Contracts\BookingRepositoryInterface;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Enums\BookingTypeEnum;

class CreditDisplayHeader extends CreditDisplay
{
    private BookingRepositoryInterface $bookingRepository;

    public function __construct(BookingRepositoryInterface $bookingRepository, string $size = 'base')
    {
        parent::__construct($size);
        $this->bookingRepository = $bookingRepository;
    }
    /**
     * Get the view/contents that represent the component.
     */
    public function render(): View|string
    {
        $user = auth()->user();
        $bookings = $this->bookingRepository->getUserBookingsByType(
            $user,
            BookingRepositoryInterface::SCHEDULED_CLASSES,
            100
        );

        $groupBookings = $bookings
            ->filter(fn($booking) => $booking->lesson_type == BookingTypeEnum::BOOKING_TYPE_GROUP)
            ->count();
        $individualBookings = $user->bookings
            ->filter(fn($booking) => $booking->lesson_type == BookingTypeEnum::BOOKING_TYPE_INDIVIDUAL)
            ->count();

        return view('user::components.credit-display-header', compact('groupBookings', 'individualBookings'));
    }
}
