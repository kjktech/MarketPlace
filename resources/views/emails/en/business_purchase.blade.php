@component('mail::message')
# Hello {{ $name }}

We're please to inform you that your business registration purchase ({{ $type }}) has been successful,
you can now proceed in registering your business on Afiaanyi.com.

@component('mail::button', ['url' => $url])
Proceed to Afiaanyi
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
