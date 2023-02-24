@extends("layouts.email")
@section("emailbody")	

# Change Order Request

Hi, we received change order request to the {{ config('app.name') }} <br><br>
Click below link to approve or decline the change order status<br><br>
<a href="{{ $mail_data['url'] }}">{{ $mail_data['url'] }}</a><br>

Thanks,<br>
Team {{ config('app.name') }}

@endsection	