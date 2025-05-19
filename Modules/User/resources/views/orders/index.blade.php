<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">My Orders</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @include('user::profile.partials.sidebar')

            <div class="md:col-span-3">
                <div class="bg-white shadow sm:rounded-lg p-6">
                    @if ($orders->isEmpty())
                        <p class="text-gray-500">{{ __('You have no orders yet.')  }}</p>
                    @else
                        <table class="w-full text-justify">
                            <thead>
                            <tr class="border-b-2 border-gray-800 font-black uppercase text-sm">
                                <th class="py-2 ">{{ __('Order Id')  }}</th>
                                <th class="py-2">{{ __('Date')  }}</th>
                                <th class="py-2">{{ __('Status')  }}</th>
                                <th class="py-2"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($orders as $order)
                                <tr class="border-b border-gray-400 text-xm">
                                    <td class="py-4 font-bold">{{ $order->id }}</td>
                                    <td class="py-4">{{ $order->created_at->format('d/M/Y') }}</td>
                                    <td class="py-4 capitalize">{{ $order->status->label() }}</td>
                                    <td class="py-4">
                                        <a href="{{ route('profile.orders.show', $order) }}" class="underline hover:no-underline">
                                            {{ __('View')  }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
