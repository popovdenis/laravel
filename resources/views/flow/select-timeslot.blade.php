<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-medium text-gray-900">
            Select Time Slots
        </h2>
    </x-slot>

    <div class="bg-white shadow-sm rounded-lg p-6">
        <!-- Selected course -->
        <div class="mb-6">
            <h3 class="text-md font-semibold text-gray-800 mb-2">Selected Course</h3>
            <div class="p-4 border rounded bg-gray-50">
                <strong>{{ $course->title }}</strong><br>
                {{ $course->description }}
            </div>
        </div>

        <form method="POST" action="{{ route('flow.checkout.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Teacher info -->
                <div>
                    <h3 class="text-md font-semibold text-gray-800 mb-2">Teacher</h3>
                    <div class="p-4 border rounded bg-gray-50">
                        <p><strong>{{ $teacher->name }}</strong></p>
                        <p>{{ $teacher->email }}</p>
                        <!-- avatar, other teacher info -->
                    </div>
                </div>

                <!-- Slots -->
                <div class="md:col-span-2">
                    <h3 class="text-md font-semibold text-gray-800 mb-2">Available Time Slots</h3>
                    <div class="flex flex-col gap-3">
                        @foreach ($timeslots as $slot)
                            @php
                                $value = "{$slot->id}";
                            @endphp

                            <button
                                type="button"
                                class="select-slot px-4 py-2 rounded border text-left text-sm bg-white text-gray-700 border-gray-300 hover:bg-gray-100 transition"
                                data-id="{{ $value }}"
                            >
                                {{ ucfirst($slot->day) }} {{ \Carbon\Carbon::parse($slot->start)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end)->format('H:i') }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <input type="hidden" name="selected_slots" id="selected-slots">

            <div class="mt-6 text-end">
                <x-primary-button type="submit">
                    Continue to Checkout
                </x-primary-button>
            </div>
        </form>
    </div>

    <script>
        const selected = new Set();

        document.querySelectorAll('.select-slot').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;

                if (selected.has(id)) {
                    selected.delete(id);
                    button.classList.remove('bg-blue-500', 'text-white', 'border-blue-500', 'hover:bg-blue-600');
                    button.classList.add('bg-white', 'text-gray-700', 'border-gray-300', 'hover:bg-gray-100');
                } else {
                    selected.add(id);
                    button.classList.remove('bg-white', 'text-gray-700', 'border-gray-300', 'hover:bg-gray-100');
                    button.classList.add('bg-blue-500', 'text-white', 'border-blue-500', 'hover:bg-blue-600');
                }

                document.getElementById('selected-slots').value = JSON.stringify([...selected]);
            });
        });
    </script>
</x-app-layout>
