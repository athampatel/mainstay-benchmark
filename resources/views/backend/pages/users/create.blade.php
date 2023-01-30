
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
                <h4 class="page-title pull-left">Customer Create</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.users.index') }}">All Customer</a></li>
                    <li><span>Create Customer</span></li>
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
                            <input type="text" class="form-control" id="search-customer-no" name="customer_search" placeholder="Enter Customer no or customer email" value="" required>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <button class="position-relative btn btn-primary" id='user-search'>Search</button>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="card multiple-container mt-3" style="display: none;">
                <div class="card-body">
                     
                </div>
            </div>
        <div class="card userDetails-container mt-3" style="display: none;">
            <div class="card-body">
                <div class="userDetails-container" >        
                    <h4 class="header-title">Create New Customer</h4>
                    @include('backend.layouts.partials.messages')
                    <form action="{{ route('admin.users.store') }}" method="POST" id="create-customer">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_no">Customer No</label>
                                <input type="text" class="form-control required" id="user_no" name="customerno" placeholder="Enter User Number" required>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_email">Customer Email</label>
                                <input type="email" class="form-control required" id="user_email" name="email" placeholder="Enter User Email" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_name">Customer Name</label>
                                <input type="text" class="form-control required" id="user_name" name="customername" placeholder="Enter Name">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="ardivision_no">ardivisionno</label>
                                <input type="text" class="form-control" id="ardivision_no" name="ardivisionno" placeholder="Enter AR division no">
                            </div>
                        </div>

                        <h6 class="text-secondary">Address</h6><br>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="address_line_1">Address Line 1</label>
                                <input type="text" name="addressline1" class="form-control" id="address_line_1" placeholder="Enter Address line 1">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="address_line_2">Address Line 2</label>
                                <input type="text" name="addressline2" class="form-control" id="address_line_2" placeholder="Enter Address line 2">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="address_line_3">Address Line 3</label>
                                <input type="text" name="addressline3" class="form-control" id="address_line_3" placeholder="Enter Address line 3">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_city">City</label>
                                <input type="text" name="city" class="form-control" id="user_city" placeholder="Enter City">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_state">State</label>
                                <input type="text" name="state" class="form-control" id="user_state" placeholder="Enter State">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_zipcode">Zipcode</label>
                                <input type="text" name="zipcode" class="form-control" id="user_zipcode" placeholder="Enter Zipcode">
                            </div>
                        </div>

                        <h6 class="text-secondary">Sales person</h6><br>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="sales_person_divison_no">Division No </label>
                                <input type="text" name="salespersondivisionno" class="form-control" id="sales_person_divison_no" placeholder="Enter Division No">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="sales_person_no">Benchmark Regional Manager Number</label>
                                <input type="text" name="salespersonno" class="form-control" id="sales_person_no" placeholder="Enter Sales Person No" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="sales_person_name">Benchmark Regional Manager Name</label>
                                <input type="text" name="salespersonname" class="form-control" id="sales_person_name" placeholder="Enter Sales Person Name">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="sales_person_email">Benchmark Regional Manager Email</label>
                                <input type="email" name="salespersonemail" class="form-control required" id="sales_person_email" placeholder="Enter Sales Person Email">
                            </div>
                            <input type="hidden" name="is_vmi" id="is_vmi" value="0">
                        </div>
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Create Customer</button>
                    </form>
                </div>    
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

    function ValidateEmail(emailaddress){
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(emailaddress)){
            return true;
        }    
        return false;
    }
    $(document).ready(function() {
        $('.select2').select2();
        $('.userDetails-container .form-control').blur(function(){
            var val = $(this).val();
            
            if($(this).attr('type') == 'email' && !ValidateEmail(val) == ''){
                $(this).addClass('error-field');
            }else if(val.trim() == '' && $(this).hasClass('required')){
                $(this).addClass('error-field');
            }else{
                $(this).removeClass('error-field');
            }
        });
        $(document.body).on('click','a.do_customer',function(e){
            $('a.do_customer').each(function(){
                $(this).removeClass('active');
            });
            var parseData = $(this).data('json');
          
            $(this).addClass('active');
            $('.userDetails-container').fadeIn();
            rendorUserForm(parseData,0);

        });
        $(document.body).on('submit','#create-customer',function(e){
            //e.preventDefault();
            $('.userDetails-container .form-control').each(function(){
                    if($(this).hasClass('required')){
                        $(this).prop('required',true);
                        var val = $(this).val();
                        if(val.trim() == ''){
                            $(this).addClass('error-field');
                        }else{
                            $(this).removeClass('error-field');
                        }
                    }     
                });
                if($('.userDetails-container').find('.error-field').length > 0){
                    return false;        
                }
                alert("form submitting");
        });

    })

    $(document).on('click','#user-search',function(){
        $search_text = $('#search-customer-no').val();
        $.ajax({
            type: 'POST',
            url: '/admin/get_customer_info',
            dataType: "JSON",
            data: { "_token": "{{ csrf_token() }}",'search_text':$search_text},
            beforeSend:function(){
                $(document.body).append('<div id="preloader" style="opacity:0.5"><div class="loader"></div></div>');
            },
            success: function (res) {
                var total_records = parseInt(res.customers.length);
                if(total_records > 1){
                    $('#customer_response_alert').addClass('alert-danger').removeClass('d-none').html('Multiple records found');     
                    var customers = res.customers;  
                    var _html = '';
                    $.each(customers,function(index,value){
                        _html +='<div class="form-row duplicate-date">'+
                                    '<div class="form-group col-md-12 col-sm-12">'+
                                        '<a href="javascript:void(0)" data-json=\''+JSON.stringify(value)+'\' class="do_customer"><strong>Customer No:</strong>'+value.emailaddress+' ('+value.customerno+')'+
                                        
                                    '</a></div>'+                              
                                '</div>';
                    });
                    $('.multiple-container').fadeIn();
                    $('.multiple-container').find('.card-body').html(_html);
                    return false; 
                }else if(res.customers.length > 0){
                    $customer = res.customers[0];
                    rendorUserForm($customer,1);
                } else {
                    $('#customer_response_alert').removeClass('d-none');
                    $('#customer_response_alert').removeClass('alert-success');
                    $('#customer_response_alert').addClass('alert-danger');
                    setTimeout(() => {
                        $('#customer_response_alert').addClass('d-none');
                    },2000);
                }
                $('.userDetails-container').fadeIn();
            },
            complete:function(){
                $(document.body).find('#preloader').remove();            }
        });
    })
function rendorUserForm($customer,show){
    console.log($customer,'__customer');
    if("vmi_companycode" in $customer){
        $('#is_vmi').val(1);
    }
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
    if(show == 1)
        $('#customer_response_alert').removeClass('d-none');
        
    setTimeout(() => {
        $('#customer_response_alert').addClass('d-none');
        $('.userDetails-container .form-control').each(function(){
            if($(this).hasClass('required')){
                $(this).prop('required',true);
                var val = $(this).val();
                if(val.trim() == ''){
                    $(this).addClass('error-field');
                }else{
                    $(this).removeClass('error-field');
                }
            }     
        });
        if($('.userDetails-container').find('.error-field').length > 0){

        }
    },2000);
    return true;
}    
</script>
@endsection