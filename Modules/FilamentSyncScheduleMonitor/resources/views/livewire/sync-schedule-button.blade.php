<button
    x-data="{{ json_encode(['visible' => $visible]) }}"
    x-show="visible"
    wire:click="sync"
    wire:loading.attr="disabled"
    wire:key="sync-schedule-button"
    type="button"
    class="flex flex-shrink-0 w-10 h-10 rounded-full bg-gray-200 items-center justify-center relative dark:bg-gray-900"
    x-tooltip.raw="{{ __('Sync Scheduled Tasks') }}"
>
    @svg('heroicon-s-arrow-path', 'w-5 h-5', ['wire:loading.remove.delay'])

    <x-filament::loading-indicator x-cloak wire:loading.delay wire:target="sync" class="filament-button-icon w-5 h-5"/>
</button>
