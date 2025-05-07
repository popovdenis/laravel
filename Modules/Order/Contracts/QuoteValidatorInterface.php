<?php

namespace Modules\Order\Contracts;

/**
 * Interface QuoteValidatorInterface
 *
 * @package Modules\Order\Contracts
 */
interface QuoteValidatorInterface
{
    public function validate(QuoteInterface $bookingQuote): void;
}
