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
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-3 col-md-12">
                                    <p class="float-right mb-2">
                                        {{-- <a class="btn btn-primary btn-rounded text-capitalize bm-btn-primary text-white" href="{{ route('admin.users.create') }}">Create Customer</a> --}}
                                        <a class="btn btn-primary btn-rounded text-capitalize bm-btn-primary text-white" href="{{ route('admin.users.create') }}">{{ config('constants.label.admin.buttons.create_request') }}</a>
                                    </p>            
                                </div>
                                <div class="col-12 col-lg-9 col-md-12  d-flex align-items-center justify-content-end flex-wrap col-filter"> 
                                    <div class="position-relative item-search">
                                        <input type="text" class="form-control1 form-control-sm datatable-search-input-admin" placeholder="Search in All Columns" id="admin_change_order_search" value="{{!$search ? '' : $search}}" aria-controls="help-page-table">
                                        <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="admin-change-order-search-img">
                                    </div> 
                                    @php 
                                    $select_options = [10,12,20];
                                    @endphp
                                    <div class="position-relative datatable-filter-div">
                                        <select name="" class="datatable-filter-count" id="admin-change-order-filter-count">
                                            @foreach($select_options as $select_option)
                                                <option value="{{$select_option}}" {{$select_option == $paginate['per_page'] ? 'selected' :'' }}>{{$select_option}} Items</option>
                                            @endforeach
                                        </select>
                                        <img src="/assets/images/svg/filter-arrow_icon.svg" alt="" class="position-absolute datatable-filter-img">
                                    </div>
                                    <form id="change_order_from" action="/admin/customers/change-orders" method="GET"></form>
                                    <div class="datatable-export">
                                        <div class="datatable-print">
                                            <a href="">
                                                <img src="/assets/images/svg/print-report-icon.svg" alt="" class="position-absolute" id="admin-customer-print-icon">
                                            </a>
                                        </div>
                                        <div class="datatable-report position-relative">
                                            <a href="">
                                                <img src="/assets/images/svg/export-report-icon.svg" alt="" class="position-absolute" id="admin-customer-orders-icon">
                                            </a>
                                            <div class="dropdown-menu export-drop-down-table d-none" aria-labelledby="export-admin-customers" id="export-admin-orders-drop">
                                                <a href="/admin/exportAllChangeOrdersInExcel" class="dropdown-item export-admin-orders-item" data-type="csv">Export to Excel</a>
                                                <a href='/admin/exportAllChangeOrdersInPdf' class="dropdown-item export-admin-orders-item" data-type="pdf">Export to PDF</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>     
                            <div class="clearfix"></div>
                            <div class="data-tables table-responsive">
                                @include('backend.layouts.partials.messages')
                                <table id="backend_change_order_requests" class="text-center datatable-dark">
                                    <thead class="text-capitalize">
                                        <tr>
                                            <th width="10%">{{ config('constants.label.admin.customer_no') }}</th>
                                            <th width="10%">{{ config('constants.label.admin.customer_name') }}</th>
                                            <th width="10%">{{ config('constants.label.admin.customer_email') }}</th>
                                            <th width="10%">{{ config('constants.label.admin.order_date') }}</th>
                                            <th width="10%">{{ config('constants.label.admin.region_manager') }}</th>
                                            <th width="10%">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($change_request as $_request)
                                        <tr>
                                            <td> <a class="" href="">{{ $_request->customerno }}</a></td>
                                            <td>{{ $_request->name }}</td>
                                            <td>{{ $_request->email }}</td>                                                                
                                            <td>{{ date('m-d-Y',strtotime($_request->ordered_date))}}</td>
                                            <td>{{ $_request->manager}}</td>
                                            <td>
                                                @if($_request['request_status'] == 2)
                                                    <span class="badge bm-btn-delete btn-rounded text-capitalize text-white ">Declined</span>
                                                @elseif($_request['request_status'] == 1)
                                                    <span class="badge bm-btn-primary btn-success btn-rounded text-capitalize text-white">Approved</span>  
                                                @else                                             
                                                    <span class="badge btn btn-primary bm-btn-primary btn-rounded badge-warning text-capitalize text-white">New</span>
                                                @endif
                                                <a class="btn btn-info btn-rounded text-white" href="{{ route('admin.users.change-order-view', $_request->id) }}">View Info</a>
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
            </div>
        </div>
    </div>
</div>
@endsection