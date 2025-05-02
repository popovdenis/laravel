@if ($getRecord())
    <span>{{ __('Order #') }}
        <a href="{{ route('filament.admin.resources.orders.view', ['record' => $getRecord()->id]) }}"
            target="_blank"
            class="text-primary-600 underline"
        >
             {{ substr('0000000', 0, -strlen((string) $getRecord()->id)) . $getRecord()->id }}
        </a>
    </span>
@else
    <span class="text-gray-500">None</span>
@endif


