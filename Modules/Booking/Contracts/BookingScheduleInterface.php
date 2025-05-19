<?php

namespace Modules\Booking\Contracts;

/**
 * Interface BookingScheduleInterface
 *
 * @package Modules\Booking\Contracts
 */
interface BookingScheduleInterface
{
    const STUDENT = 'student';
    const TEACHER = 'teacher';
    const FILTERS = 'filters';
    const STREAMS = 'streams';
    const LEVELS = 'levels';
    const FIRST_LEVEL = 'first_level';
    const SUBJECTS = 'subjects';
    const FILTER_START_DATE = 'filter_start_date';
    const FILTER_END_DATE = 'filter_end_date';
    const USER_BOOKED_SLOTS = 'user_booked_slots';
    const STREAM_CURRENT_DATE = 'stream_current_date';
}
