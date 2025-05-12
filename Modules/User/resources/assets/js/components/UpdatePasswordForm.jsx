// UpdatePasswordForm.jsx
import React, { useState } from 'react';
import axios from 'axios';

const UpdatePasswordForm = () => {
    const [currentPassword, setCurrentPassword] = useState('');
    const [password, setPassword] = useState('');
    const [passwordConfirmation, setPasswordConfirmation] = useState('');
    const [status, setStatus] = useState(null);
    const [errors, setErrors] = useState({});

    const handleSubmit = async (e) => {
        e.preventDefault();
        setStatus(null);
        setErrors({});

        try {
            await axios.put('/user/password', {
                current_password: currentPassword,
                password,
                password_confirmation: passwordConfirmation,
            });
            setStatus('password-updated');
            setCurrentPassword('');
            setPassword('');
            setPasswordConfirmation('');
        } catch (error) {
            if (error.response?.data?.errors) {
                setErrors(error.response.data.errors);
            }
        }
    };

    return (
        <section className="bg-white shadow sm:rounded-lg p-6">
            <header>
                <h2 className="text-lg font-medium text-gray-900">Update Password</h2>
                <p className="mt-1 text-sm text-gray-600">
                    Ensure your account is using a long, random password to stay secure.
                </p>
            </header>

            <form onSubmit={handleSubmit} className="mt-6 space-y-6">
                <div>
                    <label htmlFor="current_password" className="block text-sm font-medium text-gray-700">
                        Current Password
                    </label>
                    <input
                        id="current_password"
                        name="current_password"
                        type="password"
                        className="mt-1 block w-full"
                        value={currentPassword}
                        onChange={(e) => setCurrentPassword(e.target.value)}
                        required
                    />
                    {errors.current_password && (
                        <p className="text-sm text-red-600 mt-1">{errors.current_password[0]}</p>
                    )}
                </div>

                <div>
                    <label htmlFor="password" className="block text-sm font-medium text-gray-700">
                        New Password
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

                <div>
                    <label htmlFor="password_confirmation" className="block text-sm font-medium text-gray-700">
                        Confirm Password
                    </label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        className="mt-1 block w-full"
                        value={passwordConfirmation}
                        onChange={(e) => setPasswordConfirmation(e.target.value)}
                        required
                    />
                    {errors.password_confirmation && (
                        <p className="text-sm text-red-600 mt-1">{errors.password_confirmation[0]}</p>
                    )}
                </div>

                <div className="flex items-center gap-4">
                    <button type="submit" className="btn btn-primary">
                        Save
                    </button>
                    {status === 'password-updated' && (
                        <p className="text-sm text-gray-600">Saved.</p>
                    )}
                </div>
            </form>
        </section>
    );
};

export default UpdatePasswordForm;
