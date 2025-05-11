import React, { useEffect, useRef, useState } from 'react'
import { loadStripe } from '@stripe/stripe-js'
import { Elements } from '@stripe/react-stripe-js'
import axios from 'axios'
import StripeCardForm from './StripeCardForm'

const stripePromise = loadStripe(import.meta.env.VITE_STRIPE_PUBLIC_KEY)

export default function StripeCard() {
    const [props, setProps] = useState(null)
    const [loading, setLoading] = useState(true);
    const hasFetched = useRef(false);

    useEffect(() => {
        if (!hasFetched.current) {
            hasFetched.current = true;
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
