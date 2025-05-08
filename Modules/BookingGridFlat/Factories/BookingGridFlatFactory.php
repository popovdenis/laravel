<?php
declare(strict_types=1);

namespace Modules\BookingGridFlat\Factories;

use Modules\BookingGridFlat\Contracts\BookingGridFlatInterface;
use Modules\BookingGridFlat\Models\BookingGridFlat;

/**
 * Class BookingGridFlatFactory
 *
 * @package Modules\BookingGridFlat\Factories
 */
class BookingGridFlatFactory
{
    public function create(array $data = []): BookingGridFlat
    {
        return new BookingGridFlat($data);
    }
}
