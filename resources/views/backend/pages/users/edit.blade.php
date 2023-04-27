
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
                                    @if($loop->last)
                                        </div>
                                    @endif
                                @endforeach
                                <button type="submit" class="btn btn-rounded text-capitalize btn-primary mt-4 pr-4 pl-4">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                        <div class="userDetails-container">        
                            <h4 class="header-title">Customer Details</h4>
                            @include('backend.layouts.partials.messages')
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_no">{{ config('constants.label.admin.customer_no') }}</label>
                                        <input type="text" class="form-control readonly" readonly id="user_no" name="customerno" placeholder="Enter {{ config('constants.label.admin.customer_no') }}" required value="{{ $user->customername }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_email">{{ config('constants.label.admin.customer_email') }}</label>
                                        <input type="text" class="form-control readonly" readonly id="user_email" name="email" placeholder="Enter {{ config('constants.label.admin.customer_email') }}" required value="{{ $user->email }}">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_name">{{ config('constants.label.admin.customer_name') }}</label>
                                        <input type="text" class="form-control readonly" readonly id="user_name" name="customername" placeholder="Enter {{ config('constants.label.admin.customer_name') }}" required value="{{ $user->customername }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="ardivision_no">{{ config('constants.label.admin.phone_no') }}</label>
                                        <input type="text"class="form-control readonly" readonly id="ardivision_no" name="phone_no" placeholder="Phone {{ config('constants.label.admin.phone_no') }}" value="{{ $user->ardivisionno }}">
                                    </div>
                                </div>

                                <h6 class="text-secondary">Address</h6><br>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="address_line_1">{{ config('constants.label.admin.address_line_1') }}</label>
                                        <input type="text" name="addressline1" class="form-control readonly" readonly id="address_line_1" placeholder="Enter {{ config('constants.label.admin.address_line_1') }}" value="{{ $user->addressline1 }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="address_line_2">{{ config('constants.label.admin.address_line_2') }}</label>
                                        <input type="text" name="addressline2" class="form-control readonly" readonly id="address_line_2" placeholder="Enter {{ config('constants.label.admin.address_line_2') }}" value="{{ $user->addressline2 }}">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="address_line_3">{{ config('constants.label.admin.address_line_3') }}</label>
                                        <input type="text" name="addressline3" class="form-control readonly" readonly id="address_line_3" placeholder="Enter {{ config('constants.label.admin.address_line_3') }}" value="{{ $user->addressline3 }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_city">{{ config('constants.label.admin.city') }}</label>
                                        <input type="text" name="city" class="form-control readonly" readonly id="user_city" placeholder="Enter {{ config('constants.label.admin.city') }}" value="{{ $user->city }}">
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_state">{{ config('constants.label.admin.state') }}</label>
                                        <input type="text" name="state" class="form-control readonly" readonly id="user_state" placeholder="Enter {{ config('constants.label.admin.state') }}" value="{{ $user->state }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_zipcode">{{ config('constants.label.admin.zipcode') }}</label>
                                        <input type="text" name="zipcode" class="form-control readonly" readonly id="user_zipcode" placeholder="Enter {{ config('constants.label.admin.zipcode') }}" value="{{ $user->zipcode }}">
                                    </div>
                                </div>

                                <h6 class="text-secondary">Benchmark Regional Manager Details</h6><br>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="sales_person_name">{{ config('constants.label.admin.relational_manager_name') }}</label>
                                        <input type="text" name="salespersonname" class="form-control readonly" readonly id="sales_person_name" placeholder="Enter Regional Manager Name" value="{{ $user->salespersonname }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="sales_person_email">{{ config('constants.label.admin.relational_manager_email') }}</label>
                                        <input type="text" name="salespersonemail"class="form-control readonly" readonly id="sales_person_email" placeholder="Enter Regional Manager Email" value="{{ $user->salespersonemail }}">
                                    </div>
                                </div>
                            </div>    
                        </div>
                    </div>
                </div>
            </div>
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
    const searchWords = <?php echo json_encode($searchWords); ?>;
</script>
@endsection