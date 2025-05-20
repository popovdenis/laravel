<?php

namespace Modules\Booking\Contracts;

use Modules\User\Models\User;

/**
 * Interface SlotContextInterface
 *
 * @package Modules\Booking\Contracts
 */
interface SlotContextInterface
{
    const DAY_SLOT = 'day_slot';
    const STREAM = 'stream';
    const CURRENT_DATE = 'current_date';
    const SUBJECT_ID = 'subject_id';
    const TEACHER = 'teacher';
    const STUDENT = 'student';
    const LESSON_TYPE = 'lesson_type';
    const SLOT_LENGTH = 'lesson_length';
    const SLOT_START = 'slot_start';
    const SLOT_END = 'slot_end';
    const CURRENT_SUBJECT = 'current_subject';

    public function getDaySlot();

    public function setStream($stream): self;
    public function getStream();

    public function getCurrentDate();

    public function getSubjectId();
    public function getCurrentSubject();

    public function setTeacher(User $teacher);
    public function getTeacher();

    public function getStudent();

    public function getLessonType();

    public function getSlotLength();

    public function setSlotStart($slotStart);
    public function getSlotStart();

    public function setSlotEnd($slotEnd);
    public function getSlotEnd();
}