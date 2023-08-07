
@extends('backend.layouts.master')

@section('title')
Admin Create - Admin Panel
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
    <span class="page_title">Create Benchmark User</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            @include('backend.layouts.partials.messages')                    
                            <form action="{{ route('admin.admins.store') }}" class="mt-5" method="POST" enctype="multipart/form-data">
                                @csrf

                                <label for="file-input-admin">Profile Picture</label>

                                <div class="form-row">
                                    <div class="d-flex align-items-center justify-content-center" style="position: relative">
                                        <div class="image-upload position-relative">
                                            <img class="position-relative profile_img_disp_admin" src="/assets/images/profile_account_img2.png" alt="profile Image" height="182" width="182">
                                            <img src="/assets/images/svg/pen_rounded.svg" alt="image upload icon" id="file_input_button_admin" class="position-absolute">
                                            <input id="file-input-admin" name="profile_picture1" type="file" accept=".jpg, .jpeg, .png"/>
                                        </div>  
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12 {{$errors->has('name') ? 'is_error' : '' }}">
                                        <label for="name">{{ config('constants.label.admin.user_name') }} *</label>
                                        @if(old('name'))
                                        <input type="text" class="form-control box-shadow-none" id="name" name="name" placeholder="Enter Name" value="@if(isset($manager['name'])) {{$manager['name']}} @else {{ old('name') }} @endif" autocomplete="off">
                                        @else
                                        <input type="text" class="form-control box-shadow-none" id="name" name="name" placeholder="Enter Name" value="@if(isset($manager['name'])) {{$manager['name']}} @endif" autocomplete="off">
                                        @endif
                                        @if($errors->has('name'))
                                        <div class="invalid-feedback d-block">
                                            {{-- {{ $errors->first('name') }} --}}
                                            {{config('constants.label.admin.field_required')}}
                                        </div>
                                        @endif
                                    </div>

                                    <div class="form-group col-md-6 col-sm-12 {{$errors->has('email') ? 'is_error' : '' }}">
                                        <label for="email">{{ config('constants.label.admin.user_email') }} * </label>
                                        @if(old('email'))
                                        <input type="text" class="form-control box-shadow-none" id="email" name="email" placeholder="Enter Email" value="@if(isset($manager['email'])) {{$manager['email']}} @else {{old('email')}} @endif" autocomplete="off">
                                        @else
                                        <input type="text" class="form-control box-shadow-none" id="email" name="email" placeholder="Enter Email" value="@if(isset($manager['email'])) {{$manager['email']}} @endif" autocomplete="off">
                                        @endif
                                        @if($errors->has('email'))
                                        <div class="invalid-feedback d-block">
                                            {{-- {{ $errors->first('email') }} --}}
                                            {{config('constants.label.admin.field_required')}}
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12 {{$errors->has('password') ? 'is_error' : '' }}">
                                        <label for="password">{{ config('constants.label.admin.password') }} *</label>
                                        @if(old('password'))
                                        <input type="password" class="form-control password-field box-shadow-none" id="password" name="password" placeholder="Enter Password" autocomplete="new-password" value="{{ old('password') }}">
                                        @else
                                        <input type="password" class="form-control password-field box-shadow-none" id="password" name="password" placeholder="Enter Password" autocomplete="new-password" value="">
                                        @endif
                                        @if($errors->has('password'))
                                        <div class="invalid-feedback d-block">
                                            {{-- {{ $errors->first('password') }} --}}
                                            {{config('constants.label.admin.field_required')}}
                                        </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <a  href="javascript:void(0)" class="btn random-password btn-form-control bm-btn-primary text-capitalize btn-primary mt-4 pr-4 pl-4">{{ config('constants.label.admin.generate_random_password') }}</a>
                                    </div> 
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-6">
                                        <label for="password">{{ config('constants.label.admin.assign_roles') }}</label>
                                        <select name="roles[]" id="roles" class="form-control select2">
                                            @foreach ($roles as $role)
                                                @php $selected = "" @endphp
                                                @if(isset($manager['email']) && $role->id == 2)
                                                    @php $selected = "selected" @endphp
                                                @endif
                                                <option value="{{ $role->name }}" {{$selected}}>{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-6 {{$errors->has('username') ? 'is_error' : '' }}">
                                        <label for="username">{{ config('constants.label.admin.user_account_name') }} *</label>
                                        @if(old('username'))
                                            <input type="text" class="form-control box-shadow-none" id="username" name="username" placeholder="Enter Account User Name" value="@if(isset($manager['email'])) {{$manager['email']}} @else {{old('username')}} @endif" autocomplete="off">
                                        @else 
                                            <input type="text" class="form-control box-shadow-none" id="username" name="username" placeholder="Enter Account User Name" value="@if(isset($manager['email'])) {{$manager['email']}} @endif" autocomplete="off">
                                        @endif
                                        @if($errors->has('username'))
                                        <div class="invalid-feedback d-block">
                                            {{-- {{ $errors->first('username') }} --}}
                                            {{config('constants.label.admin.field_required')}}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12 col-sm-12 custom-checkbox">
                                        <input type="checkbox" class="custom-control-input box-shadow-none" name="send_password" value="1" id="send-password" />
                                        <label class="custom-control-label px-3" for="send-password">{{ config('constants.label.admin.send_login_credentials') }}</label>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="password">{{ config('constants.label.admin.phone_no') }}</label>
                                        @if(old('phone_no'))
                                        <input type="text" class="form-control box-shadow-none" id="phone_no" name="phone_no" placeholder="Enter Phone Number" autocomplete="new-password" value="{{ old('phone_no') }}">
                                        @else
                                        <input type="text" class="form-control box-shadow-none" id="phone_no" name="phone_no" placeholder="Enter Phone Number" autocomplete="new-password" value="">
                                        @endif
                                    </div>
                                </div>

                                
                                <button type="submit" class="btn btn-primary btn-rounded text-capitalize mt-4 pr-4 pl-4 fl-right btn-larger">{{ config('constants.label.admin.buttons.create') }}</button>
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
    const searchWords = <?php echo json_encode($searchWords); ?>;
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>
@endsection