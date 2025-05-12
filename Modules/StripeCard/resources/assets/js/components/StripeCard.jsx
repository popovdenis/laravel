import React, { useEffect, useRef, useState } from 'react'
import { loadStripe } from '@stripe/stripe-js'
import { Elements } from '@stripe/react-stripe-js'
import axios from 'axios'
import StripeCardForm from './StripeCardForm'

const stripePromise = loadStripe(import.meta.env.VITE_STRIPE_PUBLIC_KEY)

export default function StripeCard({ props: externalProps }) {
    const [props, setProps] = useState(externalProps || null)
    const [loading, setLoading] = useState(!externalProps);

    useEffect(() => {
        const initStripeCard = async () => {
            try {
                await axios.get('/stripecard/init')
                    .then(res => setProps(res.data))
                    .catch(err => console.error('StripeCard init error:', err))
                    .finally(() => setLoading(false))
            } catch (error) {
                console.error('Error fetching patterns:', error);
            }
        };
        if (!externalProps) {
            initStripeCard();
        }
    }, [])

    if (loading) return <div>Loading Stripe card...</div>
    if (!props) return <div>Failed to load card info.</div>

    return (
        <Elements stripe={stripePromise}>
            <StripeCardForm {...props} />
        </Elements>
    )
}
