@forelse ($groupedSlots as $date => $slots)
    <div>
        <h3 class="text-md font-medium text-gray-600 bg-gray-100 rounded">
            {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}
        </h3>
        <div class="mt-4">
            @foreach ($slots as $item)
                <div class="flex items-center justify-between border border-gray-200 rounded-md bg-white px-6 py-4">
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
                    <div class="flex items-center justify-center w-64 space-x-2 mr-20">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($item['teacher']->name) }}&size=32"
                             alt="{{ $item['teacher']->name }}"
                             class="w-8 h-8 rounded-full">
                        <span class="text-sm text-gray-500 uppercase tracking-wide">
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
