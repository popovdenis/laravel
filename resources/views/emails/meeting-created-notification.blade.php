@component('mail::message')
    # Hello, {{ $user->name }}

    You have a new lesson scheduled.

    **Start Time:** {{ $startsAt }}

    @component('mail::button', ['url' => $url])
        Join the Meeting
    @endcomponent

    Thanks,
    {{ config('app.name') }}
@endcomponent
