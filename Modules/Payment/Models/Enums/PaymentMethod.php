<?php
/**
 * @package App\Enums
 */

namespace Modules\Payment\Models\Enums;

enum PaymentMethod: string
{
    case CREDITS = 'credits';
    case STRIPE = 'stripe';
}
