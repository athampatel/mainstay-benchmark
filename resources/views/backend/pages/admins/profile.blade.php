
@extends('backend.layouts.master')

@section('title')
Admins - Admin Panel
@endsection

@section('admin-content')
<div class="backdrop d-none">
    <div class="loader"></div>
</div>
<div class="home-content">
    <span class="page_title">Profile</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <div class="col-12 mt-2">
                    <div>
                        <div class="card-body">  
                            <div class="clearfix"></div>
                            <div class="row row-cols-1 col-12 result-data flex-wrap sm-rverse-flex">
                                <div class="col-12 col-md-3 col-lg-3 ">
                                    <div class="card box mb-4 box-shadow-none">						
                                        <div class="card-header col-12 p-3 d-flex align-items-center">
                                            <div class="col-12 d-flex align-items-center">
                                                <div class="box-icon small-icon rounder-border">
                                                    <img src="/assets/images/svg/pen.svg" />
                                                </div>  
                                                <h4 class="mb-0 title-5">Edit Profile</h4>
                                            </div>                    
                                        </div>
                                        <div class="card-body col-12">
                                            <div class="d-flex align-items-center justify-content-center" style="position: relative">
                                                <div class="image-upload position-relative">
                                                    @if(Auth::guard('admin')->user()->profile_path && File::exists(Auth::guard('admin')->user()->profile_path))
                                                    <img src="/{{Auth::guard('admin')->user()->profile_path}}" class="rounded-circle position-relative profile_img_disp_admin" alt="profile Image" height="182" width="182">
                                                    @else 
                                                    <img class="position-relative profile_img_disp_admin" src="/assets/images/profile_account_img2.png" alt="profile Image" height="182" width="182">
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
                                            <div class="title-5 text-white d-none">Change Password</div>
                                            <div class="row d-none">
                                                <div class="mb-3 col-12">    
                                                    <label class="form-label">Password</label>
                                                    <input class="form-control col-12" type="password" placeholder="Password" name="Acc_password" id="Acc_password" autocomplete="off">
                                                </div>                            
                                            </div>
                                            <div class="row d-none">
                                                <div class="mb-3 col-12">    
                                                    <label class="form-label">Confirm Password</label>
                                                    <input class="form-control col-12" type="password" placeholder="Confirm Password" name="Acc_confirm_password" id="Acc_confirm_password">
                                                </div>                            
                                            </div>
                                        </div>      
                                    </div>  
                                    <button type="submit" class="btn btn-primary bm-btn-primary btn-rounded text-capitalize" id="admin-profile-edit-save-button">Update Profile</button> 
                                </div>

                                <div class="col-12 col-md-9  co-lg-9 d-none">
                                    <div class="card box box-shadow-none">	
                                        <div class=" profile-header card-header col-12 p-3 d-flex align-items-center">
                                            <div class="col-12 d-flex align-items-center">
                                                <div class="box-icon small-icon rounder-border">
                                                    @if(Auth::guard('admin')->user()->profile_path)
                                                        <img id="account-detail-profile-img" src="/{{Auth::guard('admin')->user()->profile_path}}" class="rounded-circle"/>
                                                    @else
                                                        <img id="account-detail-profile-img" src="/assets/images/profile_account_img2.png" class="rounded-circle"/>
                                                    @endif
                                                </div>  
                                            </div>                    
                                        </div>    					
                                        <div class="card-body col-12">
                                            <div class="row">
                                                <div class="mb-3 col-6">    
                                                    <label class="form-label" for="acc_name">Name</label>
                                                    <input class="form-control col-12" type="text" value="{{Auth::guard('admin')->user()->name}}" placeholder="Name" name="acc_name" id="acc_name">
                                                </div>
                                                <div class="mb-3 col-6">    
                                                    <label class="form-label">Email Address</label>
                                                    <input class="form-control col-12" type="text" value="{{Auth::guard('admin')->user()->email}}" placeholder="Email Address" name="acc_email_address" id="acc_email_address" disabled>
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
        </div>
    </div>
</div>        

@endsection

@section('scripts')
<script>
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