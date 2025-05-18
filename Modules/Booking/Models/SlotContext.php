<?php
declare(strict_types=1);

namespace Modules\Booking\Models;

use Modules\Base\Framework\DataObject;
use Modules\Booking\Contracts\SlotContextInterface;
use Modules\LanguageLevel\DTO\SlotResult;
use Modules\User\Models\User;

/**
 * Class BookingSlot
 *
 * @package Modules\Booking\Models
 */
class SlotContext extends DataObject implements SlotContextInterface
{
    public function getDaySlot()
    {
        return $this->getData(self::DAY_SLOT);
    }

    public function setStream($stream): self
    {
        return $this->setData(self::STREAM, $stream);
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

    public function getTeacher()
    {
        return $this->getData(self::TEACHER);
    }

    public function setTeacher(User $teacher)
    {
        return $this->setData(self::TEACHER, $teacher);
    }

    public function getStudent()
    {
        return $this->getData(self::STUDENT);
    }

    public function getLessonType()
    {
        return $this->getData(self::LESSON_TYPE);
    }

    public function getSlotLength()
    {
        return $this->getData(self::SLOT_LENGTH);
    }

    public function setSlotStart($slotStart)
    {
        return $this->setData(self::SLOT_START, $slotStart);
    }

    public function getSlotStart()
    {
        return $this->getData(self::SLOT_START);
    }

    public function setSlotEnd($slotEnd)
    {
        return $this->setData(self::SLOT_END, $slotEnd);
    }

    public function getSlotEnd()
    {
        return $this->getData(self::SLOT_END);
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
            $this->getLessonType()->value,
            $this->getSlotStart()->format('Y-m-d H:i:s')
        ]));
    }

    public function getSlotResult($booking): SlotResult
    {
        return new SlotResult(
            time: $this->getSlotStart()->format('H:i'),
            slotStartAt: $this->getSlotStart()->format('Y-m-d H:i'),
            slotEndAt: $this->getSlotEnd()->format('Y-m-d H:i'),
            lessonType: $this->getLessonType()->value,
            stream: $this->getStream(),
            subject: $this->getSubject(),
            currentSubjectNumber: $this->getSubjectId(),
            slot: $this->getDaySlot(),
            uid: $this->generateUID(),
            bookingId: $booking ? $booking->id : null,
            isBookable: true
        );
    }
}