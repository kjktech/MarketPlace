@component('mail::message')
<u><b>{{ $name }} Sent You A Contact Message From Afiaanyi.com</b></u>
<br><br>

Email:<br>
{{ $email }}<br><br>


Message: <br>
{{ $message }}<br><br>


Thanks,<br>
{{ config('app.name') }}
@endcomponent
