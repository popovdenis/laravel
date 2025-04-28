<?php
declare(strict_types=1);

namespace Modules\Security\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Security\Contracts\AttemptRequestFactoryInterface;
use Modules\Security\Contracts\SecurityCheckerInterface;
use Modules\Security\Factories\AttemptRequestFactory;
use Modules\Security\Models\AttemptRequestEvent;
use Modules\Security\Models\SecurityChecker\Frequency;
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
        $this->app->bind(AttemptRequestFactoryInterface::class, AttemptRequestFactory::class);
    }
}
