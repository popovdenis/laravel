<?php

namespace Modules\Booking\Contracts;

use Modules\Order\Contracts\QuoteInterface;
use Modules\ScheduleTimeslot\Models\ScheduleTimeslot;

/**
 * Interface BookingQuoteInterface
 *
 * @package Modules\Booking\Contracts
 */
interface BookingQuoteInterface extends QuoteInterface
{
    public function getSlot();
    public function setSlot(ScheduleTimeslot $slot);

    public function getSlotContext();
    public function setSlotContext(SlotContextInterface $slotContext);

    public function setStreamId(int $streamId);
    public function getStreamId();
}
