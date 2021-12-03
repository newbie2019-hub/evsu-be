@component('mail::message')
Greetings, {{$last_name}} your account was recently created and it requires you to verify your email address
by the system administrator. Click the link below to verify your email address. Thank you!
<br/>

@component('mail::button', ['url' => 'https://tesgrant.info/account?id='.$id.'&token='.$token.''])
Verify Account
@endcomponent

Thanks,<br>
System Admin
@endcomponent