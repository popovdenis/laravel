<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $course->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8 space-y-4">
            <div class="text-sm text-gray-600">
                Level: {{ $course->level }} | Duration: {{ $course->duration }} | ${{ $course->price }}
            </div>

            <div class="prose max-w-none">
                {!! nl2br(e($course->description)) !!}
            </div>
        </div>
    </div>
</x-app-layout>
