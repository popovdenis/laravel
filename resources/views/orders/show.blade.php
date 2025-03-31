<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">
                Order #{{ $order->id }}
            </h2>
            <a href="{{ route('orders.index') }}" class="text-sm text-blue-600 hover:underline">
                Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6 space-y-6">
            <div>
                <p><span class="font-semibold">Status:</span> {{ ucfirst($order->status) }}</p>
                <p><span class="font-semibold">Date:</span> {{ $order->created_at->format('M d, Y H:i') }}</p>
            </div>

            <div>
                <h3 class="text-lg font-medium mb-2">Items:</h3>
                <ul class="divide-y divide-gray-200">
                    @foreach ($order->items as $item)
                        <li class="py-3">
                            <div class="font-medium">{{ $item->itemable->title ?? 'Course' }}</div>
                            <div class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</div>
                            <div class="text-sm text-gray-500">Price: {{ $item->itemable->getFormattedPrice() }}</div>
                        </li>
                    @endforeach
                </ul>
            </div>

            @php
                $total = $order->items->sum(fn($i) => ($i->itemable->price ?? 0) * $i->quantity);
            @endphp

            <div class="flex justify-between items-center">
                <div class="text-lg font-semibold">
                    Total: ${{ number_format($total, 2) }}
                </div>

                <div class="space-x-3">
                    <a href="#" class="bg-gray-100 px-4 py-2 rounded hover:bg-gray-200 text-sm">
                        Download Invoice
                    </a>

                    @if ($order->status === 'pending')
                        <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                            Pay Now
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
