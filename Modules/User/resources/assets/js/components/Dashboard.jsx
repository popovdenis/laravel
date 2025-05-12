import React from 'react'
import MyCredits from "./MyCredits.jsx";
import CurrentUserSubscription from "@modules/UserSubscription/resources/assets/js/components/CurrentUserSubscription.jsx";
import ContactInformation from "./ContactInformation.jsx";
import StripeCard from '@modules/StripeCard/resources/assets/js/components/StripeCard'
import {useUser} from "./UserContext.jsx";

export default function Dashboard() {
    const { stripeCardProps } = useUser();

    return (
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
                    <div className="flex flex-wrap justify-between bg-white rounded-2xl px-4 py-6 lg:px-6 mb-6 lg:mb-10">
                        <CurrentUserSubscription />
                        <ContactInformation />
                    </div>
                </div>
                <div className="bg-gray-100 px-4 py-6">
                    <h2 className="text-xl font-bold inline-block mb-4">My Card</h2>
                    <StripeCard props={ stripeCardProps } />
                </div>
            </div>
        </div>
    )
}
