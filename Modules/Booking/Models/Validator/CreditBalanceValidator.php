<?php
declare(strict_types=1);

namespace Modules\Booking\Models\Validator;

use Modules\Booking\Contracts\CreditBalanceValidatorInterface;
use Modules\Order\Contracts\QuoteInterface;
use Modules\Subscription\Exceptions\InsufficientCreditsException;

/**
 * Class CreditBalanceValidator
 *
 * @package Modules\Booking\Services
 */
class CreditBalanceValidator implements CreditBalanceValidatorInterface
{
    public function validate(QuoteInterface $bookingQuote): void
    {
        if ($bookingQuote->getStudent()->credit_balance < $bookingQuote->getAmount()) {
            throw new InsufficientCreditsException('Not enough credits');
        }
    }
}
