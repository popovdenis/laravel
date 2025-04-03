<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">My Schedule</h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            @if($schedules->isEmpty())
                <p class="text-gray-500">You don't have any upcoming meetings.</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($schedules as $schedule)
                        <li class="py-4 flex justify-between items-center">
                            <div>
                                <p class="font-medium">{{ $schedule->teacher->name }}</p>
                                <p class="text-sm text-gray-600">{{ $schedule->start_time->format('M d, Y H:i') }}</p>
                            </div>
                            <div>
                                @if ($schedule->zoom_join_url)
                                    <a href="{{ $schedule->zoom_join_url }}" target="_blank"
                                       class="text-sm bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                        Join Meeting
                                    </a>
                                @else
                                    <span class="text-sm text-gray-500">Meeting not available</span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
