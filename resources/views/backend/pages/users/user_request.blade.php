
@extends('backend.layouts.master')

@section('title')
User Create - Admin Panel
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .form-check-label {
        text-transform: capitalize;
    }
</style>
@endsection


@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">User Activate</h4>
                {{-- <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.users.index') }}">All Users</a></li>
                    <li><span>Create User</span></li>
                </ul> --}}
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-success d-none text-center" id="user_activate_message"></div>
                    <h4 class="header-title">User Information</h4>
                    @include('backend.layouts.partials.messages')
                    {{-- <form action="{{ route('admin.users.store') }}" method="POST"> --}}
                        {{-- @csrf --}}
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_no">User No</label>
                                <div class="text-secondary">{{$user_info['customerno']}}</div>
                                {{-- <input type="text" class="form-control" id="user_no" name="user_no" placeholder="Enter User Number" disabled> --}}
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_email">User Email</label>
                                <div class="text-secondary">{{$user_info['emailaddress']}}</div>
                                {{-- <input type="text" class="form-control" id="user_email" name="user_email" placeholder="Enter User Email" disabled> --}}
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_name">User Name</label>
                                <div class="text-secondary">{{$user_info['customername']}}</div>
                                {{-- <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter Name" disabled> --}}
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="ardivision_no">ardivisionno</label>
                                <div class="text-secondary">{{$user_info['ardivisionno']}}</div>
                                {{-- <input type="text" class="form-control" id="ardivision_no" name="ardivision_no" placeholder="Enter AR division no" disabled> --}}
                            </div>
                        </div>

                        <h6 class="text-secondary">Address</h6><br>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="address_line_1">Address Line 1</label>
                                <div class="text-secondary">{{$user_info['addressline1']}}</div>
                                {{-- <input type="text" name="address_line_1" class="form-control" id="address_line_1" placeholder="Enter Address line 1" disabled> --}}
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="address_line_2">Address Line 2</label>
                                <div class="text-secondary">{{$user_info['addressline2']}}</div>
                                {{-- <input type="text" name="address_line_2" class="form-control" id="address_line_2" placeholder="Enter Address line 2" disabled> --}}
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="address_line_3">Address Line 3</label>
                                <div class="text-secondary">{{$user_info['addressline3']}}</div>
                                {{-- <input type="text" name="address_line_3" class="form-control" id="address_line_3" placeholder="Enter Address line 3" disabled> --}}
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_city">City</label>
                                <div class="text-secondary">{{$user_info['city']}}</div>
                                {{-- <input type="text" name="user_city" class="form-control" id="user_city" placeholder="Enter City" disabled> --}}
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_state">State</label>
                                <div class="text-secondary">{{$user_info['state']}}</div>
                                {{-- <input type="text" name="user_state" class="form-control" id="user_state" placeholder="Enter State" disabled> --}}
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_zipcode">Zipcode</label>
                                <div class="text-secondary">{{$user_info['zipcode']}}</div>
                                {{-- <input type="text" name="user_zipcode" class="form-control" id="user_zipcode" placeholder="Enter Zipcode" disabled> --}}
                            </div>
                        </div>

                        <h6 class="text-secondary">Sales person</h6><br>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="sales_person_divison_no">Division No </label>
                                <div class="text-secondary">{{$user_info['salespersondivisionno']}}</div>
                                {{-- <input type="text" name="sales_person_divison_no" class="form-control" id="sales_person_divison_no" placeholder="Enter Division No" disabled> --}}
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="sales_person_no">Sales Peson Number</label>
                                <div class="text-secondary">{{$user_info['salespersonno']}}</div>
                                {{-- <input type="text" name="sales_person_no" class="form-control" id="sales_person_no" placeholder="Enter Sales Person No" disabled> --}}
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="sales_person_name">Sales Person Name</label>
                                <div class="text-secondary">{{$user_info['salespersonname']}}</div>
                                {{-- <input type="text" name="sales_person_name" class="form-control" id="sales_person_name" placeholder="Enter Sales Person Name" disabled> --}}
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="sales_person_email">Sales Person Email</label>
                                <div class="text-secondary">{{$user_info['salespersonemail']}}</div>
                                {{-- <input type="text" name="sales_person_email" class="form-control" id="sales_person_email" placeholder="Enter Sales Person Email" disabled> --}}
                            </div>
                        </div>

                        <input type="hidden" name="" value="{{$user->id}}" id="user_id">

                        <button class="btn btn-primary mt-4 pr-4 pl-4" id="activate_user">Activate User</button>
                        <button class="btn btn-danger mt-4 pr-4 pl-4" id="cancel_user">Cancel User</button>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
        <!-- data table end -->
        
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    
    $(document).ready(function() {
        $('.select2').select2();
    })

    $(document).on('click','#activate_user',function(){
        $user_id = $('#user_id').val();
        AjaxRequest('/admin/user/activate','POST',{ "_token": "{{ csrf_token() }}",'user_id':$user_id},'userActivateResponseAction')
    });

    $(document).on('click','#cancel_user',function(){
        $user_id = $('#user_id').val();
        AjaxRequest('/admin/user/cancel','POST',{ "_token": "{{ csrf_token() }}",'user_id':$user_id},'userActivateResponseAction')
    });

    
    function AjaxRequest($url,$method,$data,$callback){
        $.ajax({
            type: $method,
            url: $url,
            dataType: "JSON",
            data: $data,
            success: function (res) {  
                window[$callback](res);
            }
        });
    }

    function userActivateResponseAction(res){
        if(res.success){
            $('#user_activate_message').text(res.message).removeClass('d-none');
            setTimeout(() => {
                $('#user_activate_message').addClass('d-none');
                window.location.href = "{{ url('/admin/users/')}}";
            }, 2000);
        }
    }
</script>
@endsection