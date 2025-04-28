<?php
declare(strict_types=1);

namespace Modules\Booking\Factories;

use Modules\Booking\Contracts\BookingFactoryInterface;
use Modules\Booking\Contracts\BookingInterface;
use Modules\Booking\Models\Booking;

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
