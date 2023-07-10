@extends("layouts.email")
@section("emailbody")	

<table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="bg_color">

    <tr>
        <td align="center">
            <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590" style="background-color:#e2e2e2; padding:0px">
                <tr>

                    <td align="center" class="section-img">
                        <a href="" style=" border-style: none !important; display: block; border: 0 !important;"><img src="/assets/images/gallery/23.png" style="display: block; width: 590px;" width="590" border="0" alt="" /></a>
                    </td>
                </tr>
                <tr>
                    <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center" style="color: #343434; font-size: 24px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;" class="main-header">


                        <div style="line-height: 35px; padding:0.5rem 2rem">
                            <span style="color: #424448;">Inventory Post Quantity Update</span>
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
                                <td align="left" style="color: #888888; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">
                                    <div style="line-height: 24px; padding:0px 32px">
                                        <p style="padding:10px 0px">Hi Support,</p><br/>
                                        <p style="padding:10px 0px">{!!$details['body_header']!!}</p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr> 

                <tr>
                    <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                </tr>

                <tr>
                    <td align="center" style="padding:0px 32px">
                        <table border="10" bordercolor="white" width="100%" align="center" cellpadding="10" cellspacing="0" class="container590">
                            <thead>
                                <tr>
                                    <td align="left" style="color: #101010; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">
                                        <div style="line-height: 24px; padding:0px 32px; color:#888888;">
                                            Customer<br>Item Number
                                        </div>
                                    </td>
                                    <td align="left" style="color: #101010; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">
                                        <div style="line-height: 24px; padding:0px 32px;color:#888888">
                                            Benchmark<br>Item Number
                                        </div>
                                    </td>
                                    <td align="left" style="color: #101010; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">
                                        <div style="line-height: 24px; padding:0px 32px;color:#888888">
                                            Item <br>Description
                                        </div>
                                    </td>
                                    <td align="left" style="color: #101010; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">
                                        <div style="line-height: 24px; padding:0px 32px;color:#888888">
                                            Qty<br>on Hand
                                        </div>
                                    </td>
                                    <td align="left" style="color: #101010; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">
                                        <div style="line-height: 24px; padding:0px 32px;color:#888888">
                                            Quantity<br>Counted
                                        </div>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($details['data_array'] as $data_arr)    
                                    <tr>
                                        <td align="left" style="color: #101010; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">
                                            <div style="line-height: 24px; padding:0px 32px;color:#888888">
                                               {{$data_arr['item_key']}}
                                            </div>
                                        </td>
                                        <td align="left" style="color: #101010; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">
                                            <div style="line-height: 24px; padding:0px 32px;color:#888888">
                                                {{$data_arr['itemcode']}}
                                            </div>
                                        </td>
                                        <td align="left" style="color: #101010; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">
                                            <div style="line-height: 24px; padding:0px 32px;color:#888888">
                                                {{$data_arr['description']}}
                                            </div>
                                        </td>
                                        <td align="left" style="color: #101010; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">
                                            <div style="line-height: 24px; padding:0px 32px;color:#888888">
                                                {{$data_arr['old_qty']}}
                                            </div>
                                        </td>
                                        <td align="left" style="color: #101010; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">
                                            <div style="line-height: 24px; padding:0px 32px;color:#888888">
                                                {{$data_arr['new_qty']}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    {{-- {!!$details['body']!!} --}}
                    </td>
                </tr>


            </table>

        </td>
    </tr>
</table>
@endsection	