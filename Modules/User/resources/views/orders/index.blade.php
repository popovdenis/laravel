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
                        <p class="text-gray-500">You have no orders yet.</p>
                    @else
                        <table class="w-full text-left text-sm">
                            <thead>
                            <tr class="border-b font-semibold text-gray-700">
                                <th class="py-2">#</th>
                                <th class="py-2">Status</th>
                                <th class="py-2">Date</th>
                                <th class="py-2">Items</th>
                                <th class="py-2 text-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($orders as $order)
                                <tr class="border-b">
                                    <td class="py-2">{{ $order->id }}</td>
                                    <td class="py-2 capitalize">{{ $order->status }}</td>
                                    <td class="py-2">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="py-2">{{ $order->items_count }}</td>
                                    <td class="py-2 text-right">
                                        <a href="{{ route('profile.orders.show', $order) }}" class="text-blue-600 hover:underline text-sm">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
