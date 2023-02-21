
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


<div class="home-content">
    <span class="page_title">Activate Customer</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <!-- data table start -->
                <div class="col-12 mt-5">
                    
                    <div class="card mb-3">
                        <div class="card-body">
                            <h4 class="header-title mb-0">Customer Information</h4>       
                        </div>
                    </div> 
                    @if(empty($customers))
                         <div class="alert alert-danger text-center">Customer not found with the details requested.</div>
                    @endif
                    <div class="alert alert-success d-none text-center" id="user_activate_message"></div>

                    @if(!empty($userinfo))
                    <div class="card mb-3">                        
                        <div class="card-body">
                            <div class="form-row d-flex flex-wrap mb-0">
                                <div class="form-group col-md-12 col-sm-12">                                   
                                    <label class="custom-control-label col-3">Full Name</label>
                                    <span class="col-9 text-white">{{$userinfo['full_name']}}</span>
                                </div>  
                                <div class="form-group col-md-12 col-sm-12">                                   
                                    <label class="custom-control-label col-3">Email Address</label>
                                    <span class="col-9 text-white">{{$userinfo['email']}}</span>
                                </div> 
                                <div class="form-group col-md-12 col-sm-12">                                   
                                    <label class="custom-control-label col-3">Company Name</label>
                                    <span class="col-9 text-white">{{$userinfo['company_name']}}</span>
                                </div> 
                                <div class="form-group col-md-12 col-sm-12 mb-0">                                   
                                    <label class="custom-control-label col-3">Phone No</label>
                                    <span class="col-9 text-white">{{$userinfo['phone_no']}}</span>
                                </div>  
                            </div>
                        </div>
                    </div>    
                    @endif
                    @if(!empty($customers))
                        @include('backend.layouts.partials.messages')            
                        <form action="{{ route('admin.users.store') }}" method="POST" class="form-create-customers">                        
                            @csrf 



                            @foreach($customers as $key => $user_info)
                            <div class="card mb-3">
                                <div class="card-body">
                                    @if(count($customers) > 1)
                                        <div class="form-row">
                                            <div class="form-group col-md-12 col-sm-12 custom-checkbox">
                                                <input type="checkbox" name="create_user[{{$key}}]" value="{{$key}}" class="custom-control-input create_customerCheck" id="create_user_{{$key}}" checked />
                                                <label class="custom-control-label px-3" for="create_user_{{$key}}">Create Customer - {{$user_info['customerno']}} </label>
                                            </div>   
                                        </div>
                                    @endif

                                    <div class="form-row">
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="user_no">Customer No</label>
                                            @if(isset($user->id))
                                                <div class="text-secondary">{{$user_info['customerno']}}</div>
                                            @else
                                                <input type="text" class="form-control required customerno" name="customerno[{{$key}}]" value="{{$user_info['customerno']}}" placeholder="Enter User Number" required>
                                            @endif
                                            
                                        </div>

                                        @if($key == 0)
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="user_email">Customer Email</label>
                                            @if(isset($user->id))
                                                <div class="text-secondary">{{$user_info['emailaddress']}}</div>
                                            @else
                                                <input type="text" class="form-control required emailaddress" name="emailaddress[{{$key}}]" placeholder="Enter User Email" value="{{$user_info['emailaddress']}}" required>
                                            @endif
                                        </div>
                                        @endif 
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="user_name">Customer Name</label>
                                            @if(isset($user->id))
                                                <div class="text-secondary">{{$user_info['customername']}}</div>
                                            @else
                                                <input type="text" class="form-control required"  required name="customername[{{$key}}]" placeholder="Enter Name" value="{{$user_info['customername']}}"> 
                                            @endif    
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="ardivision_no">ardivisionno</label>
                                            @if(isset($user->id))
                                                <div class="text-secondary">{{$user_info['ardivisionno']}}</div>
                                            @else
                                                <input type="text" class="form-control required" required  name="ardivisionno[{{$key}}]" placeholder="Enter AR division no" value="{{$user_info['ardivisionno']}}">
                                            @endif 
                                        </div>
                                    </div>

                                    <h6 class="text-secondary">Address</h6><br>
                                    <div class="form-row">
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="address_line_1">Address Line 1</label>
                                            @if(isset($user->id))
                                                <div class="text-secondary">{{$user_info['addressline1']}}</div>
                                            @else
                                                <input type="text" name="addressline1[{{$key}}]" class="form-control" placeholder="Enter Address line 1" value="{{$user_info['addressline1']}}"> 
                                            @endif    
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="address_line_2">Address Line 2</label>
                                            @if(isset($user->id))
                                                <div class="text-secondary">{{$user_info['addressline2']}}</div>
                                            @else
                                                <input type="text" name="addressline2[{{$key}}]" class="form-control"  placeholder="Enter Address line 2" value="{{$user_info['addressline2']}}"> 
                                            @endif    
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="address_line_3">Address Line 3</label>
                                            @if(isset($user->id))
                                                <div class="text-secondary">{{$user_info['addressline3']}}</div>
                                            @else
                                                <input type="text" name="addressline3[{{$key}}]" class="form-control" placeholder="Enter Address line 3" value="{{$user_info['addressline3']}}"> 
                                            @endif
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="user_city">City</label>
                                            @if(isset($user->id))
                                                <div class="text-secondary">{{$user_info['city']}}</div>
                                            @else
                                                <input type="text" name="city[{{$key}}]" class="form-control" placeholder="Enter City" value="{{$user_info['city']}}"> 
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="user_state">State</label>
                                            @if(isset($user->id))
                                                <div class="text-secondary">{{$user_info['state']}}</div>
                                            @else 
                                                <input type="text" name="state[{{$key}}]" class="form-control"  placeholder="Enter State" value="{{$user_info['state']}}"> 
                                            @endif
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="user_zipcode">Zipcode</label>
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
                                            <label for="sales_person_divison_no">Division No </label>
                                            @if(isset($user->id))
                                                <div class="text-secondary">{{$user_info['salespersondivisionno']}}</div>
                                            @else    
                                                <input type="text" name="salespersondivisionno[{{$key}}]" class="form-control required" required  placeholder="Enter Division No" value="{{$user_info['salespersondivisionno']}}"> 
                                            @endif
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="sales_person_no">Benchmark Regional Manager Number</label>
                                            @if(isset($user->id))
                                                <div class="text-secondary">{{$user_info['salespersonno']}}</div>
                                            @else
                                                <input type="text" name="salespersonno[{{$key}}]" class="form-control required" required placeholder="Enter Sales Person No" value="{{$user_info['salespersonno']}}"> 
                                            @endif    
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="sales_person_name">Benchmark Regional Manager Name</label>
                                            @if(isset($user->id))
                                                <div class="text-secondary">{{$user_info['salespersonname']}}</div>
                                            @else 
                                                <input type="text" name="salespersonname[{{$key}}]" class="form-control required" required placeholder="Enter Sales Person Name" value="{{$user_info['salespersonname']}}"> 
                                            @endif    
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="sales_person_email">Benchmark Regional Manager Email</label>
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
                                    
                                    @if(isset($user->id))
                                        <input type="hidden" name="" value="{{$user->id}}" id="user_id">
                                    @endif
                                <div class="card mb-3">
                                    <div class="card-body">
                                        @if(isset($user->id))
                                            <button class="btn btn-primary btn-rounded pr-4 pl-4" id="activate_user">Activate Customer </button>
                                        @else
                                            <button class="btn btn-primary btn-rounded pr-4 pl-4 create_customers" id="create_customers">Create Customer </button>
                                        @endif
                                    
                                        <button class="btn btn-danger btn-rounded pr-4 pl-4" id="cancel_user">Decline Request</button>
                                    </div>
                                </div>        
                                </form> 
                        @else
                            <div class="card mb-3">                        
                                <div class="card-body">
                                    <div class="form-row d-flex flex-wrap mb-0">   
                                         <a class="btn btn-primary pr-4 btn-rounded pl-4 px-5" href="{{route('admin.users.create')}}?email={{$userinfo['email']}}" target="_blank">Lookup Customer </a>     
                                    </div>
                                </div>
                            </div>        
                        @endif        
                        
                </div>
                <!-- data table end -->
                
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

        console.log(res);
        return false;
        
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