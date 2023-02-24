@extends("layouts.email")
@section("emailbody")	

# Change the user status

Hi, we received user request to sign up the {{ config('app.name') }} <br><br>
Click below link to change the user status.<br><br>
<a href="{{ $mail_data['url'] }}">{{ $mail_data['url'] }}</a><br>


Thanks,<br>
Team {{ config('app.name') }}

@endsection	