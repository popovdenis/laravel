<?php
declare(strict_types=1);

namespace Modules\Booking\Models;

use Modules\Booking\Contracts\SlotContextInterface;
use Modules\Booking\Contracts\SlotValidatorInterface;

/**
 * Class BookingValidator
 *
 * @package Modules\Booking\Models
 */
class SlotValidator
{
    /** @var SlotValidatorInterface[] $validators  */
    private array $validators;

    public function __construct(array $validators = [])
    {
        $this->validators = $validators;
    }

    public function validate(SlotContextInterface $slotContext): void
    {
        foreach ($this->validators as $validator) {
            $validator->validate($slotContext);
        }
    }
}