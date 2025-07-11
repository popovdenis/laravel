<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('profile.dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <x-booking::my-classes-menu />
                <x-booking::book-classes-menu />
            </div>

            @auth
                <div class="hidden sm:flex sm:items-center sm:space-x-6">
                    <!-- User Dropdown -->
                    <x-user::preferred-time-dropdown />
                    <x-user::credit-display-header size="lg" />
                    <x-user::account-menu-dropdown />
                </div>
            @endauth

            @guest
                <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">Sign in</a>
                    <span>/</span>
                    <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-gray-900">Sign up</a>
                </div>
            @endguest

            <!-- Hamburger (mobile menu toggle) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': ! open, 'inline-flex': open }" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (mobile) -->
    <div :class="{ 'block': open, 'hidden': ! open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('profile.dashboard')" :active="request()->routeIs('profile.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ auth()->user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.dashboard')">
                        {{ __('Customer Account') }}
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                               onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth

        @guest
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="mt-3 space-y-1 px-4">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Log in') }}
                    </x-responsive-nav-link>
                    @if (request()->routeIs('register'))
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('Register') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            </div>
        @endguest
    </div>
</nav>
