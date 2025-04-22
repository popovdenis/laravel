<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Language Levels') }}</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div x-data="{ open: 0 }" class="space-y-4">
            @foreach ($levels as $index => $level)
                <div class="border rounded shadow-sm bg-white">
                    <button
                        type="button"
                        class="w-full flex items-center justify-between px-4 py-3 bg-gray-100 hover:bg-gray-200 focus:outline-none"
                        @click="open === {{ $index }} ? open = null : open = {{ $index }}"
                    >
                        <h3
                            :class="open === {{ $index }}
                                ? 'text-blue-600 font-extrabold'
                                : 'text-gray-800 font-bold'"
                            class="text-lg transition-colors duration-300"
                        >
                            {{ $level->title }}
                        </h3>
                        <svg :class="{'transform rotate-180': open === {{ $index }}}" class="h-5 w-5 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open === {{ $index }}"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 max-h-0"
                         x-transition:enter-end="opacity-100 max-h-screen"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 max-h-screen"
                         x-transition:leave-end="opacity-0 max-h-0"
                         class="overflow-hidden">
                        <div class="p-4">
                            @foreach ($level->teachers as $teacher)
                                <div class="mt-4 p-4 border rounded bg-gray-50">
                                    <p class="font-semibold">{{ $teacher->name }}</p>

                                    @php
                                        $currentSubject = $teacher->currentSubjectForLevel($level->id);
                                    @endphp

                                    @if ($currentSubject)
                                        <p class="text-sm text-green-600">Current subject: {{ $currentSubject->title }}</p>
                                    @else
                                        <p class="text-sm text-gray-500">No current subject selected.</p>
                                    @endif

                                    @if ($teacher->scheduleTimeslots->isNotEmpty())
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            @foreach ($teacher->scheduleTimeslots as $slot)
                                                <button
                                                    type="button"
                                                    class="px-3 py-1 border rounded text-sm bg-white text-gray-700 border-gray-300 hover:bg-blue-500 hover:text-white transition"
                                                >
                                                    {{ ucfirst($slot->day) }}
                                                    {{ \Carbon\Carbon::parse($slot->start)->format('H:i') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($slot->end)->format('H:i') }}
                                                </button>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-500 text-sm">No available time slots.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
