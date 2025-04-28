<?php
/**
 * @package App\Enums
 */

namespace Modules\Payment\Enums;

enum PaymentMethod: string
{
    case CREDITS = 'credits';
    case STRIPE = 'stripe';
}
