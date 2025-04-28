<?php
declare(strict_types=1);

namespace Modules\Booking\Services;

use Modules\Booking\Contracts\BookingInterface;
use Modules\Booking\Contracts\BookingPlacementServiceInterface;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Models\Booking;
use Modules\Payment\Models\Transaction\ManagerInterface;

/**
 * Class BookingPlacementService
 *
 * @package App\Services\Booking
 */
class BookingPlacementService implements BookingPlacementServiceInterface
{
    /**
     * @var \Modules\Payment\Models\Transaction\ManagerInterface
     */
    private ManagerInterface $transactionManager;

    public function __construct(ManagerInterface $transactionManager)
    {
        $this->transactionManager = $transactionManager;
    }

    public function getStatus($id)
    {
        // TODO: Implement getStatus() method.
    }

    public function place(BookingInterface $booking): BookingInterface
    {
        try {
            $booking->place();

            $newBooking = Booking::create([
                'student_id'           => $booking->getStudent()->id,
                'stream_id'            => $booking->getStreamId(),
                'schedule_timeslot_id' => $booking->getSlotId(),
                'status'               => BookingStatus::PENDING,
            ]);

            $transactionId = $booking->getTransactionId();
            if ($transactionId) {
                $transaction = $this->transactionManager->getTransaction($transactionId, $booking->getStudent());
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

    public function cancel(BookingInterface $booking): bool
    {
        try {
            if ($booking->canCancel()) {
                $booking->cancel();
                $booking->newQuery()
                    ->where('id', $booking->id)
                    ->update(['status' => BookingStatus::CANCELLED]);
                // $booking->delete(); // Soft delete?
                $transactionId = $booking->getTransactionId();
                if ($transactionId) {
                    $transaction = $this->transactionManager->getTransaction($transactionId, $booking->getStudent());
                    $transaction->update(['booking_id' => $booking->id]);
                }
            }
        } catch (\Exception $e) {
//            $this->logger->critical(
//                'Saving order ' . $order->getIncrementId() . ' failed: ' . $e->getMessage()
//            );
            throw $e;
        }

        return true;
    }
}
