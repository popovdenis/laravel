@php
    $class = match($data['style']) {
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white',
        'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white',
        'outline' => 'border border-blue-600 text-blue-600 hover:bg-blue-50',
        default => 'bg-blue-600 text-white',
    };
@endphp

<a href="{{ $data['url'] ?? '#' }}"
   class="inline-block px-5 py-2 rounded {{ $class }}">
    {{ $data['text'] ?? 'Click' }}
</a>
