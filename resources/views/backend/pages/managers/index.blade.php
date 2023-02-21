
@extends('backend.layouts.master')

@section('title')
Region Managers - Admin Panel
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
                            <div class="row">
                                <div class="col-12 col-lg-3 col-md-12">
                                    <p class="float-right mb-2">
                                    </p>         
                                </div>
                                <div class="col-12 col-lg-9 col-md-12  d-flex align-items-center justify-content-end flex-wrap col-filter"> 
                                    <div class="position-relative item-search">
                                            <input type="text" class="form-control1 form-control-sm datatable-search-input-admin" placeholder="Search in All Columns" id="admin_managers_search" value="{{!$search ? '' : $search}}" aria-controls="help-page-table">
                                            <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="admin-managers-search-img">
                                        </div> 
                                        @php 
                                        $select_options = [10,12,20];
                                        @endphp
                                        <div class="position-relative datatable-filter-div">
                                            <select name="" class="datatable-filter-count" id="admin-managers-filter-count">
                                                @foreach($select_options as $select_option)
                                                    <option value="{{$select_option}}" {{$select_option == $paginate['per_page'] ? 'selected' :'' }}>{{$select_option}} Items</option>
                                                @endforeach
                                            </select>
                                            <img src="/assets/images/svg/filter-arrow_icon.svg" alt="" class="position-absolute datatable-filter-img">
                                        </div>
                                        <form id="managers_from" action="/admin/admins/manager" method="GET"></form>
                                    <div class="datatable-export">
                                        <div class="datatable-print">
                                            <a href="">
                                                <img src="/assets/images/svg/print-report-icon.svg" alt="" class="position-absolute" id="admin-managers-print-icon">
                                            </a>
                                        </div>
                                        <div class="datatable-report">
                                            <a href="/admin/exportAllCustomers">
                                                <img src="/assets/images/svg/export-report-icon.svg" alt="" class="position-absolute" id="admin-managers-report-icon">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>                                              
                            <div class="clearfix"></div>
                            <div class="data-tables table-responsive">
                                @include('backend.layouts.partials.messages')
                                <table id="backend_managers" class="text-center datatable-dark dataTable">
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
                                                    @if($user->user_id != '')  
                                                        <a class="btn btn-rounded btn-medium btn-primary" href="{{ route('admin.admins.index') }}/{{$user->user_id}}/edit">View</a>
                                                    @endif
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