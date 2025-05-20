<?php

namespace Modules\User\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Enums\BookingTypeEnum;

class CreditDisplayHeader extends CreditDisplay
{
    /**
     * Get the view/contents that represent the component.
     */
    public function render(): View|string
    {
        $user = auth()->user();
        $groupBookings = $user->bookings->where('status', '!=', BookingStatus::CANCELLED)
                                   ->where('lesson_type', BookingTypeEnum::BOOKING_TYPE_GROUP->value)
                                   ->count();
        $individualBookings = $user->bookings->where('status', '!=', BookingStatus::CANCELLED)
                                        ->where('lesson_type', BookingTypeEnum::BOOKING_TYPE_INDIVIDUAL->value)
                                        ->count();

        return view('user::components.credit-display-header', compact('groupBookings', 'individualBookings'));
    }
}
