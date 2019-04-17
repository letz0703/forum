@component('mail::message')
# One Last Step

We need your email address for sure you are a human.

@component('mail::button', ['url' => 'http://forum.test/register/confirm?token='.$user->confirmation_token])
    Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
