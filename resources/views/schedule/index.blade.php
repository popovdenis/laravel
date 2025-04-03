<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">My Schedule</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            @if ($schedules->isEmpty())
                <p class="text-gray-500">You don't have any meetings scheduled.</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($schedules as $schedule)
                        <li class="py-4 flex justify-between items-center">
                            <div>
                                <p class="text-lg font-semibold">Lesson with {{ $schedule->teacher->name }}</p>
                                <p class="text-sm text-gray-600">Starts at: {{ $schedule->start_time->format('M d, Y H:i') }}</p>
                                <p class="text-sm text-gray-600">Meeting ID: {{ $schedule->zoom_meeting_id ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600">Passcode: {{ $schedule->passcode ?? 'N/A' }}</p>
                            </div>
                            <div>
                                @if ($schedule->zoom_join_url)
                                    <a href="{{ $schedule->zoom_join_url }}" target="_blank"
                                       class="inline-block mt-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                                        Join Meeting
                                    </a>
                                @elseif ($schedule->custom_link)
                                    <a href="{{ $schedule->custom_link }}" target="_blank"
                                       class="inline-block mt-2 bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-sm">
                                        Join Custom Meeting
                                    </a>
                                @else
                                    <span class="text-sm text-gray-500 mt-2">Meeting not available</span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
