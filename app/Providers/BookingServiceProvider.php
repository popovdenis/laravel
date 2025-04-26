<?php

namespace App\Providers;

use App\Factories\BookingFactory;
use App\Factories\BookingFactoryInterface;
use App\Services\Booking\SubmitBookingValidator;
use App\Services\Booking\SubmitBookingValidatorInterface;
use App\Services\Booking\BookingPlacementService;
use App\Services\Booking\BookingPlacementServiceInterface;
use Illuminate\Support\ServiceProvider;
use App\Services\Booking\BookingManagementInterface;
use App\Services\Booking\BookingManagementService;
use App\Services\Booking\SlotAvailabilityValidatorInterface;
use App\Services\Booking\SlotAvailabilityValidator;
use App\Services\Payment\PaymentMethodResolver;

class BookingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Booking Management binding
        $this->app->bind(BookingManagementInterface::class, BookingManagementService::class);
        $this->app->bind(BookingFactoryInterface::class, BookingFactory::class);

        // Slot Validator binding
        $this->app->bind(SlotAvailabilityValidatorInterface::class, SlotAvailabilityValidator::class);
        $this->app->bind(SubmitBookingValidatorInterface::class, SubmitBookingValidator::class);
        $this->app->bind(BookingPlacementServiceInterface::class, BookingPlacementService::class);

        // PaymentMethodResolver (singleton)
        $this->app->singleton(PaymentMethodResolver::class, PaymentMethodResolver::class);
    }
}
