<x-app-layout>
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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

                <!-- Language Level selector -->
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
            <div class="bg-white border rounded-md p-4 mb-6">
                <form method="GET" action="{{ route('levels.index') }}" class="flex flex-wrap items-center gap-4">
                    <!-- Preserve level_id and subject_ids -->
                    <input type="hidden" name="level_id" value="{{ $selectedLevelId }}">
                    @foreach ($selectedSubjectIds as $subjectId)
                        <input type="hidden" name="subject_ids[]" value="{{ $subjectId }}">
                    @endforeach

                    <!-- Date range -->
                    <div>
                        <p class="text-xs text-gray-700 font-semibold mb-1">Date and Time</p>
                        <input
                            id="datetime-range"
                            type="text"
                            class="border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm px-3 py-2 w-64"
                            placeholder="Select date and time range"
                            readonly
                        >
                        <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">
                    </div>

                    <!-- Group or Private (toggle buttons, non-functional for now) -->
                    <div>
                        <p class="text-xs text-gray-700 font-semibold mb-1">Group or Private</p>
                        <div class="flex space-x-2">
                            <button type="button" class="border rounded-md px-3 py-2 text-sm text-blue-600 border-blue-600 bg-blue-100">Group</button>
                            <button type="button" class="border rounded-md px-3 py-2 text-sm text-gray-700 border-gray-300">Private</button>
                        </div>
                    </div>

                    <!-- Clear all -->
                    <div class="self-end ml-auto">
                        <a href="{{ route('levels.index', $selectedLevelId ? ['level_id' => $selectedLevelId] : []) }}" class="px-4 py-2 border border-blue-600 text-blue-600 text-sm rounded-md hover:bg-blue-50">
                            Clear all
                        </a>
                    </div>
                </form>
            </div>
            <!-- Slots grouped by date -->
            @forelse ($groupedSlots as $date => $slots)
                <div>
                    <h3 class="text-md font-semibold text-gray-800 bg-gray-100 px-4 py-2 rounded">
                        {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}
                    </h3>
                    <div class="mt-4">
                        @foreach ($slots as $item)
                            <div class="flex items-center justify-between border border-gray-200 rounded-md bg-white px-6 py-5">
                                <!-- Time -->
                                <div class="w-24 text-blue-700 font-bold text-sm uppercase">
                                    {{ $item['time'] }}
                                </div>

                                <!-- Subject details -->
                                <div class="flex-1 px-4">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">
                                        {{ $item['stream']->languageLevel->title }}
                                        • Chapter {{ $item['current_subject_number'] }}
                                        • {{ strtoupper($item['subject']->category ?? '') }}
                                    </p>
                                    <p class="text-sm text-gray-800 font-semibold">
                                        {{ $item['subject']->title ?? 'No subject selected' }}
                                    </p>
                                </div>

                                <!-- Teacher -->
                                <div class="flex items-center justify-center w-64 space-x-2 mr-16">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($item['teacher']->name) }}&size=32"
                                         alt="{{ $item['teacher']->name }}"
                                         class="w-8 h-8 rounded-full">
                                    <span class="text-xs text-gray-500 uppercase tracking-wide">
                                        {{ __('Group Class with :name', ['name' => $item['teacher']->name]) }}
                                    </span>
                                </div>

                                <!-- Actions -->
                                <div class="flex space-x-2">
                                    <button class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">{{ __('Book') }}</button>
                                    <button class="px-4 py-2 bg-gray-200 text-gray-800 text-sm rounded-md hover:bg-gray-300">{{ __('Details') }}</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-gray-500">{{ __('No available streams for the selected filters.') }}</p>
            @endforelse
        </div>
    </div>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        flatpickr("#datetime-range", {
            mode: "range",
            enableTime: false,
            noCalendar: false,
            dateFormat: "Y-m-d",
            time_24hr: true,
            onClose: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    document.getElementById('start_date').value = flatpickr.formatDate(selectedDates[0], "Y-m-d H:i");
                    document.getElementById('end_date').value = flatpickr.formatDate(selectedDates[1], "Y-m-d H:i");
                    instance._input.form.submit();
                }
            },
            defaultDate: [
                "{{ request('start_date') }}",
                "{{ request('end_date') }}"
            ],
        });
    </script>
</x-app-layout>
