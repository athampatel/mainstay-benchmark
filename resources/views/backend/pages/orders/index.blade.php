@extends('backend.layouts.master')

@section('title')
Customer Change Order Requests - Admin Panel
@endsection

@section('admin-content')

<div class="home-content">
    <span class="page_title">Change Order Request</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <!-- data table start -->
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            {{-- test work start --}}
                            <div class="row">
                                <div class="col-12 col-lg-3 col-md-12">
                                    <p class="float-right mb-2">
                                        <a class="btn btn-primary btn-rounded text-white" href="{{ route('admin.users.create') }}">Create Customer</a>
                                    </p>            
                                </div>
                                <div class="col-12 col-lg-9 col-md-12  d-flex align-items-center justify-content-end flex-wrap col-filter"> 
                                    <div class="position-relative item-search">
                                            {{-- <form id="customer_filter" action="/admin/customers" method="GET"> --}}
                                                <input type="text" class="form-control1 form-control-sm datatable-search-input-admin" placeholder="Search in All Columns" id="admin_change_order_search" value="{{!$search ? '' : $search}}" aria-controls="help-page-table">
                                                <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="admin-change-order-search-img">
                                            {{-- </form> --}}
                                        </div> 
                                        {{-- {{dd($paginate['per_page'])}} --}}
                                        @php 
                                        $select_options = [10,12,20];
                                        @endphp
                                        <div class="position-relative datatable-filter-div">
                                            {{-- <form action="" method="get"> --}}
                                                <select name="" class="datatable-filter-count" id="admin-change-order-filter-count">
                                                    @foreach($select_options as $select_option)
                                                        <option value="{{$select_option}}" {{$select_option == $paginate['per_page'] ? 'selected' :'' }}>{{$select_option}} Items</option>
                                                    @endforeach
                                                </select>
                                                <img src="/assets/images/svg/filter-arrow_icon.svg" alt="" class="position-absolute datatable-filter-img">
                                            {{-- </form> --}}
                                        </div>
                                        <form id="change_order_from" action="/admin/customers/change-orders" method="GET">
                                            {{-- @csrf --}}
                                        </form>
                                    {{-- </form> --}}
                                    <div class="datatable-export">
                                        <div class="datatable-print">
                                            <a href="">
                                                <img src="/assets/images/svg/print-report-icon.svg" alt="" class="position-absolute" id="admin-customer-print-icon">
                                            </a>
                                        </div>
                                        <div class="datatable-report">
                                            <a href="/admin/exportAllCustomers">
                                                <img src="/assets/images/svg/export-report-icon.svg" alt="" class="position-absolute" id="admin-customer-report-icon">
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
                            {{-- test work end --}}                             
                            <div class="clearfix"></div>
                            <div class="data-tables table-responsive">
                                @include('backend.layouts.partials.messages')
                                <table id="backend_change_order_requests" class="text-center datatable-dark">
                                    <thead class="text-capitalize">
                                        <tr>
                                            <th width="10%">Customer No</th>
                                            <th width="10%">Customer Name</th>
                                            <th width="10%">Customer Email</th>                                   
                                            <th width="10%">Order Date</th>
                                            <th width="10%">Region Manager</th>
                                            <th width="10%">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($change_request as $_request)
                                    <tr>
                                            <td> <a class="" href="">{{ $_request->customerno }}</a></td>
                                            <td>{{ $_request->name }}</td>
                                            <td>{{ $_request->email }}</td>                                                                
                                            <td>{{ $_request->ordered_date}}</td>
                                            <td>{{ $_request->manager}}</td>
                                            <td>

                                                    @if($_request['request_status'] == 2)
                                                        <span class="badge badge-danger text-white ">Declined</span>
                                                    @elseif($_request['request_status'] == 1)
                                                        <span class="badge badge-success text-white">Approved</span>  
                                                    @else                                             
                                                        <span class="badge badge-warning text-white">New</span>
                                                    @endif
                                                <a class="btn btn-info text-white" href="{{ route('admin.users.change-order-view', $_request->id) }}">View       Info</a>
                                                <!--<a class="btn btn-danger text-white" href="{{ route('admin.users.destroy', $user->id) }}"
                                                onclick="event.preventDefault(); document.getElementById('delete-form-{{ $user->id }}').submit();">Delete
                                                </a> -->
                                            </td>                                    
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($paginate['last_page'] > 1)
                                <div class="mt-3">
                                    <x-pagination-component :pagination="$paginate" :search="$search" />
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- data table end -->
                
            </div>
        </div>
    </div>
</div>
@endsection