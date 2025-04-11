<x-app-layout>
    <div class="max-w-xl mx-auto my-12 p-6 bg-white dark:bg-gray-800 rounded shadow">
        <form method="GET" action="{{ route('blog.search', app('request')->get('locale')) }}" class="space-y-4">
            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 text-center">Search for something in our blog:</h4>

            <div>
                <x-text-input
                    id="search"
                    name="s"
                    type="text"
                    class="block w-full"
                    placeholder="Search..."
                    value="{{ request('s') }}"
                />
            </div>

            <div class="text-center">
                <x-primary-button>
                    {{ __('Search') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
