@if ($getRecord())
    <span>{{ __('Order #') }}
        <a href="{{ route('filament.admin.resources.orders.view', ['record' => $getRecord()->order?->increment_id]) }}"
            target="_blank"
            class="text-primary-600 underline"
        >
             {{ $getRecord()->order?->increment_id }}
        </a>
    </span>
@else
    <span class="text-gray-500">None</span>
@endif


