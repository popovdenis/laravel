<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Change Subscription Plan') }}</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div id="subscription-list"></div>
    </div>
    @vite('Modules/Subscription/resources/assets/js/components/SubscriptionsList.jsx');

        <!-- Sidebar -->
{{--        @include('user::profile.partials.sidebar')--}}

        <!-- Main Content -->

{{--    </div>--}}
</x-app-layout>
