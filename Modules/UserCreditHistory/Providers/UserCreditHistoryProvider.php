<?php
declare(strict_types=1);

namespace Modules\UserCreditHistory\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\UserCreditHistory\Models\UserCreditHistoryInterface;
use Modules\UserCreditHistory\Services\UserCreditHistoryService;

/**
 * Class UserCreditHistoryProvider
 *
 * @package Modules\UserCreditHistory\Providers
 */
class UserCreditHistoryProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->bind(UserCreditHistoryService::class, UserCreditHistoryInterface::class);
    }
}
