
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
                                        <label for="name">User Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="@if(isset($manager['name'])) {{$manager['name']}} @endif">
                                    </div>
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="email">User Email</label>
                                        <input type="text" class="form-control" id="email" name="email" placeholder="" value="@if(isset($manager['email'])) {{$manager['email']}} @endif">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-12">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control password-field" id="password" name="password" placeholder="Enter Password" value="">
                                    </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <!--  <label for="password_confirmation">Confirm Password</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Enter Password" value="">
                                        --->
                                        <a  href="javascript:void(0)" class="btn random-password btn-form-control btn-primary mt-4 pr-4 pl-4">Gernerate Random Password</a>
                                </div> 
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6 col-sm-6">
                                        <label for="password">Assign Roles</label>
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
                                        <label for="username">User Account Name</label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required value="@if(isset($manager['email'])) {{$manager['email']}} @endif">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12 col-sm-12 custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="send_password" value="1" id="send-password" />
                                        <label class="custom-control-label px-3" for="send-password">Send Login Credentials</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-rounded mt-4 pr-4 pl-4">Create</button>
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
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>
@endsection