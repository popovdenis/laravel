import React from 'react'
import { useTab } from './TabContext'

const tabs = [
    { name: 'dashboard', label: 'Dashboard' },
    { name: 'account', label: 'Account Information' },
    { name: 'orders', label: 'My Orders' },
    { name: 'schedule', label: 'My Schedule' },
    { name: 'courses', label: 'My Courses' },
]

const Sidebar = () => {
    const { tab, setTab } = useTab()

    return (
        <aside className="md:col-span-1">
            <nav className="bg-white shadow rounded-lg p-4 space-y-2">
                {tabs.map((tabItem) => (
                    <a key={tabItem.name}
                       onClick={() => setTab(tabItem.name)}
                       className={`block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 ${
                           tab === tabItem.name ? 'bg-gray-100 font-medium text-gray-900' : ''
                       }`}
                    >
                        {tabItem.label}
                    </a>
                ))}
            </nav>
        </aside>
    )
}

export default Sidebar
