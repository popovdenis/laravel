<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $course->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8 space-y-4 bg-white p-6 shadow rounded">
            <div class="text-sm text-gray-600">
                Level: {{ $course->level }} | Duration: {{ $course->duration }} | ${{ $course->price }}
            </div>
            <div class="prose max-w-none">
                {!! nl2br(e($course->description)) !!}
            </div>

{{--            <form method="POST" action="{{ route('cart.add', $course) }}">--}}
{{--                @csrf--}}
{{--                <button type="submit"--}}
{{--                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">--}}
{{--                    Add to Cart--}}
{{--                </button>--}}
{{--            </form>--}}
            <form method="POST" action="{{ route('flow.selectTeacher.store') }}">
                @csrf
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <x-primary-button>
                    {{ __('Select a Teacher') }}
                </x-primary-button>
            </form>
        </div>
    </div>
</x-app-layout>
