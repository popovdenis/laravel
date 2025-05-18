<?php
declare(strict_types=1);

namespace Modules\LanguageLevel\DTO;

/**
 * Class SlotResult
 *
 * @package Modules\LanguageLevel\DTO
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
