<?php
declare(strict_types=1);

namespace Modules\FilamentSyncScheduleMonitor\Http\Livewire;

use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Livewire\Component;
use Modules\FilamentSyncScheduleMonitor\Jobs\SyncScheduleJob;

/**
 * Class SyncScheduleMonitor
 *
 * @package Modules\FilamentSyncScheduleMonitor\Http\Livewire
 */
class SyncScheduleMonitor extends Component
{
    public bool $visible;

    public function mount(): void
    {
        throw_if(
            ! Filament::auth()->check(),
            AuthenticationException::class
        );

        $this->visible = $this->getVisibility();
    }

    public function getVisibility(): bool
    {
        if (($user = auth()->user()) === null) {
            return false;
        }

        if ($permissions = config('filament-sync-schedule-monitor.permissions')) {
            return $user->can($permissions);
        }

        if (method_exists($user, 'hasRole') && $role = config('filament-sync-schedule-monitor.role')) {
            return $user->hasRole($role);
        }

        return true;
    }

    public function sync()
    {
        Notification::make()
            ->title(__('Scheduled Tasks are synced successfully'))
            ->success()
            ->send();

        SyncScheduleJob::dispatchAfterResponse();

        // Refresh page to ensure new cache
        if ($referer = request()->header('Referer')) {
            $this->redirect($referer);
        }
    }

    public function render(): View
    {
        return view('filament-sync-schedule-monitor::livewire.sync-schedule-button');
    }
}
