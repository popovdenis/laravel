<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link
            :href="route('booking::index')"
            :active="request()->routeIs('booking::index')"
            class="text-purple-700 hover:text-purple-600 font-semibold text-xl"
    >
        {{ __('Book classes') }}
    </x-nav-link>
</div>