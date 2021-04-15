@component('mail::message')
# Hello,

Welcome to the afiaanyi platform you can view your newly
posted business using the button below.


Business name: {{$name}}


@component('mail::button', ['url' => $url])
 View Business
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
