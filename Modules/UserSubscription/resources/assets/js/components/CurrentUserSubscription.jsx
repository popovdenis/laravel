import React from 'react';
import { useUser } from '@modules/User/resources/assets/js/components/UserContext.jsx';

export default function CurrentUserSubscription(props) {
    const { subscriptionPlan } = useUser();

    return (
        <div className="w-full lg:w-1/2 md:mb-6 lg:mb-0 lg:pr-4">
            <div className="flex flex-col h-full sm:flex-row">
                <div className="grow flex flex-col md:justify-between">
                    <div>
                        <h5 className="mb-4 font-bold">Customer Subscription Plan</h5>
                        { subscriptionPlan?.name ? (
                            <p className="mt-1 text-green-600 font-extrabold text-xl">
                                { subscriptionPlan.name }
                            </p>
                        ) : (
                            <p>You aren't subscribed to our newsletter.</p>
                        )}
                    </div>
                    <a
                        href="my-subscription/change"
                        className="inline-flex items-center w-full mt-4 text-xm font-semibold hover:underline"
                        aria-label="Edit newsletters"
                    >
                        <span>Edit</span>
                    </a>
                </div>
            </div>
        </div>
    );
}
