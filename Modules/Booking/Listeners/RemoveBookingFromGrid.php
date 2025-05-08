<?php
declare(strict_types=1);

namespace Modules\Booking\Listeners;

use Modules\BookingGridFlat\Models\BookingGridFlat;

/**
 * Class RemoveBookingFromGrid
 *
 * @package Modules\Booking\Listeners
 */
class RemoveBookingFromGrid
{
    public function handle(array $data): void
    {
        if (isset($data['order'])) {
            $order = $data['order'];
            $booking = $order->purchasable;

            BookingGridFlat::where('booking_id', $booking->id)->delete();
        }
    }
}
