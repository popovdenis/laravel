<x-app-layout>
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
                    <h4 class="text-blue-400 text-xl font-bold">{{ __('Account Information') }}</h4>

                    <div class="flex flex-wrap justify-between bg-white rounded-2xl px-4 py-6 lg:px-6 mb-6 lg:mb-10">
                        <div class="w-full lg:w-1/2 md:mb-6 lg:mb-0 lg:pr-4">
                            <div class="flex flex-col h-full sm:flex-row">
                                <div class="grow flex flex-col md:justify-between">
                                    <div>
                                        <h5 class="mb-4 text-blue-900 font-bold">{{ __('Customer Subscription Plan') }}</h5>
                                        @if($subscriptionPlan->name)
                                            <p class="mt-1">{{ $subscriptionPlan->name }}</p>
                                            <p class="mt-1">
                                                <span>{{ __('Credit Balance: ') }}</span>
                                                <span class="font-bold">{{ $user->credit_balance }}</span>
                                            </p>
                                        @else
                                            <p>{{ __('You aren\'t subscribed to our newsletter.') }}</p>
                                        @endif
                                    </div>
                                    <a href="{{ route('usersubscription::show') }}"
                                       class="inline-flex items-center w-full mt-4 text-xm font-bold text-blue-400 hover:underline"
                                       aria-label="Edit newsletters"
                                    >
                                        <span>{{ __('Edit') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="w-full lg:w-1/2">
                            <div class="flex flex-col h-full sm:flex-row">
                                <div class="grow flex flex-col md:justify-between">
                                    <div>
                                        <h5 class="mb-4 text-blue-900 font-bold">
                                            <span>{{ __('Contact Information') }}</span>
                                        </h5>
                                        <p>
                                            {{ $user->firstname }} {{ $user->lastname }}<br>
                                            {{ $user->email }}<br>
                                        </p>
                                    </div>
                                    <a href="{{ route('profile.account-information.edit') }}"
                                       class="inline-flex items-center w-full mt-4 text-xm font-bold text-blue-400 hover:underline"
                                       aria-label="Edit contact information"
                                    >
                                        <span>{{ __('Edit') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
