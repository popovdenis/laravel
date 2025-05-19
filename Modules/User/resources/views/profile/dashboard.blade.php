<x-theme::app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Dashboard') }}</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Sidebar -->
            @include('user::profile.partials.sidebar')

            <!-- Main Content -->
            <div class="md:col-span-3">
                <div class="md:col-span-3 space-y-6">
                    <!-- Update Profile Information -->
                    @include('user::profile.partials.my-credits')
                    @include('user::profile.partials.account-information')
                    <x-stripecard::stripe-card />
                </div>
            </div>
        </div>
    </div>
</x-theme::app-layout>
