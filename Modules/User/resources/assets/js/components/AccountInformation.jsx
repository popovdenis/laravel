import CurrentUserSubscription from '../components/CurrentUserSubscription'

export default function AccountInformation({ user }) {
    return (
        <div className="flex flex-wrap justify-between bg-white rounded-2xl px-4 py-6 lg:px-6 mb-6 lg:mb-10">
            <CurrentUserSubscription />
            <div className="w-full lg:w-1/2">
                <div className="flex flex-col h-full sm:flex-row">
                    <div className="grow flex flex-col md:justify-between">
                        <div>
                            <h5 className="mb-4 font-bold">
                                <span>Contact Information</span>
                            </h5>
                            <p>
                                {user.firstname} {user.lastname}<br/>
                                {user.email}<br/>
                            </p>
                        </div>
                        <a
                            href="/profile/account-information/edit"
                            className="inline-flex items-center w-full mt-4 text-sm font-semibold hover:underline"
                            aria-label="Edit contact information"
                        >
                            <span>Edit</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    )
}
