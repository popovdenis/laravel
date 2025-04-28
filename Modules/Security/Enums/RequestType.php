<?php
/**
 * @package Modules\Security\Models\Enums
 */

namespace Modules\Security\Enums;

enum RequestType: string
{
    case BOOKING_ATTEMPT_REQUEST = 'booking';
    case CUSTOMER_PASSWORD_RESET_REQUEST = 'customer_password_reset';
    case ADMIN_PASSWORD_RESET_REQUEST = 'admin_password_reset';
}
