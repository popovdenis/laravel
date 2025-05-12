// DeleteAccountForm.jsx
import React, { useState } from 'react';
import axios from 'axios';

const DeleteAccountForm = () => {
    const [password, setPassword] = useState('');
    const [confirming, setConfirming] = useState(false);
    const [errors, setErrors] = useState({});

    const handleDelete = async (e) => {
        e.preventDefault();
        setErrors({});

        try {
            await axios.delete('/user', {
                data: { password },
            });
            // Redirect or update UI after successful deletion
        } catch (error) {
            if (error.response?.data?.errors) {
                setErrors(error.response.data.errors);
            }
        }
    };

    return (
        <section className="bg-white shadow sm:rounded-lg p-6">
            <header>
                <h2 className="text-lg font-medium text-gray-900">Delete Account</h2>
                <p className="mt-1 text-sm text-gray-600">
                    Once your account is deleted, all of its resources and data will be permanently deleted.
                    Before deleting your account, please download any data or information that you wish to retain.
                </p>
            </header>

            <button
                className="btn btn-cancel mt-6"
                onClick={() => setConfirming(true)}
            >
                Delete Account
            </button>

            {confirming && (
                <form onSubmit={handleDelete} className="mt-6 space-y-6">
                    <div>
                        <label htmlFor="password" className="block text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            className="mt-1 block w-full"
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            required
                        />
                        {errors.password && (
                            <p className="text-sm text-red-600 mt-1">{errors.password[0]}</p>
                        )}
                    </div>

                    <div className="flex items-center gap-4">
                        <button type="submit" className="btn btn-cancel">
                            Confirm Delete
                        </button>
                        <button
                            type="button"
                            className="btn btn-secondary"
                            onClick={() => setConfirming(false)}
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            )}
        </section>
    );
};

export default DeleteAccountForm;
