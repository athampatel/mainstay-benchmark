
@extends('backend.layouts.master')

@section('title')
Admins - Admin Panel
@endsection

@section('admin-content')

<div class="home-content">
    <span class="page_title">Porfile</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <div class="col-12 mt-2">
                    <div class="card">
                        <div class="card-body">  
                            <div class="clearfix"></div>
                            <div class="row row-cols-1 col-12 result-data flex-wrap sm-rverse-flex">
                                <div class="col-12 col-md-3 col-lg-3 ">
                                    <div class="card box mb-4 box-shadow-none">						
                                        <div class="card-header col-12 p-3 d-flex align-items-center">
                                            <div class="col-12 d-flex align-items-center">
                                                <div class="box-icon small-icon rounder-border">
                                                    <img src="assets/images/svg/pen.svg" />
                                                </div>  
                                                <h4 class="mb-0 title-5">Edit Customer Details</h4>
                                            </div>                    
                                        </div>
                                        <div class="card-body col-12">
                                            <div class="d-flex align-items-center justify-content-center" style="position: relative">
                                                <div class="image-upload position-relative">
                                                    @if(Auth::guard('admin')->user()->profile_path)
                                                    <img src="/{{Auth::guard('admin')->user()->profile_path}}" class="rounded-circle position-relative profile_img_disp" alt="profile Image" height="182" width="182">
                                                    @else 
                                                    <img class="position-relative profile_img_disp" src="/assets/images/profile_account_img2.png" alt="profile Image" height="182" width="182">
                                                    @endif
                                                    <img src="/assets/images/svg/pen_rounded.svg" alt="image upload icon" id="file_input_button_admin" class="position-absolute">
                                                    <input id="file-input-admin" type="file" accept=".jpg, .jpeg, .png"/>
                                                </div>  
                                            </div>
                                            <div class="row">
                                                <div class="mb-3 col-12">    
                                                    <label class="form-label">Name</label>
                                                    <input class="form-control col-12" type="text" value="{{Auth::guard('admin')->user()->name}}" placeholder="Name" name="Acc_name" id="Acc_name" disabled>
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
                                    <button type="submit" class="btn btn-primary bm-btn-primary btn-rounded text-capitalize" id="admin-profile-edit-save-button">Update Changes</button> 
                                </div>
                    
                                {{-- customer detail section --}}
                                <div class="col-12 col-md-9  co-lg-9">
                                    <div class="card box box-shadow-none">	
                                        <div class=" profile-header card-header col-12 p-3 d-flex align-items-center">
                                            <div class="col-12 d-flex align-items-center">
                                                {{-- <div class="box-icon small-icon rounder-border"> --}}
                                                <div class="box-icon small-icon rounder-border">
                                                    @if(Auth::guard('admin')->user()->profile_path)
                                                        <img id="account-detail-profile-img" src="/{{Auth::guard('admin')->user()->profile_path}}" class="rounded-circle" style="max-width:100%;height:100%;" />
                                                    @else
                                                        <img id="account-detail-profile-img" src="/assets/images/profile_account_img2.png" class="rounded-circle" style="max-width:100%; height:100%" />
                                                    @endif
                                                </div>  
                                                {{-- <h4 class="mb-0 title-5">Order Details</h4> --}}
                                            </div>                    
                                        </div>    					
                                        <div class="card-body col-12">
                                            <div class="row">
                                                <div class="mb-3 col-6">    
                                                    <label class="form-label" for="acc_name">Name</label>
                                                    <input class="form-control col-12" type="text" value="{{Auth::guard('admin')->user()->name}}" placeholder="Name" name="acc_name" id="acc_name">
                                                </div>
                                                {{-- <div class="mb-3 col-6">    
                                                    <label class="form-label">Customer Number</label>
                                                    <input class="form-control  col-12" type="text" value="" placeholder="Customer Number" name="acc_customer_number" id="acc_customer_number" disabled>
                                                </div> --}}
                                                <div class="mb-3 col-6">    
                                                    <label class="form-label">Email Address</label>
                                                    <input class="form-control col-12" type="text" value="{{Auth::guard('admin')->user()->email}}" placeholder="Email Address" name="acc_email_address" id="acc_email_address" disabled>
                                                </div>
                                            </div>
                                            {{-- <div class="row">
                                                <div class="mb-3 col-6">    
                                                    <label class="form-label">Email Address</label>
                                                    <input class="form-control col-12" type="text" value="" placeholder="Email Address" name="acc_email_address" id="acc_email_address" disabled>
                                                </div> --}}
                                                {{-- <div class="mb-3 col-6">    
                                                    <label class="form-label">Phone Number</label>
                                                    <input class="form-control col-12" type="text" value="" placeholder="Phone Number" name="acc_phone_no" id="acc_phone_no">
                                                </div> --}}
                                            {{-- </div> --}}
                                            {{-- <div class="row"> --}}
                                                {{-- <div class="mb-3 col-12">    
                                                    <label class="form-label">Address Line 1</label>
                                                    <input class="form-control col-12" type="text" value="" placeholder="Address Line 1" name="acc_address_line_1" id="acc_address_line_1">
                                                </div>           --}}
                                            {{-- </div> --}}
                                            {{-- <div class="row"> --}}
                                                {{-- <div class="mb-3 col-12">    
                                                    <label class="form-label">Address Line 2</label>
                                                    <input class="form-control col-12" type="text" value="" placeholder="Address Line 2" name="acc_address_line_2" id="acc_address_line_2">
                                                </div>           --}}
                                            {{-- </div> --}}
                                            {{-- <div class="row"> --}}
                                                {{-- <div class="mb-3 col-6">    
                                                    <label class="form-label">State</label>
                                                    <input class="form-control col-12" type="text" value="" placeholder="State" name="acc_state" id="acc_state">
                                                </div> --}}
                                                {{-- <div class="mb-3 col-6">    
                                                    <label class="form-label">City</label>
                                                    <input class="form-control col-12" type="text" value="" placeholder="City" name="acc_city" id="acc_city">
                                                </div> --}}
                                            {{-- </div> --}}
                                            {{-- <div class="row"> --}}
                                                {{-- <div class="mb-3 col-6">    
                                                    <label class="form-label">Zip code</label>
                                                    <input class="form-control col-12" type="text" value="" placeholder="Zip Code" name="acc_zipcode" id="acc_zipcode">
                                                </div> --}}
                                                {{-- <div class="mb-3 col-6">    
                                                    <label class="form-label">Division Number</label>
                                                    <input class="form-control col-12" type="text" placeholder="Division Number" name="acc_division_no" id="acc_division_no" disabled>
                                                </div> --}}
                                            {{-- </div> --}}
                                            {{-- <div class="row"> --}}
                                                {{-- <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                                    <label class="form-label">BenchMark Relational Manager Name</label>
                                                    <input class="form-control col-12" type="text" placeholder="BenchMark Relational Manager Name" name="acc_manager_name" id="acc_manager_name" disabled>
                                                </div> --}}
                                                {{-- <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                                    <label class="form-label">BenchMark Relational Manager Email</label>
                                                    <input class="form-control col-12" type="text" placeholder="BenchMark Relational Manager Email" name="acc_manager_email" id="acc_manager_email" disabled>
                                                </div> --}}
                                                {{-- <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                                    <label class="form-label">BenchMark Relational Manager Phone Number</label>
                                                    <input class="form-control col-12" type="text" placeholder="BenchMark Relational Manager Phone Number" name="acc_manager_phone_number" id="acc_manager_phone_number" disabled>
                                                </div> --}}
                                            {{-- </div> --}}
                                            {{-- <div class="row">
                                                <div class="mb-3 col-6">    
                                                    <label class="form-label">BenchMark Relational Manager Phone Number</label>
                                                    <input class="form-control col-12" type="text" placeholder="BenchMark Relational Manager Phone Number" name="acc_manager_phone_number" id="acc_manager_phone_number" disabled>
                                                </div>
                                            </div> --}}
                                        </div>                     
                                    </div>
                                    {{-- <button type="submit" class="btn btn-primary" id="order-save-button">Save Changes</button> --}}
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
function deleteAdmin(id){
    Swal.fire({
    title: 'Are you sure?',
    text: "You want to delete the admin",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#9FCC47',
    cancelButtonColor: '#424448',
    confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    })
}
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