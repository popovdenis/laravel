<x-app-layout>
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Book Your Lesson') }}</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Sidebar -->
        @include('languagelevel::partials.sidebar')

        <!-- Central part -->
        <div class="md:col-span-3 space-y-6">
            <!-- Date filter -->
            @include('languagelevel::partials.date-range-filter')

            <!-- Slots grouped by date -->
            @include('languagelevel::partials.slots')
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
