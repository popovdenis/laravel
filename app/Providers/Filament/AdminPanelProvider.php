<?php

namespace App\Providers\Filament;

use CmsMulti\FilamentClearCache\FilamentClearCachePlugin;
use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Outerweb\FilamentSettings\Filament\Plugins\FilamentSettingsPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Js;
use CmsMulti\FilamentClearCache\Facades\FilamentClearCache;

class AdminPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        FilamentAsset::register([
            Js::make('filters-toggle', asset('js/filament/forms/components/filters-toggle.js')),
        ]);
        FilamentClearCache::addCommand('config:clear');
        FilamentClearCache::addCommand('route:clear');
        FilamentClearCache::addCommand('view:clear');
        FilamentClearCache::addCommand('event:clear');
        FilamentClearCache::addCommand('settings:clear-cache');
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
                FilamentSettingsPlugin::make()->pages([
                    \App\Filament\Pages\System\Configuration::class,
                ]),
                FilamentClearCachePlugin::make(),
                \Mvenghaus\FilamentScheduleMonitor\FilamentPlugin::make()
            ])
            ->authMiddleware([
                Authenticate::class,
            ])->navigationGroups([
                'School',
                'Booking',
                'Members',
                'Subscriptions',
                'Marketing',
                'Blog',
                'System',
                'Cron Manager',
            ]);
    }
}
