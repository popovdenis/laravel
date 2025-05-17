<?php

namespace Modules\Booking\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Modules\Booking\Console\ConfirmUpcomingBookingCommand;
use Modules\Booking\Contracts\BookingQuoteInterface;
use Modules\Booking\Contracts\CreditBalanceValidatorInterface;
use Modules\Booking\Contracts\SlotAvailabilityValidatorInterface;
use Modules\Booking\Contracts\SlotChunkInterface;
use Modules\Booking\Contracts\SubmitQuoteValidatorInterface;
use Modules\Booking\Models\BookingQuote;
use Modules\Booking\Models\SlotChunk;
use Modules\Booking\Models\Validator\CreditBalanceValidator;
use Modules\Booking\Models\Validator\SlotAvailabilityValidator;
use Modules\Booking\Models\Validator\SubmitBookingValidator;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class BookingServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Booking';
    protected string $nameLower = 'booking';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);

        // Management binding
        $this->app->bind(BookingQuoteInterface::class, BookingQuote::class);

        // Slot Validator binding
        $this->app->bind(SlotAvailabilityValidatorInterface::class, SlotAvailabilityValidator::class);
        $this->app->bind(SubmitQuoteValidatorInterface::class, SubmitBookingValidator::class);
        $this->app->bind(CreditBalanceValidatorInterface::class, CreditBalanceValidator::class);
        $this->app->bind(SlotChunkInterface::class, SlotChunk::class);
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        $this->commands([
            ConfirmUpcomingBookingCommand::class
        ]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        if (!app()->runningInConsole()) {
            return;
        }

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command(ConfirmUpcomingBookingCommand::SIGNATURE)->cron('0 */2 * * *')->storeOutputInDb();
        });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->nameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->name, 'lang'), $this->nameLower);
            $this->loadJsonTranslationsFrom(module_path($this->name, 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $relativeConfigPath = config('modules.paths.generator.config.path');
        $configPath = module_path($this->name, $relativeConfigPath);

        if (is_dir($configPath)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $relativePath = str_replace($configPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $configKey = $this->nameLower . '.' . str_replace([DIRECTORY_SEPARATOR, '.php'], ['.', ''], $relativePath);
                    $key = ($relativePath === 'config.php') ? $this->nameLower : $configKey;

                    $this->publishes([$file->getPathname() => config_path($relativePath)], 'config');
                    $this->mergeConfigFrom($file->getPathname(), $key);
                }
            }
        }
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->nameLower);

        $componentNamespace = $this->module_namespace($this->name, $this->app_path(config('modules.paths.generator.component-class.path')));
        Blade::componentNamespace($componentNamespace, $this->nameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->nameLower)) {
                $paths[] = $path.'/modules/'.$this->nameLower;
            }
        }

        return $paths;
    }
}
