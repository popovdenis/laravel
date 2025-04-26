<?php
declare(strict_types=1);

namespace Modules\Booking\Services;

use Modules\Booking\Models\Booking;
use Modules\Booking\Models\Enums\BookingStatus;

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

    public function place(\Modules\Booking\Models\BookingInterface $booking)
    {
        try {
            $booking->place();
        } catch (\Exception $e) {
            throw $e;
        }

        try {
            $newBooking = Booking::create([
                'student_id'           => $booking->getStudent()->id,
                'stream_id'            => $booking->getStreamId(),
                'schedule_timeslot_id' => $booking->getSlotId(),
                'status'               => BookingStatus::PENDING,
            ]);

            $transaction = $booking->getLastPaymentTransaction();
            if ($transaction) {
                $transaction->update(['booking_id' => $newBooking->id]);
            }
        } catch (\Exception $e) {
//            $this->logger->critical(
//                'Saving order ' . $order->getIncrementId() . ' failed: ' . $e->getMessage()
//            );
            throw $e;
        }

        return $booking;
    }
}
