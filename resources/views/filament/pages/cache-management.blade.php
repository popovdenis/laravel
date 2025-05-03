<x-filament::page>
    <div class="flex justify-end">
        <x-filament::button wire:click="flush" color="danger" icon="heroicon-o-trash">
            {{ __('Flush Laravel Cache')  }}
        </x-filament::button>
    </div>

    <x-filament-panels::form :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()" wire:submit="save">
        <div class="flex items-end gap-4 mb-2">
            {{ $this->form }}
            <x-filament::button type="submit">{{ __('Submit')  }}</x-filament::button>
        </div>

        <table class="w-full table-auto divide-y divide-gray-300 text-sm">
            <thead style="background-color: #1f2937; color: white;">
            <tr>
                <th class="px-3 py-2 text-center w-12">
                    <input type="checkbox" onclick="toggleAll(this)">
                </th>
                <th class="px-3 py-2 text-left">{{ __('Cache Type')  }}</th>
                <th class="px-3 py-2 text-left">{{ __('Description')  }}</th>
                <th class="px-3 py-2 text-left">{{ __('Tag')  }}</th>
                <th class="px-3 py-2 text-left">{{ __('Status')  }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($cacheItems as $index => $item)
                <tr class="border-b" style="background-color: {{ $index % 2 === 0 ? '#ffffff' : '#F2F2F2' }}">
                    <td class="px-3 py-2 text-center w-12">
                        <input type="checkbox" wire:model="data.selected" value="{{ $item['command'] }}" name="data.selected[]">
                    </td>
                    <td class="px-3 py-2">{{ $item['cache_type'] }}</td>
                    <td class="px-3 py-2">{{ $item['description'] ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $item['tag'] ?? '-' }}</td>
                    <td class="px-3 py-2">
                        @if ($item['status'])
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
            Enabled
        </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
            Disabled
        </span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <script>
            function toggleAll(source) {
                const checkboxes = document.querySelectorAll('input[name="data.selected[]"]');
                checkboxes.forEach(el => {
                    el.checked = source.checked;

                    const event = new Event("change", { bubbles: true, cancelable: false });
                    document.dispatchEvent(event);
                    el.dispatchEvent(event);
                });
            }
        </script>
        <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
    </x-filament-panels::form>
</x-filament::page>
