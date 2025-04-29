<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4" x-data="{ open: false, planId: '', planName: '' }" @confirm-change.window="open = true; planId = $event.detail.id; planName = $event.detail.name">
        <h2 class="text-2xl font-bold mb-6">Change Subscription Plan</h2>

        <div class="mb-8">
            <h3 class="text-lg font-semibold">Current Plan</h3>
            <div class="p-4 border rounded bg-gray-100 mt-2">
                {{ $user->subscription->plan->name ?? 'No active plan' }}
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($plans as $plan)
                <div class="border rounded-lg p-6 bg-white shadow hover:shadow-lg transition">
                    <h4 class="text-xl font-bold mb-2">{{ $plan->name }}</h4>
                    <p class="text-gray-600 mb-4">{{ $plan->description }}</p>
                    <div class="text-lg font-semibold mb-4">${{ number_format($plan->price, 2) }}</div>

                    <button
                        type="button"
                        @click="open = true; planId = '{{ $plan->id }}'; planName = '{{ $plan->name }}'"
                        class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded"
                    >
                        Choose
                    </button>
                </div>
            @endforeach
        </div>

        <!-- Modal -->
        <div x-show="open" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow-lg max-w-sm w-full">
                <h2 class="text-lg font-bold mb-4">Confirm Plan Change</h2>
                <p class="mb-4">Are you sure you want to switch to the <strong x-text="planName"></strong> plan?</p>

                <form method="POST" action="{{ route('subscription.store') }}">
                    @csrf
                    <input type="hidden" name="plan_id" :value="planId">
                    <div class="flex space-x-4">
                        <button type="button" @click="open = false" class="px-4 py-2 bg-gray-300 rounded w-full">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded w-full">Yes, Confirm</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
