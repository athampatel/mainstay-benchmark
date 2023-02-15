
@extends('backend.layouts.master')

@section('title')
Customers - Admin Panel
@endsection


@section('admin-content')  

<div class="home-content">
    <span class="page_title">Customers</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <!-- data table start -->
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">                            
                            <p class="float-right mb-2">
                                <a class="btn btn-primary text-white" href="{{ route('admin.users.create') }}">Create Customer</a>
                            </p>
                            <div class="clearfix"></div>
                            <div class="data-tables table-responsive">
                                @include('backend.layouts.partials.messages')
                                <table id="dataTable" class="text-center datatable-dark">
                                    <thead class="text-capitalize">
                                        <tr>
                                            <th width="10%">Customer No</th>
                                            <th width="10%">Name</th>
                                            <th width="10%">Email</th>
                                            <th width="10%">AR Division no</th>
                                            <th width="10%">Benchmark Regional Manager</th>
                                            <th width="10%">Status</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($users as $user)
                                    <tr>
                                            <td> <a class="" href="{{ route('admin.users.edit', $user->id) }}">{{ $user->customerno }}</a></td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->ardivisionno }}</td>
                                            <td>
                                                @if($user->sales_person != '')
                                                    {{$user->sales_person}} ({{$user->person_number}})
                                                @else
                                                    -
                                                @endif
                                            </td>                                    
                                            <td>
                                                <div class="status-btns">
                                                    @if( $user->active == 1)
                                                        <span class="btn btn-success btn-rounded text-white" style="padding:5px;pointer-events:none;">Active</span>           
                                                    @elseif( $user->active == 0 && $user->is_deleted == 0)
                                                        <a href="{{env('APP_URL')}}/admin/user/{{$user->id}}/change-status/{{$user->activation_token}}" target="_blank" class="btn btn-rounded btn-light text-dark" style="padding:5px;">New</a>
                                                    @endif

                                                    @if($user->is_vmi == 1)
                                                        <a data-customer="{{$user->id}}" class="btn btn-rounded btn-info text-white"  href="{{ route('admin.users.inventory',$user->id) }}" title="Add / Update Inventory">Inventory</a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>

                                                <div class="btn-wrapper btns-2">
                                                    <a class="btn btn-rounded btn-medium btn-primary" href="{{ route('admin.users.edit', $user->id) }}">Edit</a>
                                                
                                                    <a class="btn btn-rounded btn-medium btn-bordered" href="{{ route('admin.users.destroy', $user->id) }}"
                                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $user->id }}').submit();">
                                                        Delete
                                                    </a>

                                                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: none;">
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>
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
