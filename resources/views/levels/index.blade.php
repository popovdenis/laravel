<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Book Your Lesson') }}</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Sidebar -->
        <aside class="bg-white border rounded shadow-sm p-4 space-y-6">
            <form method="GET" action="{{ route('levels.index') }}">
                @if (request('start_date'))
                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                @endif
                @if (request('end_date'))
                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                @endif

                <!-- Language Level selector (без label, placeholder через select) -->
                <select name="level_id" onchange="this.form.submit()" class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm px-3 py-2">
                    @foreach ($levels as $level)
                        <option value="{{ $level->id }}" {{ $selectedLevelId == $level->id ? 'selected' : '' }}>
                            {{ $level->title }}
                        </option>
                    @endforeach
                </select>

                <!-- Subjects grouped by Chapters -->
                @if ($selectedLevelId)
                    <div class="space-y-4 mt-4">
                        @foreach ($levels->where('id', $selectedLevelId)->first()->subjects->groupBy('chapter') as $chapter => $subjectsGroup)
                            <div>
                                <p class="text-sm font-semibold text-gray-800 mb-2">{{ $chapter }}</p>
                                <div class="space-y-2">
                                    @foreach ($subjectsGroup as $subject)
                                        <div class="flex items-center">
                                            <input
                                                type="checkbox"
                                                name="subject_ids[]"
                                                value="{{ $subject->id }}"
                                                id="subject-{{ $subject->id }}"
                                                {{ in_array($subject->id, $selectedSubjectIds ?? []) ? 'checked' : '' }}
                                                onchange="this.form.submit()"
                                                class="text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                            >
                                            <label for="subject-{{ $subject->id }}" class="ml-2 text-sm text-gray-700">{{ $subject->title }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </form>
        </aside>

        <!-- Central part -->
        <div class="md:col-span-3 space-y-6">
            <!-- Date filter -->
            <form method="GET" action="{{ route('levels.index') }}" class="flex items-center space-x-4 mb-6">
                <input type="hidden" name="level_id" value="{{ $selectedLevelId }}">
                @foreach ($selectedSubjectIds as $subjectId)
                    <input type="hidden" name="subject_ids[]" value="{{ $subjectId }}">
                @endforeach
                <div>
                    <label for="start_date" class="block text-sm text-gray-700 mb-1">Start Date:</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $filterStartDate }}" class="border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="end_date" class="block text-sm text-gray-700 mb-1">End Date:</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $filterEndDate }}" class="border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="self-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">Apply Filters</button>
                    <a href="{{ route('levels.index', $selectedLevelId ? ['level_id' => $selectedLevelId] : []) }}" class="ml-2 text-sm text-gray-700 underline">Clear</a>
                </div>
            </form>

            <!-- Slots grouped by date -->
            @forelse ($groupedSlots as $date => $slots)
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4">{{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}</h3>
                    <div class="space-y-4">
                        @foreach ($slots as $item)
                            <div class="flex items-center justify-between border rounded p-4 bg-white shadow-sm">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        <strong>{{ $item['time'] }}</strong>
                                        — {{ $item['stream']->languageLevel->title }}
                                        .{{ $item['current_subject_number'] }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Group Class with {{ $item['teacher']->name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $item['subject']->title ?? 'No subject selected' }}
                                    </p>
                                    <!-- Count of booked members -->
                                </div>
                                <div class="space-x-2">
                                    <button class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">Book</button>
                                    <button class="px-4 py-2 bg-gray-200 text-gray-800 text-sm rounded hover:bg-gray-300">Details</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No available streams for the selected filters.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
