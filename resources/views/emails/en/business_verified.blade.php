@component('mail::message')
# Hello {{ $name }}

We're please to inform you that your business {{ $title }} has been verified and will carry a verified status on the website.

@component('mail::button', ['url' => $url])
View Business
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
