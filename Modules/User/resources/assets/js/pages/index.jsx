import React, {useEffect, useState} from 'react'
import { createRoot } from 'react-dom/client'
import { UserContext } from '../components/UserContext';
import Sidebar from '../components/Sidebar';
import { TabProvider, useTab } from '../components/TabContext';
import Dashboard from '../components/Dashboard';
import AccountInformation from '../components/AccountInformation';
import axios from "axios";

function Main() {
    const [user, setUser] = useState(null);
    const [creditsData, setCreditsData] = useState(null);
    const [subscriptionPlan, setSubscriptionPlan] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const initUser = async () => {
            try {
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
    }, []);

    if (loading) return <div>Loading...</div>;

    return (
        <TabProvider>
            <UserContext.Provider value={{ user, creditsData, subscriptionPlan }}>
                <Content />
            </UserContext.Provider>
        </TabProvider>
    );
}

function Content() {
    const { tab } = useTab();

    return (
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
            <Sidebar />
            <div className="md:col-span-3 space-y-6">
                {tab === 'dashboard' && <Dashboard />}
                {tab === 'account' && <AccountInformation />}
            </div>
        </div>
    );
}

const el = document.getElementById('dashboard-root')
if (el) {
    createRoot(el).render(<Main/>);
}
