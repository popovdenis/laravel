<?php
declare(strict_types=1);

namespace App\Factories;

use App\Enums\PaymentMethod;
use App\Models\Booking;
use App\Models\Booking\BookingInterface;
use Illuminate\Http\Request;

/**
 * Class BookingFactory
 *
 * @package App\Factories
 */
class BookingFactory implements BookingFactoryInterface
{
    public function create(): BookingInterface
    {
        return new Booking();
    }
}
