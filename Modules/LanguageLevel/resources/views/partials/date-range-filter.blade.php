<div class="bg-white border rounded-md p-4 mb-6">
    <form method="GET" action="{{ route('languagelevel::index') }}" class="flex flex-wrap items-center gap-4">
        <!-- Preserve level_id and subject_ids -->
        <input type="hidden" name="level_id" value="{{ $selectedLevelId }}">
        @foreach ($selectedSubjectIds as $subjectId)
            <input type="hidden" name="subject_ids[]" value="{{ $subjectId }}">
        @endforeach

        <!-- Date range -->
        <div>
            <p class="text-sm text-gray-700 font-semibold mb-1">Date and Time</p>
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
            <p class="text-sm text-gray-700 font-semibold mb-1">Group or Private</p>
            <div class="flex space-x-2">
                <button type="button" class="btn-primary">Group</button>
                <button type="button" class="btn-secondary">Private</button>
            </div>
        </div>

        <!-- Clear all -->
        <div class="self-end ml-auto">
            <a href="{{ route('languagelevel::index', $selectedLevelId ? ['level_id' => $selectedLevelId] : []) }}" class="btn-primary-inverted">
                Clear all
            </a>
        </div>
    </form>
</div>
