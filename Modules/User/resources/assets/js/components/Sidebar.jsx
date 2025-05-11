import React from 'react'

export default function Sidebar() {
    const routes = [
        { href: '/profile', label: 'Dashboard', active: location.pathname === '/profile' },
        { href: '/profile/account-information', label: 'Account Information', active: location.pathname.startsWith('/profile/account-information') },
        { href: '/profile/my-orders', label: 'My Orders', active: location.pathname.startsWith('/profile/orders') },
        { href: '/profile/schedule', label: 'My Schedule', active: location.pathname.startsWith('/profile/schedule') },
        { href: '/profile/my-courses', label: 'My Courses', active: location.pathname.startsWith('/profile/courses') },
    ]

    return (
        <aside className="md:col-span-1">
            <nav className="bg-white shadow rounded-lg p-4 space-y-2">
                {routes.map((route) => (
                    <a key={route.href} href={route.href}
                       className={`block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 ${
                           route.active ? 'bg-gray-100 font-medium text-gray-900' : ''
                       }`}
                    >
                        {route.label}
                    </a>
                ))}
            </nav>
        </aside>
    )
}
