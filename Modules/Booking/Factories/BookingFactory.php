<?php
declare(strict_types=1);

namespace Modules\Booking\Factories;

use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingInterface;

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
