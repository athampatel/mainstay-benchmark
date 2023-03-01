
@extends('backend.layouts.master')

@section('title')
User Edit - Admin Panel
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

<div class="home-content">
    <span class="page_title">Edit Customer - {{ $user->name }}</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <!-- data table start -->
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Edit User - {{ $user->name }}</h4>
                            @include('backend.layouts.partials.messages')
                            
                            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="form-row mb-4">
                                    <div class="form-group col-md-6 col-sm-12 ">
                                        <label for="name">User Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ $user->customername }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="email">User Email</label>
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="{{ $user->email }}">
                                    </div>
                                </div>

                                <h4 class="header-title">Change Password <small>(Leave it blank for no change))</small></h4>   
                                <div class="form-row pt-4">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="password_confirmation">Confirm Password</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Enter Password">
                                    </div>
                                </div>
                                <h4 class="header-title">Customer Menus</h4>   
                                @foreach($menus as $menu)
                                    @if($loop->odd)
                                        <div class="form-row pt-4">
                                    @endif
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="password">{{$menu->name}}</label>
                                            <input type="checkbox" class="ml-2 menus form-check-input" name="menus[]" placeholder="" value="{{$menu->id}}" {{ in_array("$menu->id",$menu_access) ? 'checked' : '' }}> 
                                        </div>
                                    @if($loop->even)
                                        </div>
                                    @endif
                                @endforeach
                                <button type="submit" class="btn btn-rounded btn-primary mt-4 pr-4 pl-4">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- data table end -->
                
            </div>

            <div class="row">
                <!-- data table start -->
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                        <div class="userDetails-container">        
                            <h4 class="header-title">Customer Details</h4>
                            @include('backend.layouts.partials.messages')
                            <!--<form action="{{ route('admin.users.store') }}" method="POST"> -->
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_no">Customer No</label>
                                        <input type="text" class="form-control readonly" readonly id="user_no" name="customerno" placeholder="Enter User Number" required value="{{ $user->customername }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_email">Customer Email</label>
                                        <input type="text" class="form-control readonly" readonly id="user_email" name="email" placeholder="Enter User Email" required value="{{ $user->email }}">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_name">Customer Name</label>
                                        <input type="text" class="form-control readonly" readonly id="user_name" name="customername" placeholder="Enter Name" required value="{{ $user->customername }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="ardivision_no">Phone No</label>
                                        <input type="text"class="form-control readonly" readonly id="ardivision_no" name="phone_no" placeholder="Phone No" value="{{ $user->ardivisionno }}">
                                    </div>
                                </div>

                                <h6 class="text-secondary">Address</h6><br>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="address_line_1">Address Line 1</label>
                                        <input type="text" name="addressline1" class="form-control readonly" readonly id="address_line_1" placeholder="Enter Address line 1" value="{{ $user->addressline1 }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="address_line_2">Address Line 2</label>
                                        <input type="text" name="addressline2" class="form-control readonly" readonly id="address_line_2" placeholder="Enter Address line 2" value="{{ $user->addressline2 }}">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="address_line_3">Address Line 3</label>
                                        <input type="text" name="addressline3" class="form-control readonly" readonly id="address_line_3" placeholder="Enter Address line 3" value="{{ $user->addressline3 }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_city">City</label>
                                        <input type="text" name="city" class="form-control readonly" readonly id="user_city" placeholder="Enter City" value="{{ $user->city }}">
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_state">State</label>
                                        <input type="text" name="state" class="form-control readonly" readonly id="user_state" placeholder="Enter State" value="{{ $user->state }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_zipcode">Zipcode</label>
                                        <input type="text" name="zipcode" class="form-control readonly" readonly id="user_zipcode" placeholder="Enter Zipcode" value="{{ $user->zipcode }}">
                                    </div>
                                </div>

                                <h6 class="text-secondary">Benchmark Regional Manager Details</h6><br>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="sales_person_name">Benchmark Regional Manager Name</label>
                                        <input type="text" name="salespersonname" class="form-control readonly" readonly id="sales_person_name" placeholder="Enter Sales Person Name" value="{{ $user->salespersonname }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="sales_person_email">Benchmark Regional Manager Email</label>
                                        <input type="text" name="salespersonemail"class="form-control readonly" readonly id="sales_person_email" placeholder="Enter Sales Person Email" value="{{ $user->salespersonemail }}">
                                    </div>
                                </div>

                            <!-- <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Create Customer</button>
                            </form> --->
                        </div>    
                        </div>
                    </div>
                </div>
                <!-- data table end -->
                
            </div>
            {{-- <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">
                            @foreach ($menus as $menu)
                                @if($loop->odd)
                                <div class="row">
                                @endif
                                <div class="col text-white">
                                    <input type="checkbox" class="form-checkbox menus" name="page_{{$menu->id}}"  value="{{$menu->id}}"> {{ $menu->name}}
                                </div>
                                @if($loop->odd)
                                </div>
                                @endif  
                            @endforeach
                            <input type="hidden" name="user_id" id="user_id" value="{{ Request::get('customer') }}">
                            <button class="checkbox_menu btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>
@endsection