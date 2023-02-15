
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
                            <div class="data-tables table-responsive">
                                @include('backend.layouts.partials.messages')
                                <table id="dataTable" class="text-center datatable-dark">
                                    <thead class="text-capitalize">
                                        <tr>
                                            <th width="10%">Manger No</th>
                                            <th width="10%">Name</th>
                                            <th width="10%">Email</th>                                   
                                            <th width="10%">Account</th>
                                            <th width="10%">Action</th>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($managers as $user)
                                    <tr>
                                            <td> <a class="" href="">{{ $user->person_number }}</a></td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>                                                                
                                            <td>

                                           
                                                  

                                                <div class="status-btns">
                                                    @if($user->user_id != '')                                                         
                                                        <a class="btn btn-rounded btn-medium btn-bordered" href="{{  route('admin.users.index') }}?manager={{$user->user_id}}" title="View Customers">Customers</a>
                                                    @else
                                                        <a class="btn btn-rounded btn-light text-dark" href="{{ route('admin.admins.create') }}/?manager={{$user->id}}" title="Create Account">Create</a>
                                                    @endif
                                                </div>

                                            </td>
                                            <td>


                                                <div class="btn-wrapper btns-2">
                                                    <a class="btn btn-rounded btn-medium btn-primary" href="{{ route('admin.users.edit', $user->id) }}">View</a>
                                                
                                                  <!--  <a class="btn btn-rounded btn-medium btn-bordered" href="{{ route('admin.users.destroy', $user->id) }}"
                                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $user->id }}').submit();">
                                                        Delete
                                                    </a> -->
                                                    
                                                </div>
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