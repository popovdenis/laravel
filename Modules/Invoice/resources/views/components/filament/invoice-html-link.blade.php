@if ($getRecord() && $getRecord()->invoice)
    <span>{{ __('Invoice #') }}
        <a href="{{ route('filament.admin.resources.invoices.view', ['record' => $getRecord()->invoice->id]) }}"
            target="_blank"
            class="text-primary-600 underline"
        >
             {{ substr('0000000', 0, -strlen((string) $getRecord()->invoice->id)) . $getRecord()->invoice->id }}
        </a>
    </span>
@else
    <span class="text-gray-500">{{ __('We couldn\'t find any records.') }}</span>
@endif
