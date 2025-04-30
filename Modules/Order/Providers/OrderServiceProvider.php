<?php
declare(strict_types=1);

namespace Modules\Order\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Order\Contracts\OrderFactoryInterface;
use Modules\Order\Contracts\OrderInterface;
use Modules\Order\Contracts\OrderManagerInterface;
use Modules\Order\Contracts\OrderPlacementServiceInterface;
use Modules\Order\Factories\OrderFactory;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderManager;
use Modules\Order\Services\OrderPlacementService;
use Nwidart\Modules\Traits\PathNamespace;

/**
 * Class OrderServiceProvider
 *
 * @package Modules\Order\Providers
 */
class OrderServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Order';
    protected string $nameLower = 'order';

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        // Management binding
        $this->app->bind(OrderInterface::class, Order::class);
        $this->app->bind(OrderManagerInterface::class, OrderManager::class);
        $this->app->bind(OrderPlacementServiceInterface::class, OrderPlacementService::class);
        $this->app->bind(OrderFactoryInterface::class, OrderFactory::class);
    }
}
