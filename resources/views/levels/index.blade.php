<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">{{ __('Language Levels') }}</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @foreach ($levels as $level)
            <div class="mb-6 border rounded shadow-sm p-4 bg-white">
                <h3 class="text-xl font-bold">{{ $level->title }}</h3>

                @if ($level->subjects->isNotEmpty())
                    <ul class="mt-2 list-disc list-inside text-gray-700">
                        @foreach ($level->subjects as $subject)
                            <li>
                                <strong>{{ $subject->title }}</strong>
                                @if($subject->description)
                                    — {{ $subject->description }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 mt-2">No subjects defined yet for this level.</p>
                @endif
            </div>
        @endforeach
    </div>
</x-app-layout>
