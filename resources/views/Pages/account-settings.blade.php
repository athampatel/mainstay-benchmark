@extends('layouts.dashboard')

@section('title')
{{config('constants.page_title.customers.account_setting')}} - Benchmark
@endsection

@section('content')
<div class="backdrop d-none">
    <div class="loader"></div>
</div>
<div class="home-content">
    <h1 class="page_title px-5 pt-3">Account Settings</h1>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="row row-cols-1 col-12 result-data">
            <div class="col-12">
                <div class="card box">						
                    <div class="card-body col-12">
                        <h3 class="title-4 m-0 text-primary"><span>{{Auth::user()->name}}</span></h3>
                    </div>      
                </div>   
             </div>
        </div>
        <div class="row row-cols-1 col-12 result-response d-none">
            <div class="alert alert-success text-center" id="result-response-message"></div>
        </div>
        <div class="row row-cols-1 col-12 result-data flex-wrap sm-rverse-flex">
            <div class="col-12 col-md-3 col-lg-3 ">
                <div class="card box mb-4">						
                    <div class="card-header col-12 p-3 d-flex align-items-center">
                        <div class="col-12 d-flex align-items-center">
                            <div class="box-icon small-icon rounder-border">
                                <img src="assets/images/svg/pen.svg" />
                            </div>  
                            {{-- <h4 class="mb-0 title-5">Edit Customer Details</h4> --}}
                            <h4 class="mb-0 title-5">Edit Contact Details</h4>
                        </div>                    
                    </div>
                    <div class="card-body col-12">
                        <div class="d-flex align-items-center justify-content-center" style="position: relative">
                            <div class="image-upload position-relative">
                                @if(Auth::user()->profile_image)
                                <img src="/{{Auth::user()->profile_image}}" class="rounded-circle position-relative profile_img_disp" alt="profile Image" height="182" width="182">
                                @else 
                                <img class="position-relative profile_img_disp" src="/assets/images/profile_account_img2.png" alt="profile Image" height="182" width="182">
                                @endif
                                <img src="/assets/images/svg/pen_rounded.svg" alt="image upload icon" id="file_input_button" class="position-absolute">
                                <input id="file-input" type="file" accept=".jpg, .jpeg, .png"/>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                {{-- <label class="form-label">Name</label> --}}
                                <label class="form-label">Contact Name</label>
                                @php 
                                $selected_customer = session('selected_customer');
                                @endphp
                                <input class="form-control col-12" type="text" value="{{Auth::user()->name}}" placeholder="Name" name="Acc_name" id="Acc_name" disabled>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                {{-- <label class="form-label">Name</label> --}}
                                <label class="form-label">Contact Email</label>
                                <input class="form-control col-12" type="text" value="{{Auth::user()->email}}" placeholder="Name" name="Acc_name" id="Acc_name" disabled>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                {{-- <label class="form-label">Name</label> --}}
                                <label class="form-label">Contact Code</label>
                                <input class="form-control col-12" type="text" value="{{$selected_customer['contactcode']}}" placeholder="Name" name="Acc_name" id="Acc_name" disabled>
                            </div>                            
                        </div>
                        <div class="title-5 text-white">Change Password</div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                <label class="form-label">Password</label>
                                <input class="form-control col-12" type="password" placeholder="Password" name="Acc_password" id="Acc_password" autocomplete="off">
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                <label class="form-label">Confirm Password</label>
                                <input class="form-control col-12" type="password" placeholder="Confirm Password" name="Acc_confirm_password" id="Acc_confirm_password">
                            </div>                            
                        </div>
                    </div>      
                </div>  
                <button type="submit" class="btn btn-primary btn-rounded" id="profile-edit-save-button">Update Changes</button> 
            </div>
            <div class="col-12 col-md-9 co-lg-9">
                <div class="">
                    <div class="card box">	
                        <div class=" profile-header card-header col-12 p-3 d-flex align-items-center">
                            <div class="col-12 d-flex align-items-center">
                                <div class="box-icon small-icon rounder-border">
                                    @if(Auth::user()->profile_image)
                                        <img id="account-detail-profile-img" src="/{{Auth::user()->profile_image}}" class="rounded-circle" style="max-width:100%;height:100%;" />
                                    @else
                                        <img id="account-detail-profile-img" src="/assets/images/profile_account_img2.png" class="rounded-circle" style="max-width:100%; height:100%" />
                                    @endif
                                </div>  
                            </div>                    
                        </div>    					
                        <div class="card-body col-12">
                            <div class="row">
                                <div class="mb-3 col-6">    
                                    <label class="form-label" for="acc_name">Name</label>
                                    <input class="form-control col-12" type="text" value="{{$user_detail->customername}}" placeholder="Name" name="acc_name" id="acc_name" disabled>
                                </div>
                                <div class="mb-3 col-6">    
                                    <label class="form-label">Customer Number</label>
                                    <input class="form-control  col-12" type="text" value="{{$user_detail->customerno}}" placeholder="Customer Number" name="acc_customer_number" id="acc_customer_number" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-6">    
                                    <label class="form-label">Email Address</label>
                                    <input class="form-control col-12" type="text" value="{{$user_detail->email}}" placeholder="Email Address" name="acc_email_address" id="acc_email_address" disabled>
                                </div>
                                {{-- test work start --}}
                                @php
                                $selected_customer = session('selected_customer');
                                $phone_numbers = explode(' ',$selected_customer['phone_no']);
                                // dd($selected_customer,$phone_numbers);
                                // dd($phone_numbers);
                                $phone_extension = "";
                                $phone_number = "";
                                if(!empty($phone_numbers) && count($phone_numbers) == 2) {
                                    $phone_extension = $phone_numbers[0];
                                    $phone_number = $phone_numbers[1];
                                }
                                @endphp
                                <div class="mb-3 col-6">
                                    <div class="row">
                                        <div class="col-3">
                                            <label class="form-label">Phone Extension</label>
                                            <input class="form-control col-12" type="text" value="{{$phone_extension}}" placeholder="Phone Extension" name="acc_phone_no" id="acc_phone_no" disabled>       
                                        </div>
                                        <div class="col-9">
                                            <label class="form-label">Phone Number</label>
                                            <input class="form-control col-12" type="text" value="{{$phone_number}}" placeholder="Phone Number" name="acc_phone_no" id="acc_phone_no" disabled>
                                        </div>
                                    </div>
                                </div>
                                {{-- test work end --}}
                                {{-- <div class="mb-3 col-6">    
                                    <label class="form-label">Phone Number</label>
                                    <input class="form-control col-12" type="text" value="" placeholder="Phone Number" name="acc_phone_no" id="acc_phone_no">
                                </div> --}}
                            </div>
                            <div class="row">
                                <div class="mb-3 col-12">    
                                    <label class="form-label">Address Line 1</label>
                                    <input class="form-control col-12" type="text" value="{{$user_detail->addressline1}}" placeholder="Address Line 1" name="acc_address_line_1" id="acc_address_line_1" disabled>
                                </div>          
                            </div>
                            <div class="row">
                                <div class="mb-3 col-12">    
                                    <label class="form-label">Address Line 2</label>
                                    <input class="form-control col-12" type="text" value="{{$user_detail->addressline2}}" placeholder="Address Line 2" name="acc_address_line_2" id="acc_address_line_2" disabled>
                                </div>          
                            </div>
                            <div class="row">
                                <div class="mb-3 col-6">    
                                    <label class="form-label">State</label>
                                    <input class="form-control col-12" type="text" value="{{$user_detail->state}}" placeholder="State" name="acc_state" id="acc_state" disabled>
                                </div>
                                <div class="mb-3 col-6">    
                                    <label class="form-label">City</label>
                                    <input class="form-control col-12" type="text" value="{{$user_detail->city}}" placeholder="City" name="acc_city" id="acc_city" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-6">    
                                    <label class="form-label">Zip code</label>
                                    <input class="form-control col-12" type="text" value="{{$user_detail->zipcode}}" placeholder="Zip Code" name="acc_zipcode" id="acc_zipcode" disabled>
                                </div>
                                <div class="mb-3 col-6">    
                                    <label class="form-label">Division Number</label>
                                    <input class="form-control col-12" type="text" placeholder="Division Number" name="acc_division_no" id="acc_division_no" disabled>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                    <label class="form-label">Benchmark Regional Manager Name</label>
                                    <input class="form-control col-12" value="{{$sales_person['name']}}" type="text" placeholder="Benchmark Regional Manager Name" name="acc_manager_name" id="acc_manager_name" disabled>
                                </div>
                                <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                    <label class="form-label">Benchmark Regional Manager Email</label>
                                    <input class="form-control col-12" value="{{$sales_person['email']}}" type="text" placeholder="Benchmark Regional Manager Email" name="acc_manager_email" id="acc_manager_email" disabled>
                                </div>
                                <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                    <label class="form-label">Benchmark Regional Manager Phone Number</label>
                                    <input class="form-control col-12" value="{{$sales_person['person_number']}}" type="text" placeholder="Benchmark Regional Manager Phone Number" name="acc_manager_phone_number" id="acc_manager_phone_number" disabled>
                                </div>
                            </div> --}}
                        </div>                     
                    </div>
                    <div class="card box">	
                        <div class=" profile-header card-header col-12 p-3 d-flex align-items-center">
                            <div class="col-12 d-flex align-items-center">
                                <div class="box-icon small-icon rounder-border">
                                    @if($sales_person['profile_path'])
                                        <img id="account-detail-profile-img" src="/{{$sales_person['profile_path']}}" class="rounded-circle" style="max-width:100%;height:100%;" />
                                    @else
                                        <img id="account-detail-profile-img" src="/assets/images/profile_account_img2.png" class="rounded-circle" style="max-width:100%; height:100%" />
                                    @endif
                                </div>  
                            </div>                    
                        </div>    					
                        <div class="card-body col-12">
                            <div class="row">
                                <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                    <label class="form-label">Benchmark Regional Manager Name</label>
                                    <input class="form-control col-12" value="{{$sales_person['name']}}" type="text" placeholder="Benchmark Regional Manager Name" name="acc_manager_name" id="acc_manager_name" disabled>
                                </div>
                                <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                    <label class="form-label">Benchmark Regional Manager Email</label>
                                    <input class="form-control col-12" value="{{$sales_person['email']}}" type="text" placeholder="Benchmark Regional Manager Email" name="acc_manager_email" id="acc_manager_email" disabled>
                                </div>
                                <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                    <label class="form-label">Benchmark Regional Manager Phone Number</label>
                                    <input class="form-control col-12" value="{{$sales_person['person_number']}}" type="text" placeholder="Benchmark Regional Manager Phone Number" name="acc_manager_phone_number" id="acc_manager_phone_number" disabled>
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
    <script>
        const constants = <?php echo json_encode($constants); ?>;
        const searchWords = <?php echo json_encode($searchWords); ?>;
    </script>
@endsection


<style>
.image-upload>input {
    display: none;
}
.backdrop{
    height: 100%;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    z-index: 2;
    background-color: rgba(255, 255, 255, .5);
}

.loader {
    position: absolute;
    border-radius: 50%;
    border: #A8CB5C 5px solid;
    border-left-color: transparent;
    width: 36px;
    height: 36px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>