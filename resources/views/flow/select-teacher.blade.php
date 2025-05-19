<x-theme::app-layout>
    <h1 class="text-2xl font-bold mb-4">Select a Teacher for {{ $course->title }}</h1>

    <ul>
        @foreach ($teachers as $teacher)
            <li class="mb-4 border p-4 rounded">
                <div class="font-semibold">{{ $teacher->name }}</div>
                <div class="text-sm text-gray-600">{{ $teacher->email }}</div>

                <form method="POST" action="{{ route('flow.selectTimeslot.store') }}">
                    @csrf
                    <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">

                    <x-primary-button class="mt-2">
                        {{ __('Choose Time Slot') }}
                    </x-primary-button>
                </form>
            </li>
        @endforeach
    </ul>
</x-theme::app-layout>
