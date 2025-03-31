<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Courses') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <ul class="space-y-4">
                @foreach ($courses as $course)
                    <li>
                        <a href="{{ route('courses.show', $course) }}"
                           class="block p-4 border rounded hover:bg-gray-50">
                            <div class="text-lg font-bold">{{ $course->title }}</div>
                            <div class="text-sm text-gray-600">
                                Level: {{ $course->level }} | Duration: {{ $course->duration }} | ${{ $course->price }}
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>
