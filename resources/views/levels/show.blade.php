<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $level->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8 space-y-4 bg-white p-6 shadow rounded">
            <div class="text-sm text-gray-600">
                Level: {{ $level->level }} | Duration: {{ $level->duration }} | ${{ $level->price }}
            </div>
            <div class="prose max-w-none">
                {!! nl2br(e($level->description)) !!}
            </div>

            <form method="POST" action="{{ route('flow.selectTeacher.store') }}">
                @csrf
                <input type="hidden" name="course_id" value="{{ $level->id }}">
                <x-primary-button>
                    {{ __('Select a Teacher') }}
                </x-primary-button>
            </form>
        </div>
    </div>
</x-app-layout>
