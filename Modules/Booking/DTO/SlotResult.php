<?php
declare(strict_types=1);

namespace Modules\Booking\DTO;

/**
 * Class SlotResult
 *
 * @package Modules\Booking\DTO
 */
class SlotResult
{
    public function __construct(
        public string $time,
        public string $slotStartAt,
        public string $slotEndAt,
        public string $lessonType,
        public $stream,
        public $subject,
        public int $currentSubjectNumber,
        public $slot,
        public $uid,
        public ?int $bookingId,
        public bool $isBookable
    ) {}
}
