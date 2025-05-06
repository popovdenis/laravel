@forelse ($groupedSlots as $date => $slots)
    <div>
        <h3 class="text-md font-medium text-gray-600 bg-gray-100 rounded">
            {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}
        </h3>
        <div class="mt-4">
            @foreach ($slots as $item)
                @php
                    $isBooked = !empty($item['booking_id']);
                @endphp
                <div
                    x-data="{ confirmBooking: false }"
                    class="flex items-start justify-between border {{ $isBooked ? 'bg-purple-100 border-purple-400' : 'bg-white border-gray-200' }} rounded-md px-6 py-4"
                >
                    <!-- Time -->
                    <div class="w-24 px-2 text-blue-700 font-bold text-sm uppercase">
                        {{ $item['time'] }}
                    </div>

                    <!-- Subject details -->
                    <div class="flex-1 px-2">
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
                    <div class="flex px-4 items-center gap-2">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($item['teacher']->firstname) }}&size=32"
                             alt="{{ $item['teacher']->firstname }}"
                             class="w-8 h-8 rounded-full">
                        <span class="text-sm text-gray-500 uppercase tracking-wide">
                            {{ __('Group Class with :name', ['name' => $item['teacher']->firstname]) }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        @if ($isBooked)
                            <div x-data="{ confirmCancel: false }">
                                <button
                                    @click="confirmCancel = true"
                                    class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700"
                                >
                                    {{ __('Cancel Booking') }}
                                </button>

                                <!-- Confirm Cancel Popup -->
                                <div
                                    x-show="confirmCancel"
                                    x-cloak
                                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                                >
                                    <div class="bg-white p-6 rounded shadow-md text-center">
                                        <p class="mb-4 text-gray-800 font-medium">
                                            {{ __('Are you sure you want to cancel this booking?') }}
                                        </p>
                                        <div class="flex justify-center space-x-2">
                                            <button @click="confirmCancel = false" class="px-4 py-2 bg-gray-300 rounded">
                                                {{ __('No, Keep Booking') }}
                                            </button>
                                            <form method="POST" action="{{ route('booking.cancel') }}">
                                                @csrf
                                                <input type="hidden" name="booking_id" value="{{ $item['booking_id'] }}">
                                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">
                                                    {{ __('Yes, Cancel Booking') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <button @click="confirmBooking = true" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                {{ __('Book') }}
                            </button>
                        @endif
                        <button class="px-4 py-2 bg-gray-200 text-gray-800 text-sm rounded-md hover:bg-gray-300">
                            {{ __('Details') }}
                        </button>
                    </div>

                    <!-- Confirm Popup -->
                    <div
                        x-show="confirmBooking"
                        x-cloak
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                    >
                        <div class="bg-white p-6 rounded shadow-md text-center">
                            <p class="mb-4 text-gray-800 font-medium">{{ __('Are you sure you want to book this slot?') }}</p>
                            <div class="flex justify-center space-x-2">
                                <button @click="confirmBooking = false" class="px-4 py-2 bg-gray-300 rounded">
                                    {{ __('Cancel') }}
                                </button>
                                <form method="POST" action="{{ route('booking.store') }}">
                                    @csrf
                                    <input type="hidden" name="stream_id" value="{{ $item['stream']->id }}">
                                    <input type="hidden" name="slot_id" value="{{ $item['slot']->id }}">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                                        {{ __('Confirm Booking') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@empty
    <p class="text-gray-500">{{ __('No available streams for the selected filters.') }}</p>
@endforelse
