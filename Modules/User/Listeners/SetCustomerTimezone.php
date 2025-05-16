<?php
declare(strict_types=1);

namespace Modules\User\Listeners;

use Modules\User\Models\User;

/**
 * Class SetCustomerTimezone
 *
 * @package Modules\User\Listeners
 */
class SetCustomerTimezone
{
    /**
     * Handle the event.
     */
    public function handle(array $data): void
    {
        /** @var User $customer */
        $customer = $data['customer'];
        $customerData = $data['customer_data'];

        $customer->update(['timeZoneId' => $customerData->timeZoneId ?? null]);
    }
}
