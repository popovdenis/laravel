<x-theme::app-layout>
    <div class="w-full lg:w-2/3 mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-12">My classes</h2>

        <!-- Scheduled / Past -->
        <div class="relative mb-8">
            <div class="flex space-x-6 text-lg font-medium">
                <a href="{{ route('account::classes.index', ['type' => 'scheduled']) }}"
                   class="{{ request('type', 'scheduled') === 'scheduled' ? 'border-b-2 border-purple-600 font-semibold text-purple-700' : 'text-gray-600 hover:text-purple-600' }} pb-3">
                    Scheduled
                </a>
                <a href="{{ route('account::classes.index', ['type' => 'past']) }}"
                   class="{{ request('type') === 'past' ? 'border-b-2 border-purple-600 font-semibold text-purple-700' : 'text-gray-600 hover:text-purple-600' }} pb-3">
                    Past
                </a>
            </div>

            <hr class="absolute bottom-0 w-full border-t border-gray-300">
        </div>


        <!-- Content -->
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Carts -->
            <div class="w-full lg:w-3/5 space-y-4">
                @foreach ($classes as $class)
                    <div class="bg-white rounded-xl shadow-md p-5 border border-gray-200">
                        <div class="flex flex-col lg:flex-row items-start lg:items-center gap-6">
                            <!-- Left side -->
                            <div class="flex-shrink-0">
                                <img src="https://ui-avatars.com/api/?name=Floyd&background=6B46C1&color=fff&size=48"
                                     alt="Floyd" class="w-12 h-12 rounded-full">
                            </div>

                            <!-- Right side -->
                            <div class="w-full">
                                <h3 class="text-base font-semibold text-gray-900">
                                    Using <span class="font-bold">separable and inseparable phrasal verbs</span> with Floyd
                                </h3>

                                <div class="flex items-center text-sm text-gray-600 space-x-2 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>
                                    <span>Group class</span>
                                    <span class="text-gray-400">•</span>
                                    <span>Grammar</span>
                                </div>

                                <div class="flex items-center text-sm text-gray-600 space-x-2 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span>11:00 PM – 12:00 AM GMT+10</span>
                                </div>

                                <div class="flex items-center space-x-2 mt-2">
                                    <div class="flex -space-x-2">
                                        <div class="w-8 h-8 rounded-full bg-pink-500 text-white text-xs flex items-center justify-center border-2 border-white">N</div>
                                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center border-2 border-white">T</div>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <span class="text-xs text-gray-700 bg-gray-100 px-3 py-2 rounded-md border border-gray-300">
                                        You missed this class
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Calendar -->
            <div class="w-full lg:w-2/5">
                <h3 class="text-lg font-semibold mb-2 text-gray-800">Calendar</h3>
                <!--  -->
                <div id="calendar-placeholder" class="bg-white rounded-xl shadow-md p-5 space-y-3 border border-gray-200">
                    <!--  -->
                    asdfasfas
                </div>
            </div>
        </div>
    </div>
</x-theme::app-layout>