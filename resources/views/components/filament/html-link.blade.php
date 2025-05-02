@if ($getRecord())
    <span>{{ __('Order #') }}
        <a href="{{ route('filament.admin.resources.orders.view', ['record' => $getRecord()->order?->id]) }}"
            target="_blank"
            class="text-primary-600 underline"
        >
             {{ substr('0000000', 0, -strlen((string) $getRecord()->order?->id)) . $getRecord()->order?->id }}
        </a>
    </span>
@else
    <span class="text-gray-500">None</span>
@endif


