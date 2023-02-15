
@extends('backend.layouts.master')

@section('title')
Role Page - Admin Panel
@endsection


@section('admin-content')
<div class="home-content">
    <span class="page_title">Roles</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <!-- data table start -->
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title float-left">Roles List</h4>
                            <p class="float-right mb-2">
                                @if (Auth::guard('admin')->user()->can('role.create'))
                                    <a class="btn btn-primary text-white" href="{{ route('admin.roles.create') }}">Create New Role</a>
                                @endif
                            </p>
                            <div class="clearfix"></div>
                            <div class="data-tables table-responsive">
                                @include('backend.layouts.partials.messages')
                                <table id="dataTable" class="text-center datatable-dark">
                                    <thead class="text-capitalize">
                                        <tr>
                                            <th width="5%">Sl</th>
                                            <th width="10%">Name</th>
                                            <th width="60%">Permissions</th>
                                            <th width="15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($roles as $role)
                                    <tr>
                                            <td>{{ $loop->index+1 }}</td>
                                            <td>{{ $role->name }}</td>
                                            <td>
                                                <div class="d-flex flex-wrap justify-content-center">    
                                                @foreach ($role->permissions as $perm)
                                                    <span class="badge badge-info mr-1">
                                                        {{ $perm->name }}
                                                    </span>
                                                @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-wrapper btns-2">
                                                @if (Auth::guard('admin')->user()->can('admin.edit'))
                                                    <a class="btn btn-rounded btn-medium btn-primary" href="{{ route('admin.roles.edit', $role->id) }}">Edit</a>
                                                @endif

                                                @if (Auth::guard('admin')->user()->can('admin.edit'))
                                                    <a class="btn btn-rounded btn-medium btn-bordered" href="{{ route('admin.roles.destroy', $role->id) }}"
                                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $role->id }}').submit();">
                                                        Delete
                                                    </a>

                                                    <form id="delete-form-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display: none;">
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- data table end -->
                
            </div>
        </div>
    </div>
</div>    
@endsection
