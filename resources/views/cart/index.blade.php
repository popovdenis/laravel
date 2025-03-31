<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Shopping Cart</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            @if (session('success'))
                <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif

            @if ($cart->items->isEmpty())
                <p class="text-gray-500">There is no courses in the cart.</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach ($cart->items as $item)
                        <li class="py-4 flex justify-between items-center">
                            <div>
                                <div class="font-medium">{{ $item->itemable->title ?? 'Курс' }}</div>
                                <div class="text-sm text-gray-500">QTY: {{ $item->quantity }}</div>
                            </div>
                            <form method="POST" action="{{ route('cart.remove', $item) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline text-sm">Remove</button>
                            </form>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-6 text-right">
                    <form method="POST" action="{{ route('orders.store') }}">
                        @csrf
                        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Place Order
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
