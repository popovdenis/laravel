<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Customer Account</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Sidebar -->
            @include('profile.partials.sidebar')

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
