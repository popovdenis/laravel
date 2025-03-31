<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Customer Account</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Sidebar -->
            <aside class="md:col-span-1">
                <nav class="bg-white shadow rounded-lg p-4 space-y-2">
                    <a href="{{ route('dashboard') }}"
                       class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-gray-100 font-medium text-gray-900' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('profile.edit') }}"
                       class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('profile.edit') ? 'bg-gray-100 font-medium text-gray-900' : '' }}">
                        My Account
                    </a>
                    <a href="{{ route('profile.orders.index') }}"
                       class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('profile.orders.*') ? 'bg-gray-100 font-medium text-gray-900' : '' }}">
                        My Orders
                    </a>
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="md:col-span-3 space-y-6">
                <!-- Update Profile Information -->
                <section class="bg-white shadow sm:rounded-lg p-6">
                    @include('profile.partials.update-profile-information-form')
                </section>

                <!-- Update Password -->
                <section class="bg-white shadow sm:rounded-lg p-6">
                    @include('profile.partials.update-password-form')
                </section>

                <!-- Delete User -->
                <section class="bg-white shadow sm:rounded-lg p-6">
                    @include('profile.partials.delete-user-form')
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
