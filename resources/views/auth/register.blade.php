<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('Create an Account') }}</h1>
        <p class="mt-2 flex items-center space-x-2">
            <span class="text-sm text-gray-600">{{ __('Fill the form to create your account') }}</span>
            <span class="w-7 h-0.5 bg-red-500"></span>
            <span class="text-sm text-gray-600">{{ __('Required fields are marked with') }} <span class="text-red-500">*</span></span>
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Subscription Plan -->
        <div>
            <x-input-label for="subscription_plan_id">
                {{ __('Subscription Plan') }} <span class="text-red-500">*</span>
            </x-input-label>
            <select id="subscription_plan_id" name="subscription_plan_id" required class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">{{ __('Select a plan') }}</option>
                @foreach ($subscriptionPlans as $id => $name)
                    <option value="{{ $id }}" {{ old('subscription_plan_id') == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('subscription_plan_id')" class="mt-2" />
        </div>

        <!-- First Name -->
        <div class="mt-4">
            <x-input-label for="name">
                {{ __('First Name') }} <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname')" required autofocus autocomplete="firstname" />
            <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="mt-4">
            <x-input-label for="name">
                {{ __('Last Name') }} <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname')" required autofocus autocomplete="lastname" />
            <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email">
                {{ __('Email') }} <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password">
                {{ __('Password') }} <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation">
                {{ __('Confirm Password') }} <span class="text-red-500">*</span>
            </x-input-label>
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a href="{{ route('login') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
