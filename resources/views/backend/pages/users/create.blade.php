
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

<div class="home-content">
    <span class="page_title">Create Customer</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <div class="col-12 mt-4">
                    @include('backend.layouts.partials.messages')
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="form-row align-items-center form-row align-items-center col-12 col-md-8 col-lg-8 mx-auto d-flex align-items-center flex-wrap">
                                <div class="form-group col-12 col-md-9 col-lg-9">
                                    <label for="name">{{ config('constants.label.admin.search_customer_number_email') }}</label>
                                    <input type="text" class="form-control" id="search-customer-no" name="customer_search" placeholder="Enter Contact Email" value="{{$email}}" required>
                                </div>
                                <div class="col-12 col-md-3">
                                    <button class="position-relative bm-btn-primary text-capitalize btn btn-rounded px-4 btn-primary col-12" id='user-search'>{{ config('constants.label.admin.buttons.customer_search') }}</button>
                                </div>
                            </div>
                        </div>
                    </div> 

                    <div class="alert alert-success d-none text-center" id="customer_response_alert">Customer Details Found</div>
                    <div class="card multiple-container mt-3" style="display:none;">
                        <div class="card-body">
                        </div>
                    </div>
                <div class="card userDetails-container mt-3" style="display:none;">
                    <div class="card-body">
                        <div class="userDetails-container" >        
                            <h4 class="header-title">Create New Customer</h4>
                            {{-- @include('backend.layouts.partials.messages') --}}
                            <form action="{{ route('admin.users.store') }}" method="POST" id="create-customer">
                                @csrf
                                <h6 class="text-secondary">Contact Information</h6><br>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_name">{{ config('constants.label.admin.contact_code') }}</label>
                                        <input type="text" class="form-control required" id="contact_code" name="contactcode[]" placeholder="Enter {{ config('constants.label.admin.contact_code') }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="ardivision_no">{{config('constants.label.admin.contact_name')}}</label>
                                        <input type="text" class="form-control" id="contact_name" name="contactname[]" placeholder="Enter {{config('constants.label.admin.contact_name')}}">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_name">Contact Email</label>
                                        <input type="text" class="form-control required" id="contact_email" name="contactemail[]" placeholder="Enter Contact Email">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_no">{{ config('constants.label.admin.customer_no') }}</label>
                                        <input type="text" class="form-control required" id="user_no" name="customerno[]" placeholder="Enter {{ config('constants.label.admin.customer_no') }}" required>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_email">{{ config('constants.label.admin.user_email') }}</label>
                                        <input type="email" class="form-control required" id="user_email" name="email[]" placeholder="Enter {{ config('constants.label.admin.user_email') }}" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_name">{{ config('constants.label.admin.customer_name') }}</label>
                                        <input type="text" class="form-control required" id="user_name" name="customername[]" placeholder="Enter {{ config('constants.label.admin.customer_name') }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="ardivision_no">{{ config('constants.label.admin.ar_division_no') }}</label>
                                        <input type="text" class="form-control" id="ardivision_no" name="ardivisionno[]" placeholder="Enter {{config('constants.label.admin.ar_division_no')}}">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_name">{{ config('constants.label.admin.phone_no') }}</label>
                                        <input type="text" class="form-control" id="contact_phone_no" name="phone_no[]" placeholder="Enter {{ config('constants.label.admin.phone_no') }}">
                                    </div>
                                </div>
                                <input type="hidden" name="vmi_password[]" id="contact_vmi_password" value="">
                                <h6 class="text-secondary">Address</h6><br>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="address_line_1">{{ config('constants.label.admin.address_line_1') }}</label>
                                        <input type="text" name="addressline1[]" class="form-control" id="address_line_1" placeholder="Enter {{ config('constants.label.admin.address_line_1') }} ">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="address_line_2">{{ config('constants.label.admin.address_line_2') }}</label>
                                        <input type="text" name="addressline2[]" class="form-control" id="address_line_2" placeholder="Enter {{ config('constants.label.admin.address_line_2') }}">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="address_line_3">{{ config('constants.label.admin.address_line_3') }}</label>
                                        <input type="text" name="addressline3[]" class="form-control" id="address_line_3" placeholder="Enter {{ config('constants.label.admin.address_line_3') }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_city">{{ config('constants.label.admin.city') }}</label>
                                        <input type="text" name="city[]" class="form-control" id="user_city" placeholder="Enter {{ config('constants.label.admin.city') }}">
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_state">{{ config('constants.label.admin.state') }}</label>
                                        <input type="text" name="state[]" class="form-control" id="user_state" placeholder="Enter {{ config('constants.label.admin.state') }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="user_zipcode">{{ config('constants.label.admin.zipcode') }}</label>
                                        <input type="text" name="zipcode[]" class="form-control" id="user_zipcode" placeholder="Enter {{ config('constants.label.admin.zipcode') }}">
                                    </div>
                                </div>
                                <h6 class="text-secondary">Regional Manager</h6><br>                        
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="sales_person_divison_no">{{ config('constants.label.admin.division_no') }}</label>
                                        <input type="text" name="salespersondivisionno[]" class="form-control" id="sales_person_divison_no" placeholder="Enter {{ config('constants.label.admin.division_no') }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="sales_person_no">{{ config('constants.label.admin.relational_manager_no') }}</label>
                                        <input type="text" name="salespersonno[]" class="form-control required" id="sales_person_no" placeholder="Enter Regional Manager Number">
                                    </div>
                                </div>                        
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="sales_person_name">{{ config('constants.label.admin.relational_manager_name') }}</label>
                                        <input type="text" name="salespersonname[]" class="form-control" id="sales_person_name" placeholder="Enter Regional Manager Name">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="sales_person_email">{{ config('constants.label.admin.relational_manager_email') }}</label>
                                        <input type="email" name="salespersonemail[]" class="form-control required" id="sales_person_email" placeholder="Enter Regional Manager Email">
                                    </div>
                                    <input type="hidden" name="is_vmi" id="is_vmi" value="0">
                                    <input type="hidden" name="vmi_companycode[]" id="vmi_companycode" value="">
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12 col-sm-12 custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="send_password" value="1" id="send-password" />
                                        <label class="custom-control-label px-3" for="send-password">{{ config('constants.label.admin.send_login_credentials') }}</label>
                                    </div>
                                </div>
                                {{-- is mulitple checking --}}
                                <input type="hidden" name="is_multiple" id="is_multiple" value="0">
                                <button type="submit" id="customer_submit" class="btn btn-rounded bm-btn-primary text-capitalize btn-primary mt-4 pr-4 pl-4">{{ config('constants.label.admin.buttons.create_customer') }}</button>
                            </form>
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
    const constants = <?php echo json_encode($constants); ?>;
    const searchWords = <?php echo json_encode($searchWords); ?>;
    function ValidateEmail(emailaddress){
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(emailaddress)){
            return true;
        }    
        return false;
    }

    // customer submit start
    $(document).on('click','#customer_submit',function(){
        $(document.body).append('<div id="preloader" style="opacity:0.5"><div class="loader"></div></div>');
        $('#create-customer').submit();
    });
   
    // customer submit end
    $(document).ready(function() {
        $('.select2').select2();
        var customer_email = $('#search-customer-no').val();
        if(customer_email != ''){
            $('#user-search').trigger('click');
        }    
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
    
        $(document.body).on('submit','#multiple-data',function(e){  
            e.preventDefault();
            var form = $(this);
            /* test work start */
            // let checkboxes = Array.from($('.insert-customer:checked'));
            // let send_information = [];
            // if(checkboxes.length > 0){
            //     checkboxes.forEach(check => {
            //         let customer_info =  $(check).parents('.cmer').find('.user_information');
            //         let customer_no = $(customer_info).find('input[name="customerno"]').val();
            //         // fields start
            //         let customer_email = $(customer_info).find('input[name="email"]').val();
            //         let customer_name = $(customer_info).find('input[name="customername"]').val();
            //         let customer_name = $(customer_info).find('input[name="customername"]').val();
            //         // fields end
            //         console.log(customer_no,'___customer_no')
            //         //    send_information[customer_no]=
            //         // let single_customer = {
            //         //     'customerno' :  customer_no,
            //         //     'email' : customer_email,
            //             'customername' : 
            //         // };
            //         console.log(customer_info,'___info');
            //     });
            // }
            /* test work end */


           /* if($(document.body).find('input.insert-customer:checked').length > 1){
                return false;
            }
            $('#multiple-data .form-control').each(function(){
                if($(this).hasClass('required')){
                    $(this).prop('required',true);
                    var val = $(this).val();
                    var type = $(this).attr('type');
                    if(type == 'email' && !ValidateEmail(val)){
                        $(this).addClass('error-field');
                    }else if(val.trim() == ''){
                        $(this).addClass('error-field');
                    }else{
                        $(this).removeClass('error-field');
                    }
                }     
            });
            if($('#multiple-data').find('.error-field').length > 0){
                return false;        
            }     */
                
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: form.serialize(),
                beforeSend:function(){
                    $(document.body).append('<div id="preloader" style="opacity:0.5"><div class="loader"></div></div>');
                },
                success : function(resp) {     
                    console.log(resp,'___Ajax response');
                    var result = JSON.parse(resp);
                    console.log(result);              
                   if(result.status == 'success'){
                    $('#customer_response_alert').addClass('alert-success').addClass('text-dark').removeClass('alert-danger').removeClass('d-none').html(result.message);    
                        window.location.href = result.redirect;
                   }
                   $(document.body).find('#preloader').remove();
                },
                error: function(xhr, resp, text) {
                    console.log(xhr, resp, text);
                }
            })
            return false;
        });


        
        /*$(document.body).on('click','a.do_customer',function(e){            
            if(!$(this).hasClass('active'))
                $(this).find('input.insert-customer').prop('checked',true).trigger('click');
        });*/

        $(document.body).on('change','.do_customer span.angle',function(e){
            $(this).parent().parent().find('.user_information').slideToggle();
        });

        $(document.body).on('change','input.insert-customer',function(e){
            var parseData = $(this).parent().data('json');
            var _html = $('#create-customer').html();           
            var customerno  = parseData.customerno;
            if($(this).is(':checked')){
                $(this).parent().addClass('active');                
                $(this).addClass('active');
                if($('#is_multiple').val() == 0 ) {
                    $('.userDetails-container').fadeIn();
                }
                var _html = '<div class="user_information p-3 mt-2" id="'+customerno+'" style="display:none;"><input type="hidden" name="create_user['+customerno+']" value="'+customerno+'" />'+_html;                
                _html += '</div>';  
                if($(this).parent().find('.user_information').length == 0)              
                    $(this).parent().parent().append(_html);

                var container = $('#'+customerno+'.user_information');
                //console.log(parseData,'____parseData');
                rendorUserForm(parseData,0,container);

                container.find('input').each(function(){
                    $(this).removeClass('error-field');
                });

                $(this).parent().parent().find('.user_information').slideToggle();
                if(container.find('input[name="salespersonemail"]').val() == ''){
                    container.find('input[name="salespersonemail"]').addClass('error-field');
                }
                if(container.find('input[name="salespersonno"]').val() == ''){
                    container.find('input[name="salespersonno"]').addClass('error-field');   
                }

                if(container.find('.error-field').length > 0){
                    $(this).parent().addClass('error-active');  
                    $('#customer_response_alert').removeClass('alert-success').addClass('alert-danger').removeClass('d-none').html('Customer No ('+parseData.customerno+') missing regional manager details');                     
                }
                
            }else{
                $(this).parent().removeClass('active');
                $(this).parent().parent().find('.user_information').slideUp(function(){
                        $('#'+customerno+'.user_information').remove();
                });
            }
            if($(document.body).find('input.insert-customer:checked').length > 1){
                $('#create-customer .btn-primary').hide();
                $('.form-data').show();
            }else{
                $('#create-customer .btn-primary').show();
                $('.form-data').hide();
            }

            $('#multiple-data').show();
            // $('#create-customer').hide();
        });

     

        $(document.body).on('submit','#create-customer',function(e){
            if($(document.body).find('input.insert-customer:checked').length > 1){
                return false;
            }
            $('.userDetails-container .form-control').each(function(){
                    if($(this).hasClass('required')){
                        $(this).prop('required',true);
                        var val = $(this).val();
                        if(type == 'email' && !ValidateEmail(val)){
                            $(this).addClass('error-field');
                        }else if(val.trim() == ''){
                        
                            $(this).addClass('error-field');
                        }else{
                            $(this).removeClass('error-field');
                        }
                    }     
                });
                if($('.userDetails-container').find('.error-field').length > 0){
                    return false;        
                }           
        });

    })
    
    $(document).on('click','#user-search',function(){
        // console.log(constants,'__constants');
        $search_text = $('#search-customer-no').val();
        if($search_text == ''){
            $('#customer_response_alert').addClass('alert-danger').addClass('text-white').addClass('bm-alert-danger').removeClass('alert-success').removeClass('bm-btn-primary').removeClass('d-none').html(constants.validation.admin.search_customer_number_email);     
            return false;
        }
        $.ajax({
            type: 'POST',
            url: '/admin/get_customer_info',
            dataType: "JSON",
            data: { "_token": "{{ csrf_token() }}",'search_text':$search_text},
            beforeSend:function(){
                $(document.body).append('<div id="preloader" style="opacity:0.5"><div class="loader"></div></div>');
                $('.multiple-container').fadeOut();
                $('.multiple-container').find('.card-body').html('');
                $('.userDetails-container').fadeOut();
            },
            success: function (res) {
                console.log(res,'___get customer response');
                if(!res.success) {
                    $('#customer_response_alert').removeClass('alert-success').removeClass('text-dark').removeClass('bm-btn-primary').addClass('text-white').addClass('bm-alert-danger').addClass('alert-danger').removeClass('d-none').html(res.message);     
                    return false;
                }
                let is_error = false;
                let is_error_message = '';
                if("status" in res){
                    if(res.status == 'error'){
                        is_error = true;
                        is_error_message = res.message
                    }  
                } 
                if(!is_error){
                    var total_records = parseInt(res.customers.length);
                    if(total_records > 1){
                        $('#is_multiple').val(1);
                        $('#customer_response_alert').addClass('alert-success').addClass('text-dark').addClass('bm-btn-primary').removeClass('text-white').removeClass('bm-alert-danger').removeClass('alert-danger').removeClass('d-none').html(constants.multiple_customer);     
                        var customers = res.customers;  
                        var row_html = ''; 
                        $.each(customers,function(index,value){
                            row_html +='<div class="form-row duplicate-date col-12 flex-wrap cmer">'+
                                        '<div class="form-group col-12 col-md-12 col-sm-12">'+
                                            '<a href="javascript:void(0)" data-json=\''+JSON.stringify(value)+'\' class="do_customer">'+
                                            '<input type="checkbox" class="insert-customer" name="customer[]" value="'+value.customerno+'" id="'+value.customerno+'" />'+
                                            '<label for="'+value.customerno+'">'+
                                            '<strong></strong>'+value.customername+' - '+value.customerno+''+
                                            '</label><span class="angle"><i class="fa fa-angle-down"></i></span></a>'+
                                        '</div>'+
                                    '</div>';
                        });
                        var _html = '<form action="{{ route('admin.users.store') }}" method="POST" id="multiple-data" class="form-data col-12 col-md-12 flex-wrap"><input type="hidden" name="_token" value="{{ csrf_token() }}" /><input type="hidden" name="ajaxed" value="1" /><div class="dynamic-values">'+row_html+'</div><button type="submit" class="btn btn-rounded btn-primary selected-customer text-capitalize mt-4 pr-4 pl-4">Add selected customers</button></form>';                    
                        $('.multiple-container').fadeIn();
                        $('.multiple-container').find('.card-body').html(_html);
                        return false; 
                    } else if(res.customers.length > 0){
                        $customer = res.customers[0];
                        $('#is_multiple').val(0);
                        console.log(res.contact_info,'__Contact information');
                        rendorUserForm($customer,1);
                        $('#customer_response_alert').removeClass('d-none').html(constants.validation.admin.customer_detail_found);
                        $('#customer_response_alert').addClass('alert-success');
                        $('#customer_response_alert').addClass('text-dark');
                        $('#customer_response_alert').removeClass('alert-danger');
                        $('.userDetails-container').fadeIn();
                        $('#create-customer .btn-primary').show();
                        $('#create-customer .btn-primary').fadeIn();
                    } else {
                        $('#is_multiple').val(0);
                        $('#customer_response_alert').removeClass('alert-success').removeClass('bm-btn-primary').addClass('text-white').addClass('bm-alert-danger').addClass('alert-danger').removeClass('d-none').html(constants.validation.admin.customer_search_unable);
                    }
                } else {
                    $('#customer_response_alert').removeClass('alert-success').removeClass('bm-btn-primary').addClass('text-white').addClass('bm-alert-danger').addClass('alert-danger').removeClass('d-none').html(constants.api_error_message);
                }
            },
        complete:function(){
                $(document.body).find('#preloader').remove();            }
        });
    })
function rendorUserForm($customer,show,container){
    if(!container)
    container = $('#create-customer');
   
    if("vmi_companycode" in $customer){
       container.find('#is_vmi').val(1);
    }
    let is_multiple = $('#is_multiple').val();
    console.log(is_multiple,'__is is_multiple')
    if(is_multiple == 0){
        container.find('#user_no').attr('name', 'customerno');
        container.find('#user_email').attr('name', 'email');
        container.find('#user_name').attr('name', 'customername');
        container.find('#ardivision_no').attr('name', 'ardivisionno');
        container.find('#address_line_1').attr('name', 'addressline1');
        container.find('#address_line_2').attr('name', 'addressline2');
        container.find('#address_line_3').attr('name', 'addressline3');
        container.find('#user_city').attr('name', 'city');
        container.find('#user_state').attr('name', 'state');
        container.find('#user_zipcode').attr('name', 'zipcode');
        container.find('#sales_person_divison_no').attr('name', 'salespersondivisionno');
        container.find('#sales_person_no').attr('name', 'salespersonno');
        container.find('#sales_person_name').attr('name', 'salespersonname');
        container.find('#sales_person_email').attr('name', 'salespersonemail');
        container.find('#vmi_companycode').attr('name', 'vmi_companycode');
        container.find('#contact_code').attr('name', 'contactcode');
        container.find('#contact_name').attr('name', 'contactname');
        container.find('#contact_phone_no').attr('name', 'phone_no');
        container.find('#contact_vmi_password').attr('name', 'vmi_password');
        container.find('#contact_email').attr('name', 'contactemail');
    } else {
        container.find('#user_no').attr('name', 'customerno[]');;
        container.find('#user_email').attr('name', 'email[]');
        container.find('#user_name').attr('name', 'customername[]');
        container.find('#ardivision_no').attr('name', 'ardivisionno[]');
        container.find('#address_line_1').attr('name', 'addressline1[]');
        container.find('#address_line_2').attr('name', 'addressline2[]');
        container.find('#address_line_3').attr('name', 'addressline3[]');
        container.find('#user_city').attr('name', 'city[]');
        container.find('#user_state').attr('name', 'state[]');
        container.find('#user_zipcode').attr('name', 'zipcode[]');
        container.find('#sales_person_divison_no').attr('name', 'salespersondivisionno[]');
        container.find('#sales_person_no').attr('name', 'salespersonno[]');
        container.find('#sales_person_name').attr('name', 'salespersonname[]');
        container.find('#sales_person_email').attr('name', 'salespersonemail[]');
        container.find('#vmi_companycode').attr('name', 'vmi_companycode[]');
        container.find('#contact_code').attr('name', 'contactcode[]');
        container.find('#contact_name').attr('name', 'contactname[]');
        container.find('#contact_phone_no').attr('name', 'phone_no[]');
        container.find('#contact_vmi_password').attr('name', 'vmi_password[]');
        container.find('#contact_email').attr('name', 'contactemail[]');
    }

    container.find('#user_no').val($customer.customerno);
    container.find('#user_email').val($customer.emailaddress);
    container.find('#user_name').val($customer.customername);
    container.find('#ardivision_no').val($customer.ardivisionno);
    container.find('#address_line_1').val($customer.addressline1);
    container.find('#address_line_2').val($customer.addressline2);
    container.find('#address_line_3').val($customer.addressline3);
    container.find('#user_city').val($customer.city);
    container.find('#user_state').val($customer.state);
    container.find('#user_zipcode').val($customer.zipcode);
    container.find('#sales_person_divison_no').val($customer.salespersondivisionno);
    container.find('#sales_person_no').val($customer.salespersonno);
    container.find('#sales_person_name').val($customer.salespersonname);
    container.find('#sales_person_email').val($customer.salespersonemail);
    container.find('#vmi_companycode').val($customer.vmi_companycode);
    container.find('#contact_code').val($customer.contact_info.contactcode);
    container.find('#contact_name').val($customer.contact_info.contactname);
    container.find('#contact_email').val($customer.contact_info.emailaddress);
    let phone_no = "";
    if($customer.contact_info.telephoneext1 ||$customer.contact_info.telephoneno1 )
        phone_no = $customer.contact_info.telephoneext1 + ' ' + $customer.contact_info.telephoneno1;
    container.find('#contact_phone_no').val(phone_no);
    container.find('#contact_vmi_password').val($customer.contact_info.vmi_password);
    if(show == 1)
        $('#customer_response_alert').removeClass('alert-danger').removeClass('text-white').removeClass('bm-alert-danger').removeClass('d-none').addClass('alert-success').addClass('text-dark').addClass('bm-btn-primary');
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