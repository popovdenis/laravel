<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Dashboard') }}</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div id="dashboard-root"></div>
    </div>

    @vite('Modules/User/resources/assets/js/pages/dashboard.jsx')
</x-app-layout>
