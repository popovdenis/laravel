<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Language Levels') }}</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @foreach ($levels as $level)
            <div class="mb-6 border rounded shadow-sm p-4 bg-white">
                <h3 class="text-xl font-bold">{{ $level->title }}</h3>

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
                            <ul class="list-disc list-inside text-gray-600">
                                @foreach ($teacher->scheduleTimeslots as $slot)
                                    <li>{{ ucfirst($slot->day) }}: {{ \Carbon\Carbon::parse($slot->start)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end)->format('H:i') }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 text-sm">No available time slots.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</x-app-layout>
