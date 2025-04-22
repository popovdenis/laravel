<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Language Levels') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <ul class="space-y-4">
                @foreach ($levels as $level)
                    <li>
                        <a href="{{ route('levels.show', $level) }}"
                           class="block p-4 border rounded hover:bg-gray-50">
                            <div class="text-lg font-bold">{{ $level->title }}</div>
                            <div class="text-sm text-gray-600">
                                Level: {{ $level->level }} | Duration: {{ $level->duration }} | ${{ $level->price }}
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>
