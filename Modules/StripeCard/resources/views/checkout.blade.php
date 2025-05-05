<x-app-layout>
    <div class="max-w-xl mx-auto mt-12">
        <h2 class="text-xl font-bold mb-4">Purchase 1 Month Access</h2>
        <form action="{{ route('stripecard::checkout.session') }}" method="POST">
            @csrf
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">
                Pay with Stripe
            </button>
        </form>
    </div>
</x-app-layout>
