<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Book Your Lesson') }}</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Sidebar -->
        <aside class="bg-white border rounded shadow-sm p-4 space-y-4">
            <form method="GET" action="{{ route('levels.index') }}">
                <!-- Level selection -->
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Level:</label>
                <select name="level_id" onchange="this.form.submit()" class="w-full border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 mb-4">
                    @foreach ($levels as $level)
                        <option value="{{ $level->id }}" {{ $selectedLevelId == $level->id ? 'selected' : '' }}>
                            {{ $level->title }}
                        </option>
                    @endforeach
                </select>

                <!-- Subject checkboxes -->
                @if ($selectedLevelId)
                    <p class="text-sm font-medium text-gray-700 mb-1">Filter by Subjects:</p>
                    @foreach ($levels->where('id', $selectedLevelId)->first()->subjects as $subject)
                        <div class="flex items-center mb-1">
                            <input
                                type="checkbox"
                                name="subject_ids[]"
                                value="{{ $subject->id }}"
                                id="subject-{{ $subject->id }}"
                                {{ in_array($subject->id, $selectedSubjectIds ?? []) ? 'checked' : '' }}
                                onchange="this.form.submit()"
                            >
                            <label for="subject-{{ $subject->id }}" class="ml-2 text-sm text-gray-700">{{ $subject->title }}</label>
                        </div>
                    @endforeach
                @endif
            </form>
        </aside>

        <!-- Central part: grouped time slots -->
        <div class="md:col-span-3 space-y-8">
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
                                    <!-- Здесь можешь вставить количество участников -->
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
