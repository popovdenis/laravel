<?php

namespace Modules\Booking\Contracts;

/**
 * Interface SlotChunkInterface
 *
 * @package Modules\Booking\Contracts
 */
interface SlotChunkInterface
{
    const DAY_SLOT = 'day_slot';
    const STREAM = 'stream';
    const CURRENT_DATE = 'current_date';
    const SUBJECT_ID = 'subject_id';
    const STUDENT = 'student';
    const LESSON_TYPE = 'lesson_type';
    const SLOT_START = 'slot_start';

    public function addData(array $arr);
    public function setData($key, $value = null);
    public function appendData($key, $value = null);

    public function getDaySlot();
    public function getStream();
    public function getCurrentDate();
    public function getSubjectId();
    public function getLessonType();
    public function getSlotStart();
}