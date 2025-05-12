import React, {useEffect, useState} from 'react';
import axios from "axios";

function MyOrders() {
    const [orders, setOrders] = useState(null)
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const init = async () => {
            try {
                const response = await axios.get('/profile/my-orders');
                setOrders(response.data.orders);
            } catch (error) {
                console.error('Error fetching patterns:', error);
            } finally {
                setLoading(false)
            }
        };
        init();
    }, [])

    if (loading) return <div>Loading...</div>
    if (!orders) return <div>Failed to load orders.</div>

    return (
        <div className="md:col-span-3">
            <div className="bg-white shadow sm:rounded-lg p-6">
                {orders.length === 0 ? (
                    <p className="text-gray-500">You have no orders yet.</p>
                ) : (
                    <table className="w-full text-justify">
                        <thead>
                        <tr className="border-b-2 border-gray-800 font-black uppercase text-sm">
                            <th className="py-2">Order Id</th>
                            <th className="py-2">Date</th>
                            <th className="py-2">Status</th>
                            <th className="py-2"></th>
                        </tr>
                        </thead>
                        <tbody>
                        {orders.map((order) => (
                            <tr key={order.id} className="border-b border-gray-400 text-sm">
                                <td className="py-4 font-bold">{order.id}</td>
                                <td className="py-4">
                                    {new Date(order.created_at).toLocaleDateString('en-GB', {
                                        day: '2-digit',
                                        month: 'short',
                                        year: 'numeric'
                                    })}
                                </td>
                                <td className="py-4 capitalize">{order.status.label}</td>
                                <td className="py-4">
                                    <a href={`/profile/my-orders/${order.id}`} className="underline hover:no-underline">
                                        View
                                    </a>
                                </td>
                            </tr>
                        ))}
                        </tbody>
                    </table>
                )}
            </div>
        </div>
    )
}

export default MyOrders
