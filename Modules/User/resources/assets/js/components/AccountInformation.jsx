import React from 'react'
import UpdateProfileForm from './UpdateProfileForm'
import UpdatePasswordForm from './UpdatePasswordForm'
import DeleteAccountForm from './DeleteAccountForm'

function AccountInformation() {
    return (
        <div className="md:col-span-3 space-y-6">
            <UpdateProfileForm />
            <UpdatePasswordForm />
            <DeleteAccountForm />
        </div>
    )
}

export default AccountInformation
