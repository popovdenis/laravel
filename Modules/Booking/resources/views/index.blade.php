<x-theme::app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Book Your Lesson') }}</h2>
    </x-slot>
    <div id="booking-root"
         data-lesson-type="{{ $lessonType }}"
         data-visible-dates-count="{{ $visibleDatesCount }}"
         data-start-preferred-time="{{ $startPreferredTime }}"
         data-end-preferred-time="{{ $endPreferredTime }}"
         class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-6"></div>
    @vite('Modules/Booking/resources/assets/js/pages/index.jsx')
</x-theme::app-layout>
