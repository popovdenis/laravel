import React, { useContext, useState } from 'react';
import axios from 'axios';
import { useUser } from './UserContext.jsx';

const UpdateProfileForm = () => {
    const { user } = useUser();
    const [firstname, setFirstname] = useState(user?.firstname || '');
    const [lastname, setLastname] = useState(user?.lastname || '');
    const [email, setEmail] = useState(user?.email || '');
    const [status, setStatus] = useState(null);
    const [errors, setErrors] = useState({});

    const handleSubmit = async (e) => {
        e.preventDefault();
        setStatus(null);
        setErrors({});

        try {
            await axios.patch('/profile/account-information', { name, email });
            setStatus('profile-updated');
        } catch (error) {
            if (error.response?.data?.errors) {
                setErrors(error.response.data.errors);
            }
        }
    };

    const resendVerification = async () => {
        try {
            await axios.post('/email/verification-notification');
            setStatus('verification-link-sent');
        } catch (_) {
            //
        }
    };

    return (
        <section className="bg-white shadow sm:rounded-lg p-6">
            <header>
                <h2 className="text-lg font-medium text-gray-900">
                    Customer Account Information
                </h2>
                <p className="mt-1 text-sm text-gray-600">
                    Update your account's information and email address.
                </p>
            </header>

            <form onSubmit={handleSubmit} className="mt-6 space-y-6">
                <div>
                    <label htmlFor="name" className="block text-sm font-medium text-gray-700">Name</label>
                    <input
                        id="firstname"
                        name="firstname"
                        type="text"
                        className="mt-1 block w-full rounded border-gray-300 shadow-sm"
                        value={firstname}
                        onChange={(e) => setFirstname(e.target.value)}
                        required
                        autoFocus
                    />
                    {errors.firstname && <p className="text-sm text-red-600 mt-1">{errors.firstname[0]}</p>}
                </div>

                <div>
                    <label htmlFor="name" className="block text-sm font-medium text-gray-700">Name</label>
                    <input
                        id="lastname"
                        name="lastname"
                        type="text"
                        className="mt-1 block w-full rounded border-gray-300 shadow-sm"
                        value={lastname}
                        onChange={(e) => setLastname(e.target.value)}
                        required
                        autoFocus
                    />
                    {errors.lastname && <p className="text-sm text-red-600 mt-1">{errors.lastname[0]}</p>}
                </div>

                <div>
                    <label htmlFor="email" className="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        className="mt-1 block w-full rounded border-gray-300 shadow-sm"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        required
                    />
                    {errors.email && <p className="text-sm text-red-600 mt-1">{errors.email[0]}</p>}

                    {user?.must_verify_email && !user?.email_verified && (
                        <div className="mt-2 text-sm text-gray-700">
                            Your email address is unverified.{' '}
                            <button
                                type="button"
                                onClick={resendVerification}
                                className="underline text-sm text-gray-600 hover:text-gray-900"
                            >
                                Click here to re-send the verification email.
                            </button>
                            {status === 'verification-link-sent' && (
                                <p className="mt-2 font-medium text-sm text-green-600">
                                    A new verification link has been sent to your email address.
                                </p>
                            )}
                        </div>
                    )}
                </div>

                <div className="flex items-center gap-4">
                    <button
                        type="submit"
                        className="btn btn-primary"
                    >
                        Save
                    </button>

                    {status === 'profile-updated' && (
                        <p className="text-sm text-gray-600">Saved.</p>
                    )}
                </div>
            </form>
        </section>
    );
};

export default UpdateProfileForm;
