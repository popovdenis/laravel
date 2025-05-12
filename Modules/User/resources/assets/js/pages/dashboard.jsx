import React from 'react'
import { createRoot } from 'react-dom/client'
import Sidebar from '../components/Sidebar';
import MyCredits from '../components/MyCredits';
import AccountInformation from '../components/AccountInformation';
import StripeCard from '@modules/StripeCard/resources/assets/js/components/StripeCard'

function Dashboard() {
    return (
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div><Sidebar/></div>

            <div className="md:col-span-3">
                <div className="md:col-span-3 space-y-6">
                    <div className="bg-gray-100 py-2 px-4">
                        <h2 className="text-xl font-bold inline-block mb-4">My Credits</h2>
                        <MyCredits />
                    </div>
                    <div className="bg-gray-100 px-4">
                        <h2 className="text-xl font-bold inline-block mb-4">
                            Account Information
                        </h2>
                        <AccountInformation user={{firstname: 'John', lastname: 'Doe', email: 'john@example.com'}}/>
                    </div>
                    <div className="bg-gray-100 px-4 py-6">
                        <h2 className="text-xl font-bold inline-block mb-4">My Card</h2>
                        <StripeCard />
                    </div>
                </div>
            </div>
        </div>
    )
}

const el = document.getElementById('dashboard-root')
if (el) {
    createRoot(el).render(<Dashboard />)
}
