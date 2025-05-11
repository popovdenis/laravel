import React, { useState } from 'react'
import { CardElement, useStripe, useElements } from '@stripe/react-stripe-js'

function StripeCardForm(props) {
    const [showForm, setShowForm] = useState(!props.hasCard);
    const [error, setError] = useState('');
    const [isProcessing, setIsProcessing] = useState(false);
    const [showModal, setShowModal] = useState(false);

    const stripe = useStripe();
    const elements = useElements();

    const handleSubmit = async (e) => {
        e.preventDefault()
        if (!stripe || !elements) return

        setIsProcessing(true)
        const result = await stripe.confirmCardSetup( props.clientSecret, {
            payment_method: {
                card: elements.getElement(CardElement)
            }
        })

        if (result.error) {
            setError(result.error.message)
            setIsProcessing(false)
        } else {
            document.getElementById('payment-method').value = result.setupIntent.payment_method
            document.getElementById('card-form').submit()
        }
    }

    return (
        <div className="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
            {!showForm && props.hasCard && (
                <div className="flex flex-col justify-between rounded">
                    <div className="text-gray-700">
                        <p className="text-sm font-medium">
                            <strong>{props.card.brand?.toUpperCase()} **** **** **** {props.card.last4}</strong>
                        </p>
                        <p className="text-sm text-gray-500">Expires {props.card.exp_month}/{props.card.exp_year}</p>
                    </div>

                    <div className="flex items-center space-x-2 mt-4">
                        <button
                            type="button"
                            className="px-4 py-2 btn btn-secondary rounded"
                            onClick={() => setShowForm(true)}
                        >
                            Change Card
                        </button>

                        <form method="POST" action={props.detachUrl}>
                            <input type="hidden" name="_method" value="DELETE"/>
                            <input type="hidden" name="_token"
                                   value={document.querySelector('meta[name="csrf-token"]').content}/>
                            <button type="button" className="btn btn-cancel rounded" onClick={() => setShowModal(true)}>
                                Delete Card
                            </button>
                        </form>
                    </div>
                </div>
            )}

            {showForm && (
                <form method="POST" action={props.attachUrl} id="card-form" onSubmit={handleSubmit}>
                    <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]').content}/>
                    <input type="hidden" name="payment_method" id="payment-method"/>

                    <div className="mb-4">
                        <label className="block text-lg font-medium text-gray-700 mb-2">Card Details</label>
                        <div className="p-3 border border-gray-300 rounded bg-gray-50">
                            <CardElement/>
                        </div>
                        {error && <p className="text-sm text-red-600 mt-2">{error}</p>}
                    </div>

                    <div className="flex items-center justify-between mt-6">
                        <button type="submit" disabled={isProcessing}
                                className="px-5 py-2 text-sm bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                            {isProcessing ? 'Saving...' : 'Save Card'}
                        </button>

                        {props.hasCard && (
                            <button type="button" className="text-sm text-gray-500 hover:underline"
                                    onClick={() => setShowForm(false)}>
                                Cancel
                            </button>
                        )}
                    </div>
                </form>
            )}
            {showModal && (
                <div className="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                    <div className="bg-white rounded-lg shadow-lg max-w-sm w-full p-6">
                        <h3 className="text-lg font-semibold text-gray-800 mb-4">Confirm deletion</h3>
                        <p className="text-sm text-gray-600 mb-6">
                            Are you sure you want to delete your saved card?
                        </p>
                        <div className="flex justify-end space-x-3">
                            <form method="POST" action={props.detachUrl}>
                                <input type="hidden" name="_token" value={document.querySelector('meta[name=csrf-token]').content} />
                                <input type="hidden" name="_method" value="DELETE" />
                                <button type="submit" className="btn btn-cancel">
                                    Delete
                                </button>
                            </form>
                            <button onClick={() => setShowModal(false)} className="btn btn-secondary">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    )
}

export default StripeCardForm;
