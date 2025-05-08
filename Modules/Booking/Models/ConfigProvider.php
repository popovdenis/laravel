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

    public const CONFIG_PATH_BOOKING_GROUP_LESSON_DURATION = 'booking.rules.group_lesson_duration';
    public const CONFIG_PATH_BOOKING_INDIVIDUAL_LESSON_DURATION = 'booking.rules.individual_lesson_duration';
    public const CONFIG_PATH_BOOKING_CANCELLATION_DEADLINE = 'booking.rules.cancellation_deadline';

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
}
