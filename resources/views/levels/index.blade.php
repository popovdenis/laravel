<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Book Your Lesson') }}</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Sidebar -->
        <aside class="bg-white border rounded shadow-sm p-4 space-y-4">
            <form method="GET" action="{{ route('levels.index') }}" id="filter-form">
                <!-- Level Selection -->
                <label for="level_id" class="block text-sm font-medium text-gray-700 mb-1">Select Level:</label>
                <select name="level_id" id="level_id" onchange="document.getElementById('filter-form').submit()"
                        class="w-full border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 mb-4">
                    @foreach ($levels as $level)
                        <option value="{{ $level->id }}" {{ $selectedLevelId == $level->id ? 'selected' : '' }}>
                            {{ $level->title }}
                        </option>
                    @endforeach
                </select>

                <!-- Subject Checkboxes -->
                @if ($selectedLevelId)
                    <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Subjects:</label>
                    <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
                        @foreach ($levels->where('id', $selectedLevelId)->first()->subjects as $subject)
                            <div class="flex items-center">
                                <input type="checkbox"
                                       name="subject_ids[]"
                                       value="{{ $subject->id }}"
                                       {{ in_array($subject->id, request()->input('subject_ids', [])) ? 'checked' : '' }}
                                       onchange="document.getElementById('filter-form').submit()"
                                       class="border-gray-300 rounded text-blue-600 focus:ring-blue-500">
                                <label class="ml-2 text-sm text-gray-700">{{ $subject->title }}</label>
                            </div>
                        @endforeach
                    </div>
                @endif
            </form>
        </aside>

        <!-- Main Content -->
        <div class="md:col-span-3 space-y-6">
            @forelse ($streams as $stream)
                @php
                    $subjectFilter = request()->input('subject_ids', []);
                    $currentSubjectId = $stream->current_subject_id;
                @endphp

                @if (!empty($subjectFilter) && !in_array($currentSubjectId, $subjectFilter))
                    @continue
                @endif

                <div class="border rounded shadow-sm bg-white p-4">
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <h3 class="text-lg font-bold">{{ $stream->languageLevel->title }} — Stream #{{ $stream->id }}</h3>
                            <p class="text-sm text-gray-600">Teacher: {{ $stream->teacher->name }}</p>
                            <p class="text-sm text-green-600">
                                Current subject: {{ $stream->currentSubject->title ?? 'No subject selected' }}
                                ({{ $stream->current_subject_number }})
                            </p>
                        </div>
                    </div>

                    @if ($stream->teacher->scheduleTimeslots->isNotEmpty())
                        <h4 class="text-md font-semibold text-gray-800 mb-2">Available Time Slots:</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($stream->teacher->scheduleTimeslots as $slot)
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
                        <p class="text-sm text-gray-500 mt-2">No available slots.</p>
                    @endif
                </div>
            @empty
                <p class="text-gray-500">No available streams for this level.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
