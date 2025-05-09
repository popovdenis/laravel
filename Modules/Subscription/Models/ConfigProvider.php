<?php
declare(strict_types=1);

namespace Modules\Subscription\Models;

use Modules\Base\Models\ConfigProviderAbstract;

/**
 * Class ConfigProvider
 *
 * @package Modules\Booking\Models
 */
class ConfigProvider extends ConfigProviderAbstract
{
    public const CONFIG_PATH_GENERAL_RESET_CREDITS = 'general.reset_credits_on_plan_change';

    public const CONFIG_PATH_GROUP_LESSON_PRICE = 'group_lesson_price';
    public const CONFIG_PATH_INDIVIDUAL_LESSON_PRICE = 'individual_lesson_price';

    protected $pathPrefix = 'subscription.';

    public function resetCreditsOnPlanChange(): bool
    {
        return (bool) $this->getValue(self::CONFIG_PATH_GENERAL_RESET_CREDITS);
    }

    public function getGroupLessonPrice(): int
    {
        return (int) $this->getValue(self::CONFIG_PATH_GROUP_LESSON_PRICE);
    }

    public function getIndividualLessonPrice(): int
    {
        return (int) $this->getValue(self::CONFIG_PATH_INDIVIDUAL_LESSON_PRICE);
    }
}
