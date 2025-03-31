<aside class="md:col-span-1">
    <nav class="bg-white shadow rounded-lg p-4 space-y-2">
        <a href="{{ route('profile.dashboard') }}"
           class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('profile.dashboard') ? 'bg-gray-100 font-medium text-gray-900' : '' }}">
            Dashboard
        </a>
        <a href="{{ route('profile.account-information.edit') }}"
           class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('profile.account-information.*') ? 'bg-gray-100 font-medium text-gray-900' : '' }}">
            Account Information
        </a>
        <a href="{{ route('profile.orders.index') }}"
           class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('profile.orders.*') ? 'bg-gray-100 font-medium text-gray-900' : '' }}">
            My Orders
        </a>
    </nav>
</aside>
