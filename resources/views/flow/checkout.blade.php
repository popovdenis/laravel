<x-app-layout>
    <div class="max-w-3xl mx-auto py-10">
        <h2 class="text-2xl font-bold mb-6">Checkout</h2>

        <div class="border p-4 rounded shadow-sm mb-4">
            <p><strong>Teacher:</strong> {{ $teacher->name }}</p>
            <p><strong>Email:</strong> {{ $teacher->email }}</p>
        </div>

        <div class="space-y-3">
            @foreach ($slots as $slot)
                <div class="border p-3 rounded bg-gray-50">
                    <p><strong>Day:</strong> {{ ucfirst($slot->day) }}</p>
                    <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($slot->start)->format('H:i') }}
                        - {{ \Carbon\Carbon::parse($slot->end)->format('H:i') }}</p>
                </div>
            @endforeach
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" class="mt-6">
            @csrf
            <x-primary-button>Confirm and Pay</x-primary-button>
        </form>
    </div>
</x-app-layout>
