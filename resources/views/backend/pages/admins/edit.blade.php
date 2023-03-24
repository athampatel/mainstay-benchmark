
@extends('backend.layouts.master')

@section('title')
Admin Edit - Admin Panel
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
    <span class="page_title">Edit User - {{ $admin->name }}</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Edit Admin - {{ $admin->name }}</h4>
                            @include('backend.layouts.partials.messages')
                            {{-- {{dd($admin->profile_path)}} --}}
                            <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST" enctype="multipart/form-data" id="admin_edit_form">
                                @method('PUT')
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        {{-- <label for="name">Admin Name</label> --}}
                                        <label for="name">{{ config('constants.label.admin.admin_name') }}</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ $admin->name }}">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        {{-- <label for="email">Admin Email</label> --}}
                                        <label for="email">{{ config('constants.label.admin.admin_email') }}</label>
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="{{ $admin->email }}">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        {{-- <label for="password">Password</label> --}}
                                        <label for="password">{{ config('constants.label.admin.password') }}</label>
                                        <input type="password" class="form-control password-field" id="password" name="password" placeholder="Enter Password">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        {{-- <a  href="javascript:void(0)" class="btn random-password btn-form-control btn-primary mt-4 pr-4 pl-4">Gernerate Random Password</a> --}}
                                        <a  href="javascript:void(0)" class="btn random-password btn-form-control btn-primary text-capitalize mt-4 pr-4 pl-4">{{ config('constants.label.admin.generate_random_password') }}</a>
                                    </div>   
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-6">
                                        {{-- <label for="password">Assign Roles</label> --}}
                                        <label for="password">{{ config('constants.label.admin.assign_roles') }}</label>
                                        <select name="roles[]" id="roles" class="form-control select2">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}" {{ $admin->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-6">
                                        {{-- <label for="username">Admin Username</label> --}}
                                        <label for="username">{{ config('constants.label.admin.admin_username') }}</label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required value="{{ $admin->username }}">
                                    </div>
                                </div>
                                
                                {{-- profile upload --}}
                                <h6>Profile Picture</h6><br>
                                <div class="form-row">
                                    <div class="d-flex align-items-center justify-content-center" style="position: relative">
                                        <div class="image-upload position-relative">
                                            @if($admin->profile_path)
                                            <img src="/{{$admin->profile_path}}" class="rounded-circle position-relative profile_img_disp_admin" alt="profile Image" height="182" width="182">
                                            @else 
                                            <img class="position-relative profile_img_disp_admin" src="/assets/images/profile_account_img2.png" alt="profile Image" height="182" width="182">
                                            @endif
                                            <img src="/assets/images/svg/pen_rounded.svg" alt="image upload icon" id="file_input_button_admin" class="position-absolute">
                                            <input id="file-input-admin" name="profile_picture" type="file" accept=".jpg, .jpeg, .png"/>
                                        </div>  
                                    </div>
                                </div>

                                {{-- <button type="submit" class="btn btn-primary btn-rounded mt-4 pr-4 pl-4">Save Admin</button> --}}
                                {{-- <button type="submit" class="btn btn-primary text-capitalize btn-rounded mt-4 pr-4 pl-4">{{ config('constants.label.admin.buttons.save_admin') }}</button> --}}
                                <button type="submit" class="btn btn-primary text-capitalize btn-rounded mt-4 pr-4 pl-4" id="admin_user_edit_save">{{ config('constants.label.admin.buttons.save_admin') }}</button>
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
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>
@endsection