<?php
declare(strict_types=1);

namespace Modules\UserSubscription\Services;

use Modules\Order\Contracts\QuoteInterface;
use Modules\Subscription\Exceptions\SubscriptionValidationException;
use Modules\UserSubscription\Contracts\CustomerPaymentValidatorInterface;

/**
 * Class CustomerPaymentValidator
 *
 * @package Modules\UserSubscription\Services
 */
class CustomerPaymentValidator implements CustomerPaymentValidatorInterface
{
    public function validate(QuoteInterface $bookingQuote): void
    {
        $user = $bookingQuote->getStudent();
        if ($user->getActiveSubscription() && !$user->hasDefaultPaymentMethod()) {
            throw new SubscriptionValidationException('You don’t have an active payment method.');
        }
    }
}
