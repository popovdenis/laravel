<div class="space-y-4">

    {{-- Notifications --}}
    @if (session()->has('notification'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 3000)"
            x-show="show"
            x-transition
            class="p-4 rounded border text-sm
            {{ session('notification.status') === 'success' ? 'bg-green-100 text-green-800 border-green-300' : 'bg-red-100 text-red-800 border-red-300' }}">
            {{ session('notification.message') }}
        </div>
    @endif

    {{-- Main content --}}
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-4">
            <select wire:model="bulkAction" class="border rounded p-1 w-32">
                <option value="">Action</option>
                <option value="run">Run</option>
            </select>

            <button wire:click="submitSelected" class="px-3 py-1 bg-primary-600 text-white rounded">
                Submit
            </button>

            <span wire:ignore.self class="ml-4 text-sm text-gray-600">
                {{ count($cacheTypes) }} records found
                <span wire:loading.remove wire:target="selected">
                    @if (!empty($selected))
                        ({{ count($selected) }} selected)
                    @endif
                </span>
            </span>
            <pre class="text-xs text-gray-400">
                Selected: @json($selected)
            </pre>
        </div>

        <button wire:click="flushAll"
                class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
            Flush Laravel Cache
        </button>
    </div>

    {{-- Caches --}}
    <table class="w-full table-auto divide-y divide-gray-300 text-sm">
        <thead class="bg-gray-100">
        <tr>
            <th class="px-3 py-2">
                <input type="checkbox" wire:model="selectAll">
            </th>
            <th class="px-3 py-2 text-left">Cache Type</th>
            <th class="px-3 py-2 text-left">Description</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($cacheTypes as $type)
            <tr class="border-b">
                {{-- отладка --}}
                <td>
                    {{ gettype($type['key']) }}: {{ $type['key'] }}
                    <input type="checkbox" wire:model="selected" value="{{ $type['key'] }}">
                </td>
                <td class="px-3 py-2 font-semibold text-gray-800">{{ $type['label'] }}</td>
                <td class="px-3 py-2 text-gray-600">{{ $type['description'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
