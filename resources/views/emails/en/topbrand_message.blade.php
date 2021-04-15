@component('mail::message')
<u><b>{{ $name }} Sent You A Message From Afiaanyi.com</b></u>
<br><br>
Subject: <br>
{{ $subject }}<br><br>

Email:<br>
{{ $email }}<br><br>


Message: <br>
{{ $message }}<br><br>

You received this message because your business by name ({{ $topbrand }}) is registered on www.afiaanyi.com

Thanks,<br>
{{ config('app.name') }}
@endcomponent
