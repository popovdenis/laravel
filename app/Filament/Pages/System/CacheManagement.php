<?php
declare(strict_types=1);

namespace App\Filament\Pages\System;

use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Artisan;
use Modules\CacheInvalidate\Services\CacheRegistryService;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;

/**
 * Class CacheManagement
 *
 * @package App\Filament\Pages\System
 */
class CacheManagement extends Page implements HasForms
{
    use HasUnsavedDataChangesAlert;
    use InteractsWithFormActions;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-trash';
    protected static string $view = 'filament.pages.cache-management';
    protected static ?string $title = 'Cache Management';
    protected static ?string $navigationLabel = 'Cache Management';
    protected static ?string $navigationGroup = 'System';
    protected static ?int $navigationSort = 2;

    public array $selected = [];
    public ?string $action = null;
    public array $cacheItems = [];
    public ?array $data = [];

    public function getLayout() : string
    {
        return static::$layout ?? 'filament-panels::components.layout.index';
    }

    public function mount(CacheRegistryService $registry): void
    {
        $this->cacheItems = $registry->getCombined()
            ->map(fn ($item) => $item->toArray())
            ->values()
            ->toArray();

        $this->form->fill(['selected' => [], 'action' => null]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->schema())
            ->statePath('data');
    }

    public function save(): void
    {
        $selected = $this->data['selected'] ?? [];
        $action = $this->data['action'] ?? null;

        if (empty($selected)) {
            return;
        }

        $registry = app(CacheRegistryService::class);

        if ($action === 'enable' || $action === 'disable') {
            $registry->updateStatuses($selected, $action === 'enable');
        }

        if ($action === 'refresh') {
            foreach ($selected as $type) {
                Artisan::call($type);
            }
        }

        $this->getSavedNotification()?->send();

        if ($redirectUrl = $this->getRedirectUrl()) {
            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
        }

        $this->redirect(route('filament.admin.pages.cache-management'));
    }

    public function schema(): array
    {
        return [
            Select::make('action')
                ->options([
                    'enable' => 'Enable',
                    'disable' => 'Disable',
                    'refresh' => 'Refresh',
                ])->hiddenLabel(),
        ];
    }

    public function flush(): void
    {
        Artisan::call('optimize:clear');

        Notification::make()
            ->title('Laravel cache flushed.')
            ->success()
            ->send();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('action')
                ->options([
                    'enable' => 'Enable',
                    'disable' => 'Disable',
                    'refresh' => 'Refresh',
                ])
                ->required(),

            Forms\Components\CheckboxList::make('selected')
                ->label('Select Cache Types')
                ->options(
                    collect($this->cacheItems)->mapWithKeys(fn ($item) => [
                        $item['command'] => "{$item['command']} ({$item['description']})"
                    ])
                )
                ->columns(1)
                ->required(),
        ];
    }

    protected function getSavedNotification() : ?Notification
    {
        $title = $this->getSavedNotificationTitle();

        if (blank($title)) {
            return null;
        }

        return Notification::make()
            ->success()
            ->title($this->getSavedNotificationTitle());
    }

    protected function getSavedNotificationTitle() : ?string
    {
        return __('Laravel cache flushed');
    }

    protected function getRedirectUrl() : ?string
    {
        return route('filament.admin.pages.cache-management');
    }
}



