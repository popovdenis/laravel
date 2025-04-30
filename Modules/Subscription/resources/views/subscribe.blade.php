<x-app-layout>
    <div class="max-w-md mx-auto py-8">
        <form action="{{ route('subscription') }}" method="POST">
            @csrf
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">
                Subscribe
            </button>
        </form>
    </div>
</x-app-layout>
