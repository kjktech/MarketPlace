@component('mail::message')
# Hello {{ $user->firstname }}

We're please to inform you that your user account as been created for you,
you can now proceed to login, create and or manage your business on Afiaanyi.com.

Login with the details below:
Email: {{ $user->email }}
Password:  {{ $password }}

@component('mail::button', ['url' => $url])
Proceed to Afiaanyi
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
