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
    public function getSlotId(): int;
    public function setSlotId(int $slotId): void;

    public function setStreamId(int $streamId): void;
    public function getStreamId(): int;
}
