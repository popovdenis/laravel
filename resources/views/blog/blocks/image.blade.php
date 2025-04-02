<div class="my-4">
    <img src="{{ asset('storage/' . ($data['url'] ?? '')) }}" alt="{{ $data['caption'] ?? '' }}" class="rounded shadow">
    @if (!empty($data['caption']))
        <p class="text-sm text-gray-500 mt-1 text-center">{{ $data['caption'] }}</p>
    @endif
</div>
