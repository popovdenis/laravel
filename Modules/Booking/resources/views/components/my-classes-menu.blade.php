<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link
            :href="route('account::classes.index')"
            :active="request()->routeIs('account::classes.index')"
            class="text-purple-700 hover:text-purple-600 font-semibold text-xl"
    >
        {{ __('My classes') }}
    </x-nav-link>
</div>