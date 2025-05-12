import React from 'react'
import { createRoot } from 'react-dom/client'
import { TabProvider } from '../components/TabContext';
import OrderDetails from "../components/OrderDetails.jsx";

const el = document.getElementById('order-details')
if (el) {
    createRoot(el).render(
        <TabProvider>
            <OrderDetails />
        </TabProvider>
    );
}
