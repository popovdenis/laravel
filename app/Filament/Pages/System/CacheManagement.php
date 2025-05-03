<?php
declare(strict_types=1);

namespace App\Filament\Pages\System;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Filament\Notifications\Notification;

class CacheManagement extends Page
{
    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationLabel = 'Cache Management';
    protected static ?string $navigationIcon = 'heroicon-o-trash';
    protected static ?string $slug = 'cache-management';
    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament/pages/cache-management';

    public function clearAll(): void
    {
        Artisan::call('optimize:clear');
        Notification::make()
            ->title('All cache cleared')
            ->success()
            ->send();
    }

    public function clearConfig(): void
    {
        Artisan::call('config:clear');
        Notification::make()
            ->title('Config cache cleared')
            ->success()
            ->send();
    }

    public function clearRoute(): void
    {
        Artisan::call('route:clear');
        Notification::make()
            ->title('Route cache cleared')
            ->success()
            ->send();
    }

    public function clearView(): void
    {
        Artisan::call('view:clear');
        Notification::make()
            ->title('View cache cleared')
            ->success()
            ->send();
    }
}
