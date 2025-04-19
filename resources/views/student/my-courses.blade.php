<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('My Courses') }}</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Sidebar -->
            @include('profile.partials.sidebar')

            <!-- Main Content -->
            <div class="md:col-span-3">
                <div class="bg-white shadow sm:rounded-lg p-6">
                    @if ($courses->isEmpty())
                        <p class="text-gray-500">You have no courses yet.</p>
                    @else
                        @foreach ($courses as $course)
                            <div class="mb-6 border rounded shadow-sm p-4 bg-white">
                                <h3 class="text-lg font-semibold">{{ $course->course->title }}</h3>
                                <p class="text-sm text-gray-500 mb-2">Teacher: {{ $course->teacher->name }}</p>

                                <ul class="text-sm space-y-1">
                                    @foreach ($course->timeslots as $slot)
                                        <li>
                                            {{ ucfirst($slot->scheduleTimeslot->day) }},
                                            {{ \Carbon\Carbon::parse($slot->scheduleTimeslot->start)->format('H:i') }} -
                                            {{ \Carbon\Carbon::parse($slot->scheduleTimeslot->end)->format('H:i') }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
