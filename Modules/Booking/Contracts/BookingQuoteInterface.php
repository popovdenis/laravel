<?php

namespace Modules\Booking\Contracts;

use Modules\Order\Contracts\QuoteInterface;

/**
 * Interface BookingQuoteInterface
 *
 * @package Modules\Booking\Contracts
 */
interface BookingQuoteInterface extends QuoteInterface
{
    public function getSlotId();
    public function setSlotId(int $slotId);

    public function setStreamId(int $streamId);
    public function getStreamId();
}
