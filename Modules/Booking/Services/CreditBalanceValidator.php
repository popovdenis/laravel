<?php
declare(strict_types=1);

namespace Modules\Booking\Services;

use Modules\Booking\Contracts\BookingQuoteInterface;
use Modules\Booking\Contracts\CreditBalanceValidatorInterface;
use Modules\Subscription\Exceptions\InsufficientCreditsException;

/**
 * Class CreditBalanceValidator
 *
 * @package Modules\Booking\Services
 */
class CreditBalanceValidator implements CreditBalanceValidatorInterface
{
    public function validate(BookingQuoteInterface $bookingQuote): void
    {
        if ($bookingQuote->getUser()->credit_balance < $bookingQuote->getAmount()) {
            throw new InsufficientCreditsException(__('Not enough credits'));
        }
    }
}
