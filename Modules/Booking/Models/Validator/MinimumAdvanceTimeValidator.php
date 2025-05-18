<?php
declare(strict_types=1);

namespace Modules\Booking\Models\Validator;

use Modules\Base\Services\CustomerTimezone;
use Modules\Booking\Contracts\SlotContextInterface;
use Modules\Booking\Exceptions\BookingValidationException;
use Modules\Booking\Models\ConfigProvider;

/**
 * Class MinimumAdvanceTimeValidator
 *
 * @package Modules\Booking\Models\Validator
 */
class MinimumAdvanceTimeValidator
{
    private CustomerTimezone $timezone;
    private ConfigProvider   $configProvider;

    public function __construct(
        CustomerTimezone $timezone,
        ConfigProvider $configProvider
    )
    {
        $this->timezone = $timezone;
        $this->configProvider = $configProvider;
    }

    /**
     * @throws BookingValidationException
     */
    public function validate(SlotContextInterface $slotContext): void
    {
        $allowedTime = $this->getMinimumAllowedTime();

        if ($this->timezone->date()->diffInMinutes($slotContext->getSlotStart()) < $allowedTime) {
            throw new BookingValidationException(sprintf(
                'Slot must be booked at least %s minutes before it starts.', $allowedTime
            ));
        }
    }

    private function getMinimumAllowedTime(): ?int
    {
        return $this->configProvider->getMinimumAdvanceTime();
    }
}