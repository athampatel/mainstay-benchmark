
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
    <span class="page_title">Activate Customer</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <div class="loader-container d-none">
                    <div class="loader"></div>
                </div>
                <div class="col-12 mt-5">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h4 class="header-title mb-0">Customer Information</h4>       
                        </div>
                    </div> 
                    
                    @if($is_error)
                         <div class="alert alert-danger text-center">{{ $is_error_message }}</div>
                    @endif
                    @if(empty($customers) && !$is_error)
                    {{-- @if(empty($customers)) --}}
                         <div class="alert alert-danger text-center">{{ config('constants.user_request_not_found') }}</div>
                    @endif
                    
                    
                    <div class="alert alert-success d-none text-center" id="user_activate_message"></div>

                    @if(!empty($userinfo))
                    <div class="card mb-3">                        
                        <div class="card-body">
                            <div class="form-row d-flex flex-wrap mb-0">
                                <div class="form-group col-12  col-md-12 col-sm-12">                                   
                                    <label class="custom-control-label col-3">{{ config('constants.label.admin.full_name') }}</label>
                                    <span class="col-9 text-white">{{$userinfo['full_name']}}</span>
                                </div>  
                                <div class="form-group col-12  col-md-12 col-sm-12">                                   
                                    <label class="custom-control-label col-3">{{ config('constants.label.admin.email_address') }}</label>
                                    <span class="col-9 text-white">{{$userinfo['email']}}</span>
                                </div> 
                                <div class="form-group col-12  col-md-12 col-sm-12">                                   
                                    <label class="custom-control-label col-3">{{ config('constants.label.admin.company_name') }}</label>
                                    <span class="col-9 text-white">{{$userinfo['company_name']}}</span>
                                </div> 
                                <div class="form-group col-12  col-md-12 col-sm-12 mb-0">                                   
                                    <label class="custom-control-label col-3">{{ config('constants.label.admin.phone_no') }}</label>
                                    <span class="col-9 text-white">{{$userinfo['phone_no']}}</span>
                                </div>  
                            </div>
                        </div>
                    </div>    
                    @endif
                    {{-- {{dd($contact_info)}} --}}
                    @if(!empty($customers))
                        @include('backend.layouts.partials.messages')            
                        <form action="{{ route('admin.users.store') }}" method="POST" class="form-create-customers">                        
                            @csrf 
                            <div class="card mb-3">
                            @foreach($customers as $key => $user_info) 
                                        <div class="card-body {{$loop->first ? 'customer_header_row_first' : 'customer_header_row'}}" >
                                            @if(count($customers) > 1)
                                            <div class="customer_header" id="customer_header_{{$key}}">
                                                <div class="form-row mb-0">
                                                    <div class="form-group col-md-12 col-sm-12 custom-checkbox">
                                                        <input type="checkbox" name="create_user[{{$key}}]" value="{{$key}}" class="custom-control-input create_customerCheck customer_mult_check" id="create_user_{{$key}}" checked />
                                                        <label class="custom-control-label px-3" for="create_user_{{$key}}">Create Customer - {{$user_info['customerno']}} </label>
                                                        <i class="fa fa-angle-down customer_header_icon"></i>
                                                    </div>   
                                                </div>
                                            </div>
                                            @endif
                                            <div class="customer_data {{isset($user->id) ? '' : 'backdark'}}" id="create_user_body_{{$key}}">
                                                <h6 class="text-secondary">Contact Information</h6><br>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="user_name">Contact code</label>
                                                        @if(isset($user->id))
                                                            <div class="text-secondary">{{$user_info['contact_info']['contactcode']}}</div>
                                                        @else
                                                            <input type="text" class="form-control required"  required name="contactcode[{{$key}}]" placeholder="Enter Contact Code" value="{{$user_info['contact_info']['contactcode']}}"> 
                                                        @endif    
                                                    </div>
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="user_name">Contact Name</label>
                                                        @if(isset($user->id))
                                                            <div class="text-secondary">{{$user_info['contact_info']['contactname']}}</div>
                                                        @else
                                                            <input type="text" class="form-control required"  required name="contactname[{{$key}}]" placeholder="Enter Contact Name" value="{{$user_info['contact_info']['contactname']}}"> 
                                                        @endif    
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="user_name">Contact Email</label>
                                                        @if(isset($user->id))
                                                            <div class="text-secondary">{{$user_info['contact_info']['emailaddress']}}</div>
                                                        @else
                                                            <input type="text" class="form-control required"  required name="contactemail[{{$key}}]" placeholder="Enter Contact Email" value="{{$user_info['contact_info']['emailaddress']}}"> 
                                                        @endif    
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="user_no">{{ config('constants.label.admin.customer_no') }}</label>
                                                        @if(isset($user->id))
                                                            <div class="text-secondary">{{$user_info['customerno']}}</div>
                                                        @else
                                                            <input type="text" class="form-control required customerno" name="customerno[{{$key}}]" value="{{$user_info['customerno']}}" placeholder="Enter User Number" required>
                                                        @endif
                                                    </div>
    
                                                    {{-- @if($key == 0) --}}
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="user_email">{{ config('constants.label.admin.customer_email') }}</label>
                                                        @if(isset($user->id))
                                                            <div class="text-secondary">{{$user_info['emailaddress']}}</div>
                                                        @else
                                                            <input type="text" class="form-control required emailaddress" name="emailaddress[{{$key}}]" placeholder="Enter User Email" value="{{$user_info['emailaddress']}}" required>
                                                        @endif
                                                    </div>
                                                    {{-- @endif  --}}
                                                </div>
    
                                                <div class="form-row">
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="user_name">{{ config('constants.label.admin.customer_name') }}</label>
                                                        @if(isset($user->id))
                                                            <div class="text-secondary">{{$user_info['customername']}}</div>
                                                        @else
                                                            <input type="text" class="form-control required"  required name="customername[{{$key}}]" placeholder="Enter Name" value="{{$user_info['customername']}}"> 
                                                        @endif    
                                                    </div>
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="ardivision_no">{{ config('constants.label.admin.ar_division_no') }}</label>
                                                        @if(isset($user->id))
                                                            <div class="text-secondary">{{$user_info['ardivisionno']}}</div>
                                                        @else
                                                            <input type="text" class="form-control required" required  name="ardivisionno[{{$key}}]" placeholder="Enter AR division no" value="{{$user_info['ardivisionno']}}">
                                                        @endif 
                                                    </div>
                                                </div>
                                                
                                                <input type="hidden" name="vmi_password" id="contact_vmi_password" value="{{$user_info['contact_info']['vmi_password']}}">
                                                <h6 class="text-secondary">Address</h6><br>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="address_line_1">{{ config('constants.label.admin.address_line_1') }}</label>
                                                        @if(isset($user->id))
                                                            <div class="text-secondary">{{$user_info['addressline1']}}</div>
                                                        @else
                                                            <input type="text" name="addressline1[{{$key}}]" class="form-control" placeholder="Enter Address line 1" value="{{$user_info['addressline1']}}"> 
                                                        @endif    
                                                    </div>
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="address_line_2">{{ config('constants.label.admin.address_line_2') }}</label>
                                                        @if(isset($user->id))
                                                            <div class="text-secondary">{{$user_info['addressline2']}}</div>
                                                        @else
                                                            <input type="text" name="addressline2[{{$key}}]" class="form-control"  placeholder="Enter Address line 2" value="{{$user_info['addressline2']}}"> 
                                                        @endif    
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="address_line_3">{{ config('constants.label.admin.address_line_3') }}</label>
                                                        @if(isset($user->id))
                                                            <div class="text-secondary">{{$user_info['addressline3']}}</div>
                                                        @else
                                                            <input type="text" name="addressline3[{{$key}}]" class="form-control" placeholder="Enter Address line 3" value="{{$user_info['addressline3']}}"> 
                                                        @endif
                                                    </div>
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="user_city">{{ config('constants.label.admin.city') }}</label>
                                                        @if(isset($user->id))
                                                            <div class="text-secondary">{{$user_info['city']}}</div>
                                                        @else
                                                            <input type="text" name="city[{{$key}}]" class="form-control" placeholder="Enter City" value="{{$user_info['city']}}"> 
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="form-row">
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="user_state">{{ config('constants.label.admin.state') }}</label>
                                                        @if(isset($user->id))
                                                            <div class="text-secondary">{{$user_info['state']}}</div>
                                                        @else 
                                                            <input type="text" name="state[{{$key}}]" class="form-control"  placeholder="Enter State" value="{{$user_info['state']}}"> 
                                                        @endif
                                                    </div>
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="user_zipcode">{{ config('constants.label.admin.zipcode') }}</label>
                                                        @if(isset($user->id))
                                                            <div class="text-secondary">{{$user_info['zipcode']}}</div>
                                                        @else    
                                                            <input type="text" name="zipcode[{{$key}}]" class="form-control" placeholder="Enter Zipcode" value="{{$user_info['zipcode']}}"> 
                                                        @endif
                                                    </div>
                                                </div>
    
                                                <h6 class="text-secondary">Benchmark Regional Manager</h6><br>
                                                
                                                <div class="form-row">
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="sales_person_divison_no">{{ config('constants.label.admin.division_no') }} </label>
                                                        @if(isset($user->id))
                                                            <div class="text-secondary">{{$user_info['salespersondivisionno']}}</div>
                                                        @else    
                                                            <input type="text" name="salespersondivisionno[{{$key}}]" class="form-control required" required  placeholder="Enter Division No" value="{{$user_info['salespersondivisionno']}}"> 
                                                        @endif
                                                    </div>
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="sales_person_no">{{ config('constants.label.admin.relational_manager_no') }}</label>
                                                        @if(isset($user->id))
                                                            <div class="text-secondary">{{$user_info['salespersonno']}}</div>
                                                        @else
                                                            <input type="text" name="salespersonno[{{$key}}]" class="form-control required" required placeholder="Enter Sales Person No" value="{{$user_info['salespersonno']}}"> 
                                                        @endif    
                                                    </div>
                                                </div>
                                                
                                                <div class="form-row">
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="sales_person_name">{{ config('constants.label.admin.relational_manager_name') }}</label>
                                                        @if(isset($user->id))
                                                            <div class="text-secondary">{{$user_info['salespersonname']}}</div>
                                                        @else 
                                                            <input type="text" name="salespersonname[{{$key}}]" class="form-control required" required placeholder="Enter Sales Person Name" value="{{$user_info['salespersonname']}}"> 
                                                        @endif    
                                                    </div>
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="sales_person_email">{{ config('constants.label.admin.relational_manager_email') }}</label>
                                                        @if(isset($user->id))
                                                            <div class="text-secondary">{{$user_info['salespersonemail']}}</div>
                                                        @else        
                                                            <input type="text" name="salespersonemail[{{$key}}]" class="form-control required" required  placeholder="Enter Sales Person Email" value="{{$user_info['salespersonemail']}}"> 
                                                        @endif    
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <input type="hidden" name="vmi_companycode[{{$key}}]" value="{{$user_info['vmi_companycode']}}"> 
                                    @endforeach
                                    </div>
                                    
                                    @if(isset($user->id))
                                        <input type="hidden" name="" value="{{$user->id}}" id="user_id">
                                    @endif

                                    @if(empty($user))
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                @if(isset($user->id))
                                                    <button class="btn btn-primary text-capitalize bm-btn-primary btn-rounded pr-4 pl-4 text-capitalize" id="activate_user">{{ config('constants.label.admin.buttons.activate_customer') }}</button>
                                                @else
                                                    <input type="hidden" name="create_user[{{$key}}]" value="1">
                                                    <button class="btn btn-primary bm-btn-primary text-capitalize btn-rounded pr-4 pl-4 create_customers" id="create_customers">{{ config('constants.label.admin.buttons.approve_request') }}</button>
                                                @endif
                                            
                                                <button class="btn bm-btn-delete btn-rounded text-white text-capitalize pr-4 pl-4" id="cancel_user">{{ config('constants.label.admin.buttons.decline_request') }}</button>
                                            </div>
                                        </div>
                                    @else 
                                        @if(isset($user->active) && $user->active == 0)
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    @if(isset($user->id))
                                                        <button class="btn btn-primary text-capitalize bm-btn-primary btn-rounded pr-4 pl-4 text-capitalize" id="activate_user">{{ config('constants.label.admin.buttons.activate_customer') }}</button>
                                                    @else
                                                        <input type="hidden" name="create_user[{{$key}}]" value="1">
                                                        <button class="btn btn-primary bm-btn-primary text-capitalize btn-rounded pr-4 pl-4 create_customers" id="create_customers">{{ config('constants.label.admin.buttons.approve_request') }}</button>
                                                    @endif
                                                
                                                    <button class="btn bm-btn-delete btn-rounded text-white text-capitalize pr-4 pl-4" id="cancel_user">{{ config('constants.label.admin.buttons.decline_request') }}</button>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                            </form> 
                        @else
                            <div class="card mb-3">                        
                                <div class="card-body">
                                    <div class="form-row d-flex flex-wrap mb-0">
                                        @if(isset($userinfo['email']))
                                            <a class="btn btn-primary bm-btn-primary text-capitalize pr-4 btn-rounded pl-4 px-5" href="{{route('admin.users.create')}}?email={{$userinfo['email']}}" target="_blank">{{ config('constants.label.admin.buttons.lookup_customer') }} </a>     
                                        @endif
                                    </div>
                                </div>
                            </div>        
                        @endif        
                </div>
            </div>
        </div>
    </div>            
</div>    
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script> 
   const searchWords = <?php echo json_encode($searchWords); ?>;
    $(document).ready(function() {
        $('.select2').select2();
    })

    $(document).on('click','#activate_user',function(e){
        e.preventDefault();
        $user_id = $('#user_id').val();
        $('.loader-container').removeClass('d-none');
        AjaxRequest('/admin/user/activate','POST',{ "_token": "{{ csrf_token() }}",'user_id':$user_id},'userActivateResponseAction')
    });

    $(document).on('click','#cancel_user',function(e){
        e.preventDefault();
        $user_id = $('#user_id').val();
        $('.loader-container').removeClass('d-none');
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
        $('.loader-container').addClass('d-none');
        if(res.success){
            $('#user_activate_message').text(res.message).removeClass('d-none');
            setTimeout(() => {
                $('#user_activate_message').addClass('d-none');
                window.location.href = "{{ url('/admin/customers/')}}";
            }, 2000);
        }
    }
</script>
@endsection