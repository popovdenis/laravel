<?php

namespace Modules\Booking\Contracts;

/**
 * Interface CreditBalanceValidatorInterface
 *
 * @package Modules\Booking\Contracts
 */
interface CreditBalanceValidatorInterface
{
    public function validate(BookingQuoteInterface $bookingQuote): void;
}
