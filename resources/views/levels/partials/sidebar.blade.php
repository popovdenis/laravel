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
