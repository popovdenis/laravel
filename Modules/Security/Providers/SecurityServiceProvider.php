<?php
declare(strict_types=1);

namespace Modules\Security\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Security\Contracts\RequestTypeInterface;
use Modules\Security\Contracts\SecurityCheckerInterface;
use Modules\Security\Models\SecurityChecker\Frequency;
use Modules\Security\Models\SecurityChecker\RequestType\Booking;
use Nwidart\Modules\Traits\PathNamespace;

/**
 * Class SecurityServiceProvider
 *
 * @package Modules\Security\Providers
 */
class SecurityServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Security';
    protected string $nameLower = 'security';

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        // Management binding
        $this->app->bind(SecurityCheckerInterface::class, Frequency::class);
        $this->app->bind(RequestTypeInterface::class, Booking::class);
    }
}
