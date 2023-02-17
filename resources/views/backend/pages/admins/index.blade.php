
@extends('backend.layouts.master')

@section('title')
Admins - Admin Panel
@endsection

@section('admin-content')

<div class="home-content">
    <span class="page_title">Benchmark Users</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <!-- data table start -->
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">  
                            <div class="row">
                                <div class="col-12 col-lg-3 col-md-12">
                                    <p class="float-right mb-2">
                                        @if (Auth::guard('admin')->user()->can('admin.edit'))
                                            <a class="btn btn-primary btn-rounded text-white" href="{{ route('admin.admins.create') }}">Create New User</a>
                                        @endif
                                    </p>        
                                </div>
                                <div class="col-12 col-lg-9 col-md-12  d-flex align-items-center justify-content-end flex-wrap col-filter"> 
                                    <div class="position-relative item-search">
                                            {{-- <form id="customer_filter" action="/admin/customers" method="GET"> --}}
                                                <input type="text" class="form-control1 form-control-sm datatable-search-input-admin" placeholder="Search in All Columns" id="admin_admins_search" value="{{!$search ? '' : $search}}" aria-controls="help-page-table">
                                                <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="admin-admins-search-img">
                                            {{-- </form> --}}
                                        </div> 
                                        {{-- {{dd($paginate['per_page'])}} --}}
                                        @php 
                                        $select_options = [10,12,20];
                                        @endphp
                                        <div class="position-relative datatable-filter-div">
                                            {{-- <form action="" method="get"> --}}
                                                <select name="" class="datatable-filter-count" id="admin-admins-filter-count">
                                                    @foreach($select_options as $select_option)
                                                        <option value="{{$select_option}}" {{$select_option == $paginate['per_page'] ? 'selected' :'' }}>{{$select_option}} Items</option>
                                                    @endforeach
                                                </select>
                                                <img src="/assets/images/svg/filter-arrow_icon.svg" alt="" class="position-absolute datatable-filter-img">
                                            {{-- </form> --}}
                                        </div>
                                        <form id="admins_from" action="/admin/admins" method="GET">
                                            {{-- @csrf --}}
                                        </form>
                                    {{-- </form> --}}
                                    <div class="datatable-export">
                                        <div class="datatable-print">
                                            <a href="">
                                                <img src="/assets/images/svg/print-report-icon.svg" alt="" class="position-absolute" id="admin-admins-print-icon">
                                            </a>
                                        </div>
                                        <div class="datatable-report">
                                            <a href="/admin/exportAllCustomers">
                                                <img src="/assets/images/svg/export-report-icon.svg" alt="" class="position-absolute" id="admin-admins-report-icon">
                                            </a>
                                        </div>
                                    </div>
                                   {{-- form submit work start --}}
                                    {{-- <form id="customer-page-form" action="http://localhost:8081/admin/customers" method="GET" style="display:none;">
                                        <input type="hidden" name="_token" value="UVbairuWI4ETpda1GR7s6dIfkx0ynp1C9XRH2vSi">                
                                    </form> --}}
                                   {{-- form submit work end --}}
                                </div>
                            </div>
                            {{-- <p class="float-right mb-2">
                                @if (Auth::guard('admin')->user()->can('admin.edit'))
                                    <a class="btn btn-primary btn-rounded text-white" href="{{ route('admin.admins.create') }}">Create New User</a>
                                @endif
                            </p> --}}
                            <div class="clearfix"></div>
                            <div class="data-tables table-responsive">
                                @include('backend.layouts.partials.messages')
                                <table id="backend_admins" class="text-center datatable-dark dataTable">
                                    <thead class="text-capitalize">
                                        <tr>
                                            
                                            <th width="5%">Sl</th>
                                            <th width="10%">User Name</th>
                                            <th width="10%">Name</th>
                                            <th width="10%">Email</th>
                                            <th width="40%">Roles</th>
                                            <th width="15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($admins as $admin)
                                    <tr>
                                            <td>{{ $loop->index+1 }}</td>
                                            <td>{{ $admin->username }}</td>
                                            <td>{{ $admin->name }}</td>
                                            <td>{{ $admin->email }}</td>
                                            <td>
                                                @foreach ($admin->roles as $role)
                                                    <span class="badge badge-info mr-1">
                                                        {{ $role->name }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <div class="btn-wrapper btns-2">
                                                    @if (Auth::guard('admin')->user()->can('admin.edit'))
                                                        <a class="btn btn-rounded btn-medium btn-primary" href="{{ route('admin.admins.edit', $admin->id) }}">Edit</a>
                                                    @endif
                                                    
                                                    @if (Auth::guard('admin')->user()->can('admin.delete'))
                                                    <a class="btn btn-rounded btn-medium btn-bordered" href="{{ route('admin.admins.destroy', $admin->id) }}"
                                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $admin->id }}').submit();">
                                                        Delete
                                                    </a>
                                                    <form id="delete-form-{{ $admin->id }}" action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" style="display: none;">
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <x-pagination-component :pagination="$paginate" :search="$search" />
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

