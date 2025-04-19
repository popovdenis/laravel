<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('My Schedule') }}</h2>
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
                                <p class="text-lg font-semibold">{{ __('Lesson with :teacher', ['teacher' => $schedule->teacher->name]) }}</p>
                                <p class="text-sm text-gray-600">{{ __('Starts at: :start_time', ['start_time' => $schedule->start_time->format('M d, Y H:i')]) }}</p>
                                <p class="text-sm text-gray-600">{{ __('Meeting ID: :meeting_id', ['meeting_id' => $schedule->zoom_meeting_id ?? 'N/A']) }}</p>
                                <p class="text-sm text-gray-600">{{ __('Passcode: :passcode', ['passcode' => $schedule->passcode ?? 'N/A']) }}</p>
                            </div>
                            <div>
                                @if (auth()->user()->hasRole('Teacher'))
                                    @if (!$schedule->zoom_join_url && !$schedule->zoom_start_url && !$schedule->custom_link)
                                        <form method="POST" action="{{ route('profile.schedule.create-meeting', $schedule) }}">
                                            @csrf
                                            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                                                {{ __('Create Meeting') }}
                                            </button>
                                        </form>
                                    @endif
                                @endif
                                <a href="{{ route('schedule.join', $schedule) }}" target="_blank"
                                   class="inline-block mt-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                                    {{ __('Join Meeting') }}
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
