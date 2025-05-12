import React, {useEffect, useState} from 'react'
import { createRoot } from 'react-dom/client'
import { UserContext } from '../components/UserContext';
import Dashboard from '../components/Dashboard';
import AccountInformation from '../components/AccountInformation';
import axios from "axios";

function Main() {
    const [user, setUser] = useState(null);
    const [creditsData, setCreditsData] = useState(null);
    const [subscriptionPlan, setSubscriptionPlan] = useState(null);
    const [tab, setTab] = useState('dashboard');
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const initUser = async () => {
            try {
                setLoading(true);
                const response = await axios.get('/profile/dashboard');
                setUser(response.data.user);
                setCreditsData(response.data.creditsData);
                setSubscriptionPlan(response.data.subscriptionPlan);
            } catch (error) {
                console.error('Error:', error);
            } finally {
                setLoading(false);
            }
        };
        initUser();
    }, [])

    if (loading) return <div>Loading...</div>

    const tabs = [
        { name: 'dashboard', label: 'Dashboard' },
        { name: 'account', label: 'Account Information' },
        { name: 'orders', label: 'My Orders' },
        { name: 'schedule', label: 'My Schedule' },
        { name: 'courses', label: 'My Courses' },
    ]

    return (
        <UserContext.Provider value={{user, creditsData, subscriptionPlan}}>
            <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                {/*Sidebar*/}
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
                {tab === 'dashboard' && <Dashboard/>}
                {tab === 'account' && <AccountInformation/>}
                {/*{tab === 'orders' && <MyOrders/>}*/}
                {/*{tab === 'schedule' && <MySchedule/>}*/}
                {/*{tab === 'courses' && <MyCourses/>}*/}
            </div>
        </UserContext.Provider>
    );
}

const el = document.getElementById('dashboard-root')
if (el) {
    createRoot(el).render(<Main/>);
}
