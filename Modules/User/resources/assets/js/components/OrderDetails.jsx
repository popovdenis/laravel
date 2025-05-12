import React, {useEffect, useState} from 'react';
import axios from "axios";
import Sidebar from "./Sidebar.jsx";

function OrderDetails() {
    const [loading, setLoading] = useState(true);
    const [order, setOrder] = useState(null);
    const [statusLabel, setStatusLabel] = useState(null);
    const [isInvoiced, setIsInvoiced] = useState(false);
    const [invoiceUrl, setInvoiceUrl] = useState(null);
    const [planName, setPlanName] = useState(null);
    const [totalAmount, setTotalAmount] = useState(null);
    const [currency, setCurrency] = useState(null);
    const [tax, setTax] = useState(null);
    const [totalWithTax, setTotalWithTax] = useState(null);

    useEffect(() => {
        const init = async () => {
            try {
                const match = window.location.pathname.match(/\/profile\/my-orders\/(\d+)/)
                const orderId = match ? match[1] : null;

                const response = await axios.get(`/profile/my-orders/order/${orderId}`);
                setOrder(response.data.order);
                setStatusLabel(response.data.statusLabel);
                setIsInvoiced(response.data.isInvoiced);
                setInvoiceUrl(response.data.invoiceUrl);
                setPlanName(response.data.planName);
                setTotalAmount(response.data.totalAmount);
                setCurrency(response.data.currency);
                setTax(response.data.tax);
                setTotalWithTax(response.data.totalWithTax);
            } catch (error) {
                console.error('Error fetching patterns:', error);
            } finally {
                setLoading(false)
            }
        };
        init();
    }, [])

    if (loading) return <div>Loading...</div>
    if (!order) return <div>Failed to load order.</div>

    return (
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
            <Sidebar/>
            <div className="md:col-span-3 space-y-6">
                <div className="mb-4 md:flex justify-between items-center">
                    <div className="block-title title-decor w-full">
                        <span className="text-2xl block">Order # {order.id}</span>
                    </div>

                    <div className="flex flex-col md:flex-row gap-2 mt-4 md:mt-0 md:items-center">
                    <span
                        className="order-status inline-block px-5 py-2 border border-gray-300 bg-white rounded text-sm">
                        {statusLabel}
                    </span>

                        {isInvoiced && (
                            <a
                                href={invoiceUrl}
                                target="_self"
                                className="block md:inline-flex items-center gap-2 min-w-[180px] justify-center px-5 py-2 border border-gray-300 bg-white rounded text-sm font-medium hover:bg-gray-300 transition"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" className="w-4 h-4" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2}
                                          d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/>
                                </svg>
                                Download Invoice
                            </a>
                        )}
                    </div>
                </div>

                <div className="bg-white shadow sm:rounded-lg p-1 space-y-1">
                    <div className="rounded-md">
                        <h2 className="bg-gray-100 my-0 py-2 px-2 text-lg font-semibold mb-4">Order</h2>
                        <table className="w-full text-sm bg-white rounded">
                            <thead className="text-gray-800">
                            <tr className="border-b border-blue-500">
                                <th className="text-left px-4 py-2 uppercase font-bold">Subscription Plan</th>
                                <th className="text-left px-4 py-2 uppercase font-bold">Price</th>
                                <th className="text-left px-4 py-2 uppercase font-bold">Subtotal</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr className="border-b border-gray-400">
                                <td className="px-4 py-3 font-semibold text-base">{planName}</td>
                                <td className="px-4 py-3 align-top">{totalAmount}</td>
                                <td className="px-4 py-3 align-top font-bold">{totalAmount}</td>
                            </tr>
                            </tbody>
                        </table>

                        <div className="mt-4 mb-2 w-full flex justify-end text-right">
                            <table>
                                <tbody>
                                <tr>
                                    <td className="px-2">Subtotal</td>
                                    <td className="px-2">{totalAmount}</td>
                                </tr>
                                <tr>
                                    <td className="px-2">AU-GST-10 (10%)</td>
                                    <td className="px-2">{tax}</td>
                                </tr>
                                <tr className="font-bold">
                                    <td className="px-2">Order Total</td>
                                    <td className="px-2">{totalWithTax}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}

export default OrderDetails
