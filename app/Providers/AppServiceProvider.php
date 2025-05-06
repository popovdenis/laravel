<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Laravel\Cashier\Cashier::useCustomerModel(\Modules\User\Models\User::class);
//        Relation::morphMap([
//            'booking' => \Modules\Booking\Models\Booking::class,
//            'subscription' => \Modules\Subscription\Models\Subscription::class,
//            'Laravel\\Cashier\\Subscription' => \Modules\Subscription\Models\Subscription::class,
//        ]);
    }
}
