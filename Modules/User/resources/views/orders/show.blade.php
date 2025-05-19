<x-theme::app-layout>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Sidebar -->
            @include('user::profile.partials.sidebar')

            <!-- Main Content -->
            <div class="md:col-span-3 space-y-6">
                <div class="mb-4 md:flex justify-between items-center">
                    <div class="block-title title-decor w-full">
                        <span class="text-2xl block">{{ __('Order # :number', ['number' => $order->id]) }}</span>
                    </div>

                    <div class="flex flex-col md:flex-row gap-2 mt-4 md:mt-0 md:items-center">
                        <span class="order-status inline-block px-5 py-2 border border-gray-300 bg-white rounded text-sm">
                            {{ $order->status->label() }}
                        </span>
                        @if($order->isInvoiced())
                            <a href="{{ $order->invoice->pdf_url }}" target="_self"
                               class="block md:inline-flex items-center gap-2 min-w-[180px] justify-center px-5 py-2 border border-gray-300 bg-white rounded text-sm font-medium hover:bg-gray-300 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/>
                                </svg>
                                {{ __('Download Invoice') }}
                            </a>
                        @endif
                    </div>
                </div>
                <div class="bg-white shadow sm:rounded-lg p-1 space-y-1">
                    <div class="rounded-md">
                        <h2 class="bg-gray-100 my-0 py-2 px-2 text-lg font-semibold mb-4">{{ __('Order')  }}</h2>
                        <table class="w-full text-sm bg-white rounded">
                            <thead class="text-gray-800">
                            <tr class="border-b border-blue-500">
                                <th class="text-left px-4 py-2 uppercase font-bold">{{ __('Subscription Plan') }}</th>
                                <th class="text-left px-4 py-2 uppercase font-bold">{{ __('Price') }}</th>
                                <th class="text-left px-4 py-2 uppercase font-bold">{{ __('Subtotal') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="border-b border-gray-400">
                                <td class="px-4 py-3">
                                    @if($order->isInvoiced())
                                        <span class="font-semibold text-base">{{ $order->purchasable->plan->name }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 align-top">{{ $order->getFormattedPrice($order->total_amount) }}</td>
                                <td class="px-4 py-3 align-top font-bold">{{ $order->getFormattedPrice($order->total_amount) }}</td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="mt-4 mb-2 w-full flex justify-end text-right">
                            <table>
                                <tr><td class="px-2">{{ __('Subtotal') }}</td><td class="px-2">{{ $order->getFormattedPrice($order->total_amount) }}</td></tr>
                                <tr><td class="px-2">AU-GST-10 (10%)</td><td class="px-2">{{ $order->getFormattedPrice($order->total_amount * 0.1) }}</td></tr>
                                <tr class="font-bold"><td class="px-2">{{ __('Order Total')  }}</td><td class="px-2">{{ $order->getFormattedPrice($order->total_amount * 0.1 + $order->total_amount) }}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-theme::app-layout>
