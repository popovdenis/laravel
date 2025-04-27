<?php
declare(strict_types=1);

namespace Modules\Payment\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Payment\Models\Transaction\Manager;
use Modules\Payment\Models\Transaction\ManagerInterface;

/**
 * Class PaymentServiceProvider
 *
 * @package Modules\Payment\Providers
 */
class PaymentServiceProvider extends ServiceProvider
{
    protected string $name = 'Payment';
    protected string $nameLower = 'payment';

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        // Management binding
        $this->app->bind(ManagerInterface::class, Manager::class);
    }
}
