<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
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
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Facades\Filament;

class AdminPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        FilamentAsset::register([
            Js::make('filters-toggle', asset('js/filament/forms/components/filters-toggle.js')),
        ]);

        $this->registerNavigationGroups();
        $this->registerNavigationItems();
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function registerNavigationGroups(): void
    {
        Filament::registerNavigationGroups(
            [
                NavigationGroup::make()->label('Catalog')->collapsed(),
                NavigationGroup::make()->label('Members')->collapsed(),
                NavigationGroup::make()->label('Education')->collapsed(),
                NavigationGroup::make()->label('Blog')->collapsed(),
                NavigationGroup::make()->label('System')->collapsed(),
            ],
        );
    }

    public function registerNavigationItems(): void
    {
        $menuItems = array_merge(
            [],
            $this->buildCatalogMenuItems(),
            $this->buildMembersMenuItems(),
            $this->buildEducationMenuItems(),
            $this->buildBlogMenuItems(),
            $this->buildSystemMenuItems(),
        );
        Filament::registerNavigationItems($menuItems);
    }

    private function buildCatalogMenuItems(): array
    {
        return [
            NavigationItem::make('Courses')->url('courses')->icon('heroicon-o-rectangle-stack')
            ->group('Catalog')
            ->sort(1)
        ];
    }

    private function buildMembersMenuItems(): array
    {
        return [
            NavigationItem::make('Teachers')->url('teachers')->icon('heroicon-o-user')
                ->group('Members')
                ->sort(1),
            NavigationItem::make('Students')->url('students')->icon('heroicon-o-user-circle')
                ->group('Members')
                ->sort(2),
            NavigationItem::make('All Users')->url('users')->icon('heroicon-o-user-group')
                ->group('Members')
                ->sort(3),
        ];
    }

    private function buildEducationMenuItems(): array
    {
        return [
            NavigationItem::make('Schedule')->url('schedules')->icon('heroicon-o-clock')
                ->group('Education')
                ->sort(1),
        ];
    }

    private function buildBlogMenuItems(): array
    {
        return [
            NavigationItem::make('Categories')->url('categories')->icon('heroicon-o-folder')
                ->group('Blog')
                ->sort(1),
            NavigationItem::make('Comments')->url('comments')->icon('heroicon-o-chat-bubble-left-right')
                ->group('Blog')
                ->sort(2),
            NavigationItem::make('Posts')->url('posts')->icon('heroicon-o-document-text')
                ->group('Blog')
                ->sort(3),
            NavigationItem::make('Uploaded Images')->url('upload-images')->icon('heroicon-o-photo')
                ->group('Blog')
                ->sort(4),
        ];
    }

    private function buildSystemMenuItems(): array
    {
        return [
            NavigationItem::make('Configuration')->url('configuration')->icon('heroicon-o-cog')
                ->group('System')
                ->sort(1),
        ];
    }
}
