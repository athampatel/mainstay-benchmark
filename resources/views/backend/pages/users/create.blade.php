
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
                <h4 class="page-title pull-left">User Create</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.users.index') }}">All Users</a></li>
                    <li><span>Create User</span></li>
                </ul>
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
                    
                    <div class="alert alert-success d-none text-center" id="customer_response_alert">Customer found</div>
                    <div class="form-row align-items-center">
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="name">Search Customer with customer email or customer no</label>
                            <input type="text" class="form-control" id="search-customer-no" name="customer_search" placeholder="Enter Customer no or customer email" value="10SNJ00">
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <label for=""></label>
                            <button class="form-control btn btn-primary" id='user-search'>Search</button>
                        </div>
                    </div>

                    <h4 class="header-title">Create New User</h4>
                    @include('backend.layouts.partials.messages')
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_no">User No</label>
                                <input type="text" class="form-control" id="user_no" name="user_no" placeholder="Enter User Number">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_email">User Email</label>
                                <input type="text" class="form-control" id="user_email" name="user_email" placeholder="Enter User Email">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_name">User Name</label>
                                <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter Name">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="ardivision_no">ardivisionno</label>
                                <input type="text" class="form-control" id="ardivision_no" name="ardivision_no" placeholder="Enter AR division no">
                            </div>
                        </div>

                        <h6 class="text-secondary">Address</h6><br>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="address_line_1">Address Line 1</label>
                                <input type="text" name="address_line_1" class="form-control" id="address_line_1" placeholder="Enter Address line 1">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="address_line_2">Address Line 2</label>
                                <input type="text" name="address_line_2" class="form-control" id="address_line_2" placeholder="Enter Address line 2">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="address_line_3">Address Line 3</label>
                                <input type="text" name="address_line_3" class="form-control" id="address_line_3" placeholder="Enter Address line 3">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_city">City</label>
                                <input type="text" name="user_city" class="form-control" id="user_city" placeholder="Enter City">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_state">State</label>
                                <input type="text" name="user_state" class="form-control" id="user_state" placeholder="Enter State">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_zipcode">Zipcode</label>
                                <input type="text" name="user_zipcode" class="form-control" id="user_zipcode" placeholder="Enter Zipcode">
                            </div>
                        </div>

                        <h6 class="text-secondary">Sales person</h6><br>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="sales_person_divison_no">Division No </label>
                                <input type="text" name="sales_person_divison_no" class="form-control" id="sales_person_divison_no" placeholder="Enter Division No">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="sales_person_no">Sales Peson Number</label>
                                <input type="text" name="sales_person_no" class="form-control" id="sales_person_no" placeholder="Enter Sales Person No">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="sales_person_name">Sales Person Name</label>
                                <input type="text" name="sales_person_name" class="form-control" id="sales_person_name" placeholder="Enter Sales Person Name">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="sales_person_email">Sales Person Email</label>
                                <input type="text" name="sales_person_email" class="form-control" id="sales_person_email" placeholder="Enter Sales Person Email">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save User</button>
                    </form>
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

    $(document).on('click','#user-search',function(){
        $search_text = $('#search-customer-no').val();
        $.ajax({
            type: 'POST',
            url: '/admin/get_customer_info',
            dataType: "JSON",
            data: { "_token": "{{ csrf_token() }}",'search_text':$search_text},
            success: function (res) {
                if(res.customers.length > 0){
                    $customer = res.customers[0];
                    $('#user_no').val($customer.customerno);
                    $('#user_email').val($customer.emailaddress);
                    $('#user_name').val($customer.customername);
                    $('#ardivision_no').val($customer.ardivisionno);
                    $('#address_line_1').val($customer.addressline1);
                    $('#address_line_2').val($customer.addressline2);
                    $('#address_line_3').val($customer.addressline3);
                    $('#user_city').val($customer.city);
                    $('#user_state').val($customer.state);
                    $('#user_zipcode').val($customer.zipcode);
                    $('#sales_person_divison_no').val($customer.salespersondivisionno);
                    $('#sales_person_no').val($customer.salespersonno);
                    $('#sales_person_name').val($customer.salespersonname);
                    $('#sales_person_email').val($customer.salespersonemail);
                    $('#customer_response_alert').removeClass('d-none');
                    setTimeout(() => {
                        $('#customer_response_alert').addClass('d-none');
                    },2000);
                } else {
                    $('#customer_response_alert').removeClass('d-none');
                    $('#customer_response_alert').removeClass('alert-success');
                    $('#customer_response_alert').addClass('alert-danger');
                    setTimeout(() => {
                        $('#customer_response_alert').addClass('d-none');
                    },2000);
                }
            }
        });
    })
</script>
@endsection