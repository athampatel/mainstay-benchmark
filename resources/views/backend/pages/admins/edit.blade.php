
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
                            <h4 class="header-title">Edit User - {{ $admin->name }}</h4>
                            @if(!$errors->any()) 
                                @include('backend.layouts.partials.messages')
                            @endif
                            <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST" enctype="multipart/form-data" id="admin_edit_form">
                                @method('PUT')
                                @csrf
                                
                                <label for="file-input-admin">Profile Picture</label>
                                <div class="form-row">
                                    <div class="d-flex align-items-center justify-content-center" style="position: relative">
                                        <div class="image-upload position-relative"  @if($admin->profile_path && File::exists($admin->profile_path)) style="background-image:url(/{{$admin->profile_path}})" @endif>
                                            @if($admin->profile_path && File::exists($admin->profile_path))
                                            <img src="/{{$admin->profile_path}}" class="rounded-circle position-relative profile_img_disp_admin" alt="profile Image" height="182" width="182">
                                            @else 
                                            <img class="position-relative profile_img_disp_admin" src="/assets/images/profile_account_img2.png" alt="profile Image" height="182" width="182">
                                            @endif
                                            <img src="/assets/images/svg/pen_rounded.svg" alt="image upload icon" id="file_input_button_admin" class="position-absolute">
                                            <input id="file-input-admin" name="profile_picture" type="file" accept=".jpg, .jpeg, .png"/>
                                        </div>  
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-6 {{$errors->has('name') ? 'is_error' : '' }}">                                       
                                        <label for="username">{{ config('constants.label.admin.user_name') }}</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required value="{{ $admin->name }}">
                                        {{-- <input type="hidden" name="name" value="{{ $admin->name }}"> --}}
                                        @if($errors->has('name'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('name') }}
                                        </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12 {{$errors->has('email') ? 'is_error' : '' }}">
                                        <label for="email">{{ config('constants.label.admin.user_email') }}</label>
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="{{ $admin->email }}">
                                        @if($errors->has('email'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('email') }} 
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-6">
                                        <label for="password">{{ config('constants.label.admin.assign_roles') }}</label>
                                        <select name="roles[]" id="roles" class="form-control select2">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}" {{ $admin->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6 col-sm-12 {{$errors->has('username') ? 'is_error' : '' }}">
                                        <label for="name">{{ config('constants.label.admin.user_account_name') }}</label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter Name" value="{{ $admin->username }}">
                                        @if($errors->has('username'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('username') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12 {{$errors->has('password') ? 'is_error' : '' }}">
                                        <label for="password">{{ config('constants.label.admin.password') }}</label>
                                        <input type="password" class="form-control password-field" id="password" name="password" placeholder="Enter Password">
                                        @if($errors->has('password'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('password') }}
                                        </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <a  href="javascript:void(0)" class="btn random-password btn-form-control btn-primary text-capitalize mt-4 pr-4 pl-4">{{ config('constants.label.admin.generate_random_password') }}</a>
                                    </div>   
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="phone_no">{{ config('constants.label.admin.phone_no') }}</label>
                                        <input type="text" class="form-control" id="phone_no" name="phone_no" placeholder="Enter Phone Number" value="{{$admin->phone_no}}">
                                    </div>
                                </div>
                                
                                {{-- <button type="submit" class="btn btn-primary text-capitalize btn-rounded mt-4 pr-4 pl-4" id="admin_user_edit_save">{{ config('constants.label.admin.buttons.save_admin') }}</button> --}}
                                <button type="submit" class="btn btn-primary btn-rounded text-capitalize mt-4 pr-4 pl-4 fl-right btn-larger" id="admin_user_edit_save">{{ config('constants.label.admin.buttons.save_admin') }}</button>
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
    const searchWords = <?php echo json_encode($searchWords); ?>;
</script>
@endsection