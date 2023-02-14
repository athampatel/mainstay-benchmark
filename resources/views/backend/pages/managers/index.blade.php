
@extends('backend.layouts.master')

@section('title')
Region Mangers - Admin Panel
@endsection


@section('admin-content')   
<!-- page title area start -->
<div class="home-content">
    <span class="page_title">Region Managers</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <!-- data table start -->
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">                                               
                            <div class="clearfix"></div>
                            <div class="data-tables">
                                @include('backend.layouts.partials.messages')
                                <table id="dataTable" class="text-center datatable-dark">
                                    <thead class="text-capitalize">
                                        <tr>
                                            <th width="10%">Manger No</th>
                                            <th width="10%">Name</th>
                                            <th width="10%">Email</th>                                   
                                            <th width="10%">Account</th>
                                            <th width="10%">Action</th>
                                            <th width="10%">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($managers as $user)
                                    <tr>
                                            <td> <a class="" href="">{{ $user->person_number }}</a></td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>                                                                
                                            <td>
                                                @if($user->user_id != '')  
                                                    <a class="btn btn-success text-white" href="{{ route('admin.admins.edit', $user->user_id) }}" title="View Account"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                    <a class="btn btn-warning text-white" href="{{  route('admin.users.index') }}?manager={{$user->user_id}}" title="View Customers"><i class="fa fa-users" aria-hidden="true"></i></a>
                                                @else
                                                    <a class="btn btn-success text-white" href="{{ route('admin.admins.create') }}/?manager={{$user->id}}" title="Create Account"><i class="fa fa-plus" aria-hidden="true"></i> Create</a>
                                                @endif


                                            </td>
                                            <td>
                                                <a class="btn btn-success text-white" href="{{ route('admin.users.edit', $user->id) }}">Edit</a>
                                            
                                                <a class="btn btn-danger text-white" href="{{ route('admin.users.destroy', $user->id) }}"
                                                onclick="event.preventDefault(); document.getElementById('delete-form-{{ $user->id }}').submit();">
                                                    Delete
                                                </a>
                                            </td>
                                            <td></td>
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