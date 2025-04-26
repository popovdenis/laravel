<?php
declare(strict_types=1);

namespace App\Services\Booking;

use App\Models\Booking;
use App\Models\Enums\BookingStatus;

/**
 * Class BookingPlacementService
 *
 * @package App\Services\Booking
 */
class BookingPlacementService implements BookingPlacementServiceInterface
{
    public function cancel($id)
    {
        // TODO: Implement cancel() method.
    }

    public function getStatus($id)
    {
        // TODO: Implement getStatus() method.
    }

    public function place(\App\Models\Booking\BookingInterface $booking)
    {
        try {
            $booking->place();
        } catch (\Exception $e) {
            throw $e;
        }

        try {
            $booking = Booking::create([
                'student_id'           => $booking->getStudent()->id,
                'stream_id'            => $booking->getStreamId(),
                'schedule_timeslot_id' => $booking->getSlotId(),
                'status'               => BookingStatus::PENDING,
            ]);
        } catch (\Exception $e) {
//            $this->logger->critical(
//                'Saving order ' . $order->getIncrementId() . ' failed: ' . $e->getMessage()
//            );
            throw $e;
        }

        return $booking;
    }
}
