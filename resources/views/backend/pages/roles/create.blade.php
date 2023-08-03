
@extends('backend.layouts.master')

@section('title')
Role Create - Admin Panel
@endsection

@section('styles')
<style>
    .form-check-label {
        text-transform: capitalize;
    }
</style>
@endsection

@section('admin-content')

<div class="home-content">
    <span class="page_title">Create Role </span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            @include('backend.layouts.partials.messages')
                            <form action="{{ route('admin.roles.store') }}" method="POST">
                                @csrf
                                <div class="form-group mb-5">
                                    <label for="name">{{ config('constants.label.admin.role_name') }}</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter a Role Name">
                                </div>
                                <div class="form-group">
                                    <label for="name">{{ config('constants.label.admin.permissions') }}</label>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="checkPermissionAll" value="1">
                                        <label class="form-check-label" for="checkPermissionAll">All</label>
                                    </div>
                                    <hr>
                                    @php $i = 1; @endphp
                                    @foreach ($permission_groups as $group)
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="{{ $i }}Management" value="{{ $group->name }}" onclick="checkPermissionByGroup('role-{{ $i }}-management-checkbox', this)">
                                                    <label class="form-check-label" for="{{ $i }}Management">{{ $group->name }}</label>
                                                </div>
                                            </div>
                                            <div class="col-9 role-{{ $i }}-management-checkbox">
                                                @php
                                                    $permissions = App\User::getpermissionsByGroupName($group->name);
                                                    $j = 1;
                                                @endphp
                                                @foreach ($permissions as $permission)
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" name="permissions[]" id="checkPermission{{ $permission->id }}" value="{{ $permission->name }}">
                                                        <label class="form-check-label" for="checkPermission{{ $permission->id }}">{{ $permission->name }}</label>
                                                    </div>
                                                    @php  $j++; @endphp
                                                @endforeach
                                                <br>
                                            </div>
                                        </div>
                                        @php  $i++; @endphp
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-primary btn-rounded text-capitalize mt-4 pr-4 pl-4 fl-right btn-larger">{{ config('constants.label.admin.buttons.save_role') }}</button>
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
     @include('backend.pages.roles.partials.scripts')
     <script>
      const searchWords = <?php echo json_encode($searchWords); ?>;  
     </script>
@endsection