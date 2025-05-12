import React, {useEffect, useRef, useState} from 'react'
import { createRoot } from 'react-dom/client'
import Sidebar from '../components/Sidebar';
import MyCredits from '../components/MyCredits';
import AccountInformation from '../components/AccountInformation';
import StripeCard from '@modules/StripeCard/resources/assets/js/components/StripeCard'
import CurrentUserSubscription from "@modules/UserSubscription/resources/assets/js/components/CurrentUserSubscription.jsx";
import axios from "axios";

function Dashboard() {
    const [props, setProps] = useState(null)
    const [loading, setLoading] = useState(true);
    const hasFetched = useRef(false);

    useEffect(() => {
        if (!hasFetched.current) {
            hasFetched.current = true;
            const initDashboard = async () => {
                try {
                    await axios.get('/profile/dashboard')
                        .then(res => setProps(res.data))
                        .catch(err => console.error('StripeCard init error:', err))
                        .finally(() => setLoading(false))
                } catch (error) {
                    console.error('Error fetching patterns:', error);
                }
            };
            initDashboard();
        }
    }, [])

    if (loading) return <div>Loading Stripe card...</div>
    if (!props) return <div>Failed to load card info.</div>

    return (
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div><Sidebar /></div>

            <div className="md:col-span-3">
                <div className="md:col-span-3 space-y-6">
                    <div className="bg-gray-100 py-2 px-4">
                        <h2 className="text-xl font-bold inline-block mb-4">My Credits</h2>
                        <MyCredits creditsData={ props.creditsData } />
                    </div>
                    <div className="bg-gray-100 px-4">
                        <h2 className="text-xl font-bold inline-block mb-4">
                            Account Information
                        </h2>
                        <div className="flex flex-wrap justify-between bg-white rounded-2xl px-4 py-6 lg:px-6 mb-6 lg:mb-10">
                            <CurrentUserSubscription { ...props } />
                            <AccountInformation user={ props.user } />
                        </div>
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
    createRoot(el).render(<Dashboard />);
}
