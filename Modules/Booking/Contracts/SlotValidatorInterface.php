<?php

namespace Modules\Booking\Contracts;

/**
 * Interface SlotValidatorInterface
 *
 * @package Modules\Booking\Contracts
 */
interface SlotValidatorInterface
{
    public function validate(SlotContextInterface $slotContext): void;
}