<x-theme::app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('My Courses') }}</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @include('user::profile.partials.sidebar')

            <div class="md:col-span-3">
                <div class="bg-white shadow sm:rounded-lg p-6">
                    @if ($courses->isEmpty())
                        <p class="text-gray-500">{{ __('You have no courses yet.') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-base">
                                <thead class="bg-gray-50 text-base text-gray-800 font-semibold uppercase tracking-wide">
                                <tr>
                                    <th class="px-4 py-2 text-left">{{ __('Course') }}</th>
                                    <th class="px-4 py-3 text-left">{{ __('Teacher') }}</th>
                                    <th class="px-4 py-3 text-left">{{ __('Schedule') }}</th>
                                    <th class="px-4 py-3 text-left">{{ __('Status') }}</th>
                                    <th class="px-4 py-3 text-left">{{ __('Actions') }}</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-gray-700">
                                @foreach ($courses as $course)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium">
                                            {{ $course->course->title }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $course->teacher->name }}
                                        </td>
                                        <td class="px-4 py-3">
{{--                                            @foreach ($course->timeslots as $slot)--}}
{{--                                                <div>--}}
{{--                                                    {{ ucfirst($slot->scheduleTimeslot->day) }},--}}
{{--                                                    {{ \Carbon\Carbon::parse($slot->scheduleTimeslot->start)->format('H:i') }} ---}}
{{--                                                    {{ \Carbon\Carbon::parse($slot->scheduleTimeslot->end)->format('H:i') }}--}}
{{--                                                </div>--}}
{{--                                            @endforeach--}}
                                        </td>
                                        <td class="px-4 py-3">
                                            @php
                                                $status = $course->status ?? 'active';
                                                $statusClass = match ($status) {
                                                    'active' => 'bg-green-100 text-green-800',     // Active
                                                    'pending' => 'bg-yellow-100 text-yellow-800',  // Pending
                                                    'cancelled' => 'bg-red-100 text-red-800',      // Cancelled
                                                    default => 'bg-gray-100 text-gray-800',        // Default
                                                };
                                            @endphp
                                            <span class="inline-block px-2 py-1 font-semibold rounded {{ $statusClass }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <a href="#" class="text-blue-600 hover:underline">{{ __('View') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-theme::app-layout>
