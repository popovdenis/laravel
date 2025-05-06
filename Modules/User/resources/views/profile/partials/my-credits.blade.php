<div class="bg-gray-100 py-2 px-4">
    <h2 class="text-xl font-bold inline-block mb-4">
        {{ __('My Credits') }}
    </h2>

    <div class="bg-white p-6 rounded shadow-sm flex items-center gap-6">
        <div class="text-gray-500">
            <svg class="inline-block ml-3 mr-3 w-12 h-12 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>

        <div>
            <div class="text-green-600 font-bold text-3xl">
                <x-user::credit-display />
            </div>
            <div class="font-semibold mt-1">{{ __('Current Balance')  }}</div>
            <p class="text-sm text-gray-600 mt-1">
                Your Credits may expire if there hasn’t been any transactions in the last 24 months.
            </p>
        </div>
    </div>
</div>
