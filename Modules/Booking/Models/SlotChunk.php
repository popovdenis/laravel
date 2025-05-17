<?php
declare(strict_types=1);

namespace Modules\Booking\Models;

use Modules\Base\Framework\DataObject;
use Modules\Booking\Contracts\SlotChunkInterface;
use Modules\LanguageLevel\DTO\SlotResult;

/**
 * Class SlotChunk
 *
 * @package Modules\Booking\Models
 */
class SlotChunk extends DataObject implements SlotChunkInterface
{
    public function getDaySlot()
    {
        return $this->getData(self::DAY_SLOT);
    }

    public function getStream()
    {
        return $this->getData(self::STREAM);
    }

    public function getCurrentDate()
    {
        return $this->getData(self::CURRENT_DATE);
    }

    public function getSubjectId()
    {
        return $this->getData(self::SUBJECT_ID);
    }

    public function getStudent()
    {
        return $this->getData(self::STUDENT);
    }

    public function getLessonType()
    {
        return $this->getData(self::LESSON_TYPE);
    }

    public function getSlotStart()
    {
        return $this->getData(self::SLOT_START);
    }

    public function getSubject()
    {
        return $this->getStream()->languageLevel->subjects->firstWhere('id', $this->getSubjectId());
    }

    public function generateUID(): string
    {
        return md5(implode('.', [
            $this->getStudent()->id,
            $this->getStream()->id,
            $this->getLessonType(),
            $this->getSlotStart()->format('Y-m-d H:i:s')
        ]));
    }

    public function getSlotResult($booking)
    {
        return new SlotResult(
            time: $this->getSlotStart()->format('H:i'),
            slotStartAt: $this->getSlotStart()->format('Y-m-d H:i'),
            lessonType: $this->getLessonType(),
            stream: $this->getStream(),
            subject: $this->getSubject(),
            currentSubjectNumber: $this->getSubjectId(),
            slot: $this->getDaySlot(),
            uid: $this->generateUID(),
            bookingId: $booking ? $booking->id : null,
            isBookable: $this->getSlotStart()->greaterThan(now())
        );
    }
}