<?php
/**
 * @package App\Enums
 */

namespace App\Enums;

enum PaymentMethod: string
{
    case CREDITS = 'credits';
    case STRIPE = 'stripe';
}
