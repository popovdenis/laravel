import { useUser } from './UserContext.jsx';
import { useTab } from './TabContext';

export default function ContactInformation() {
    const { user } = useUser();
    const {setTab} = useTab();

    return (
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
                    <button
                        type="button"
                        onClick={() => setTab('account')}
                        className="inline-flex items-center w-full mt-4 text-sm font-semibold hover:underline"
                        aria-label="Edit contact information"
                    >
                        <span>Edit</span>
                    </button>
                </div>
            </div>
        </div>
    )
}
