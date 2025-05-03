<x-filament::page>
    <div class="space-y-4">
        <x-filament::button wire:click="clearAll" color="danger">
            Flush All Caches
        </x-filament::button>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-filament::button wire:click="clearConfig">
                Clear Config Cache
            </x-filament::button>

            <x-filament::button wire:click="clearRoute">
                Clear Route Cache
            </x-filament::button>

            <x-filament::button wire:click="clearView">
                Clear View Cache
            </x-filament::button>
        </div>
    </div>
</x-filament::page>
