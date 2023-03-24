
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
<!-- page title area start -->
<div class="home-content">
    <span class="page_title">Create Staff / Admin User</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <!-- data table start -->
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            
                            @include('backend.layouts.partials.messages')                    
                            <form action="{{ route('admin.admins.store') }}" class="mt-5" method="POST">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        {{-- <label for="name">User Name</label> --}}
                                        <label for="name">{{ config('constants.label.admin.user_name') }}</label>
                                        <input type="text" class="form-control box-shadow-none" id="name" name="name" placeholder="Enter Name" value="@if(isset($manager['name'])) {{$manager['name']}} @endif">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        {{-- <label for="email">User Email</label> --}}
                                        <label for="email">{{ config('constants.label.admin.user_email') }}</label>
                                        <input type="text" class="form-control box-shadow-none" id="email" name="email" placeholder="Enter Email" value="@if(isset($manager['email'])) {{$manager['email']}} @endif">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        {{-- <label for="password">Password</label> --}}
                                        <label for="password">{{ config('constants.label.admin.password') }}</label>
                                        <input type="password" class="form-control password-field box-shadow-none" id="password" name="password" placeholder="Enter Password" value="">
                                    </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    {{-- <a  href="javascript:void(0)" class="btn random-password btn-form-control btn-primary mt-4 pr-4 pl-4">Gernerate Random Password</a> --}}
                                    <a  href="javascript:void(0)" class="btn random-password btn-form-control bm-btn-primary text-capitalize btn-primary mt-4 pr-4 pl-4">{{ config('constants.label.admin.generate_random_password') }}</a>
                                </div> 
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-6">
                                        {{-- <label for="password">Assign Roles</label> --}}
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
                                    <div class="form-group col-md-6 col-sm-6">
                                        {{-- <label for="username">User Account Name</label> --}}
                                        <label for="username">{{ config('constants.label.admin.user_account_name') }}</label>
                                        <input type="text" class="form-control box-shadow-none" id="username" name="username" placeholder="Enter Account User Name" value="@if(isset($manager['email'])) {{$manager['email']}} @endif">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12 col-sm-12 custom-checkbox">
                                        <input type="checkbox" class="custom-control-input box-shadow-none" name="send_password" value="1" id="send-password" />
                                        {{-- <label class="custom-control-label px-3" for="send-password">Send Login Credentials</label> --}}
                                        <label class="custom-control-label px-3" for="send-password">{{ config('constants.label.admin.send_login_credentials') }}</label>
                                    </div>
                                </div>
                                {{-- <button type="submit" class="btn btn-primary btn-rounded mt-4 pr-4 pl-4">Create</button> --}}
                                <button type="submit" class="btn btn-primary btn-rounded text-capitalize mt-4 pr-4 pl-4">{{ config('constants.label.admin.buttons.create') }}</button>
                            </form>
                        </div>
                    </div>
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
    const searchWords = <?php echo json_encode($searchWords); ?>;
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>
@endsection