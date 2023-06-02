@extends("layouts.email")
@section("emailbody")	
<!-- big image section -->
    <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="bg_color">

        <tr>
            <td align="center">
                <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590" style="background-color:#e2e2e2; padding:0px">
                    <tr>

                        <td align="center" class="section-img">
                            <a href="" style=" border-style: none !important; display: block; border: 0 !important;">
                                {{-- <img src="/assets/images/gallery/23.png" style="display: block; width: 590px;" width="590" border="0" alt="" /> --}}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center" style="color: #343434; font-size: 24px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;" class="main-header">


                            <div style="line-height: 35px; padding:0.5rem 2rem">

                                <span style="color: #424448;">{{$details['title']}}</span>

                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align="center">
                            <table border="0" width="40" align="center" cellpadding="0" cellspacing="0" bgcolor="eeeeee">
                                <tr>
                                    <td height="2" style="font-size: 2px; line-height: 2px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:0px 32px">
                            <table border="0" width="100%" align="center" cellpadding="0" cellspacing="0" class="container590">
                                <tr>
                                    <td align="left" style="color: #101010; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">
                                        <div style="line-height: 24px; padding:0px 32px">
											{{-- <p style="padding:10px 32px">Hi Support,</p> --}}
                                            @if(isset($details['namealias']))
											    <p style="padding:10px 0px">{!!$details['namealias']!!}</p>
                                            @else
                                                <p style="padding:10px 0px">Hi Support,</p>
                                            @endif
                                            @if(isset($details['name']))
                                                <p style="padding:10px 0px">Customer Name : {{$details['name']}}</p>
                                            @endif
                                            @if(isset($details['email']))
                                                <p style="padding:10px 0px">Customer Email : {{$details['email']}}</p>
                                            @endif

                                            @if(isset($details['api_connection_error']))
                                                <p style="padding:10px 0px">{{$details['api_connection_error']}}</p>
                                            @endif
                                            <p style="padding:10px 32px">{!!$details['body']!!}</p>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                    </tr>

                    @if(!isset($details['is_button']))
                    <tr>
                        <td align="center" style="padding:0px 60px;">
                            <table border="0" align="left" width="160" cellpadding="0" cellspacing="0" bgcolor="424448" style="border-radius:30px;">
                                <tr>
                                    <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align="center" style="color: #ffffff; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 26px; border-radius:30px">
                                        <div style="display:flex;justify-content:center;align-items:center;height:26px;text-align: center;width: 100%;">
                                            {{-- <a href="{{ $details['link'] }}" style="color: #ffffff; text-decoration: none;text-align: center;width: 100%;">Check Request</a> --}}
                                            <a href="{{ $details['link'] }}" style="color: #ffffff; text-decoration: none;text-align: center;width: 100%;">{{ isset($details['is_button_name']) ? $details['is_button_name'] : 'Check Request' }}</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @endif


                </table>

            </td>
        </tr>
    </table>	
    <!-- end section -->
@endsection	