<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">
                Order #{{ $order->id }}
            </h2>

            <a href="{{ route('profile.orders.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:underline">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 19l-7-7 7-7" />
                </svg>
                Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Sidebar -->
            @include('user::profile.partials.sidebar')

            <!-- Main Content -->
            <div class="md:col-span-3 space-y-6">
                <div class="bg-white shadow sm:rounded-lg p-6 space-y-6">
                    <x-slot name="header">
                        <h2 class="text-lg font-semibold">{{ __('Order Details') }}</h2>
                    </x-slot>
                    <div>
                        <p class="py-2"><span class="font-semibold">{{ __('Order ID: ') }}</span> {{ $order->id }}</p>
                        <p class="py-2"><span class="font-semibold py-4">{{ __('Status: ') }}</span> {{ ucfirst($order->status->value) }}</p>
                        <p class="py-2"><span class="font-semibold">{{ __('Amount: ') }}</span> {{ number_format($order->total_amount, 2) }} {{ strtoupper($order->currency ?? 'USD') }}</p>
                        <p class="py-2"><span class="font-semibold">{{ __('Created At: ') }}</span> {{ $order->created_at->format('M d, Y H:i') }}</p>
                        @if($order->purchasable instanceof \Modules\Subscription\Models\Subscription)
                            <p class="py-2"><span class="font-semibold">{{ __('Type: ') }}</span>{{ __('Subscription') }}</p>
                            <p class="py-2"><span class="font-semibold">{{ __('Plan: ') }}</span>{{ $order->purchasable->plan->name ?? 'N/A' }}</p>
                        @elseif($order->purchasable instanceof \Modules\Booking\Models\Booking)
                            <p class="py-2"><span class="font-semibold">{{ __('Type: ') }}</span>{{ __('Booking') }}</p>
                            <p class="py-2"><span class="font-semibold">{{ __('Slot: ') }}</span>{{ $order->purchasable->timeslot->start ?? 'N/A' }}</p>
                        @endif
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="text-lg font-semibold">
                            Total: ${{ number_format($order->total_amount, 2) }}
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
        </div>
    </div>
</x-app-layout>
