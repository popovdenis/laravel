<div class="bg-gray-100 px-4">
    <h2 class="text-xl font-bold inline-block mb-4">
        {{ __('Account Information') }}
    </h2>
    <div class="flex flex-wrap justify-between bg-white rounded-2xl px-4 py-6 lg:px-6 mb-6 lg:mb-10">
        <x-usersubscription::current-user-subscription />
        <div class="w-full lg:w-1/2">
            <div class="flex flex-col h-full sm:flex-row">
                <div class="grow flex flex-col md:justify-between">
                    <div>
                        <h5 class="mb-4 font-bold">
                            <span>{{ __('Contact Information') }}</span>
                        </h5>
                        <p>
                            {{ $user->firstname }} {{ $user->lastname }}<br>
                            {{ $user->email }}<br>
                        </p>
                    </div>
                    <a href="{{ route('profile.account-information.edit') }}"
                       class="inline-flex items-center w-full mt-4 text-xm font-semibold hover:underline"
                       aria-label="Edit contact information"
                    >
                        <span>{{ __('Edit') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
