<x-theme::app-layout>
    <div class="w-full lg:w-2/3 mx-auto">
        <!-- Заголовок -->
        <h2 class="text-2xl font-bold text-gray-800 mb-4">My classes</h2>

        <!-- Навигация Scheduled / Past -->
        <div class="flex items-center space-x-4 mb-2">
            <a href="{{ route('account::classes.index', ['type' => 'scheduled']) }}"
               class="{{ request('type', 'scheduled') === 'scheduled' ? 'border-b-2 border-purple-600 font-semibold text-purple-700' : 'text-gray-600 hover:text-purple-600' }} pb-1">
                Scheduled
            </a>
            <a href="{{ route('account::classes.index', ['type' => 'past']) }}"
               class="{{ request('type') === 'past' ? 'border-b-2 border-purple-600 font-semibold text-purple-700' : 'text-gray-600 hover:text-purple-600' }} pb-1">
                Past
            </a>
        </div>

        <!-- Линия -->
        <hr class="mb-6 border-gray-300">

        <!-- Контент -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Левая колонка с карточками -->
            <div class="lg:col-span-2 space-y-4">
                @foreach ($classes as $class)
                    <div class="border border-gray-200 rounded-lg shadow-sm p-4 bg-white">
                        <!-- Пример плитки (подставь свою разметку слота) -->
                        <p class="text-sm text-gray-600">{{ $class->date }}</p>
                        <p class="font-semibold text-gray-800">{{ $class->title }}</p>
                        <p class="text-xs text-gray-500">{{ $class->status }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Правая колонка с календарём -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 h-fit">
                <h3 class="text-lg font-semibold mb-2 text-gray-800">Calendar</h3>
                <!-- Подключи компонент календаря здесь -->
                <div id="calendar-placeholder">
                    <!-- React или Blade календарь -->
                </div>
            </div>
        </div>
    </div>
</x-theme::app-layout>