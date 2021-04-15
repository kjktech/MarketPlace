@component('mail::message')
# Hello Admin,

A new business has been posted: {{$name}}

@component('mail::button', ['url' => $url])
View Business
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
