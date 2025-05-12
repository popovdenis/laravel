import React, {useEffect, useRef, useState} from 'react'
import { createRoot } from 'react-dom/client'
import Sidebar from '@modules/User/resources/assets/js/components/Sidebar';
import axios from "axios";

function SubscriptionsList() {
    const [loading, setLoading] = useState(false);
    const [isSubscribed, setIsSubscribed] = useState(false);
    const [activePlan, setActivePlan] = useState(null);
    const [plans, setPlans] = useState([]);
    const hasFetched = useRef(false);
    const [modalOpen, setModalOpen] = useState(false)
    const [selectedPlan, setSelectedPlan] = useState({ id: '', name: '' })

    const openModal = (plan) => {
        setSelectedPlan(plan)
        setModalOpen(true)
    };

    useEffect(() => {
        if (!hasFetched.current) {
            hasFetched.current = true;
            const initDashboard = async () => {
                try {
                    setLoading(true);
                    const response = await axios.get('/subscription-plan/list');
                    setIsSubscribed(response.data.isSubscribed);
                    setActivePlan(response.data.activePlan);
                    setPlans(response.data.plans);
                    // setEntities(entities.filter((entity: any) => entity.id !== selectedEntity.id));
                } catch (error) {
                    console.error('Error:', error);
                } finally {
                    setLoading(false);
                }
            };
            initDashboard();
        }
    }, [])

    if (loading) return <div>Loading...</div>

    return (
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div><Sidebar/></div>

            <div className="md:col-span-3 space-y-6">
                <h4 className="text-blue-400 text-xl font-bold">Subscription Plans</h4>

                <div className="flex flex-wrap justify-between bg-white rounded-2xl px-4 py-6 lg:px-6 mb-6 lg:mb-10">
                    <div className="w-full lg:w-1/2">
                        <div className="flex flex-col h-full sm:flex-row">
                            <div className="grow flex flex-col md:justify-between">
                                <div>
                                    <h3 className="text-lg font-semibold text-blue-900">Current Plan</h3>
                                    <div className="p-4 border rounded bg-gray-100 mt-2">
                                        {isSubscribed && activePlan ? activePlan.name : 'No active plan'}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="bg-white shadow sm:rounded-lg p-6">
                    <h3 className="text-lg font-semibold mb-8">Subscription Plans</h3>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {plans.map(plan => (
                            <div key={plan.id}
                                 className="border rounded-lg p-6 bg-white shadow hover:shadow-lg transition">
                                <h4 className="text-xl font-bold mb-2">{plan.name}</h4>
                                <div>
                                    <span className="text-sm text-gray-500 mr-2">Price:</span>
                                    <span className="text-sm font-bold">{Number(plan.price).toFixed(2)}</span>
                                </div>
                                <div>
                                    <span className="text-sm text-gray-500 mr-2">Credits:</span>
                                    <span className="text-sm font-bold">{plan.credits}</span>
                                </div>
                                <p className="text-gray-600 mb-4">{plan.description}</p>

                                <button
                                    type="button"
                                    onClick={() => openModal(plan)}
                                    className="block w-full text-center font-bold py-2 rounded border transition btn-primary"
                                >
                                    Choose
                                </button>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Modal */}
                {modalOpen && (
                    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div className="bg-white p-6 rounded shadow-lg max-w-sm w-full">
                            <h2 className="text-lg font-bold mb-4">Confirm Plan Change</h2>
                            <p className="mb-4">
                                Are you sure you want to switch to the <strong>{selectedPlan.name}</strong> plan?
                            </p>
                            <form method="POST" action="/my-subscription/change">
                                <input type="hidden" name="_token"
                                       value={document.querySelector('meta[name="csrf-token"]').content}/>
                                <input type="hidden" name="plan_id" value={selectedPlan.id}/>
                                <div className="flex space-x-4">
                                    <button type="button" onClick={() => setModalOpen(false)}
                                            className="px-4 py-2 btn-secondary rounded w-full">
                                        Cancel
                                    </button>
                                    <button type="submit" className="px-4 py-2 btn-primary rounded w-full">
                                        Yes, Confirm
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                )}
            </div>
        </div>
    )
}

const el = document.getElementById('subscription-list')
if (el) {
    createRoot(el).render(<SubscriptionsList/>);
}
