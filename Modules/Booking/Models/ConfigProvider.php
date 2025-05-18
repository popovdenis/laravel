<?php
declare(strict_types=1);

namespace Modules\Booking\Models;

use Modules\Base\Models\ConfigProviderAbstract;

/**
 * Class ConfigProvider
 *
 * @package Modules\Booking\Models
 */
class ConfigProvider extends ConfigProviderAbstract
{
    public const CONFIG_PATH_BOOKING_REINDEX_REQUIRED_FLAG = 'reindex.required';

    public const CONFIG_PATH_BOOKING_GROUP_LESSON_DURATION = 'rules.group_lesson_duration';
    public const CONFIG_PATH_BOOKING_INDIVIDUAL_LESSON_DURATION = 'rules.individual_lesson_duration';
    public const CONFIG_PATH_BOOKING_CANCELLATION_DEADLINE = 'rules.cancellation_deadline';
    public const CONFIG_PATH_BOOKING_MINIMUM_ADVANCE_TIME = 'rules.minimum_advance_time';
    public const CONFIG_PATH_BOOKING_MAXIMUM_GROUP_MEMBERS_CAPACITY = 'rules.maximum_group_members_capacity';

    public const CONFIG_PATH_BOOKING_LISTING_DEFAULT_LESSON_TYPE = 'listing.default_lesson_type';
    public const CONFIG_PATH_BOOKING_LISTING_INITIAL_DAYS_RANGE = 'listing.initial_days_range';
    public const CONFIG_PATH_BOOKING_LISTING_LOAD_MORE_DAYS = 'listing.load_more_days';

    protected $pathPrefix = 'booking.';

    public function markBookingReindex($value): void
    {
        $this->setValue(self::CONFIG_PATH_BOOKING_REINDEX_REQUIRED_FLAG, $value);
    }

    public function getBookingReindexRequiredFlag(): bool
    {
        return (bool) $this->getValue(self::CONFIG_PATH_BOOKING_REINDEX_REQUIRED_FLAG);
    }

    public function getBookingGroupLessonDuration(): ?int
    {
        return (int) $this->getValue(self::CONFIG_PATH_BOOKING_GROUP_LESSON_DURATION);
    }

    public function getBookingIndividualLessonDuration(): ?int
    {
        return (int) $this->getValue(self::CONFIG_PATH_BOOKING_INDIVIDUAL_LESSON_DURATION);
    }

    public function getBookingCancellationDeadline(): ?int
    {
        return $this->getValue(self::CONFIG_PATH_BOOKING_CANCELLATION_DEADLINE);
    }

    public function getMinimumAdvanceTime(): ?int
    {
        return $this->getValue(self::CONFIG_PATH_BOOKING_MINIMUM_ADVANCE_TIME);
    }

    public function getMaximumGroupMembersCapacity(): ?int
    {
        return $this->getValue(self::CONFIG_PATH_BOOKING_MAXIMUM_GROUP_MEMBERS_CAPACITY);
    }

    public function getDefaultLessonType()
    {
        return $this->getValue(self::CONFIG_PATH_BOOKING_LISTING_DEFAULT_LESSON_TYPE);
    }

    public function getInitialDaysToShow(): ?int
    {
        return (int) $this->getValue(self::CONFIG_PATH_BOOKING_LISTING_INITIAL_DAYS_RANGE);
    }

    public function getLoadMoreDays(): ?int
    {
        return (int) $this->getValue(self::CONFIG_PATH_BOOKING_LISTING_LOAD_MORE_DAYS);
    }
}
