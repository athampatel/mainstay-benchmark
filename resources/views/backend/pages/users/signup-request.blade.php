
@extends('backend.layouts.master')

@section('title')
Customers - Admin Panel
@endsection


@section('admin-content')  

<div class="home-content">
    <span class="page_title">Portal Access Request</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-3 col-md-12"></div>
                                <div class="col-12 col-lg-9 col-md-12  d-flex align-items-center justify-content-end flex-wrap col-filter"> 
                                    <div class="position-relative item-search">
                                        <input type="text" class="form-control1 form-control-sm datatable-search-input-admin" placeholder="Search in All Columns" id="admin_signup_search" value="{{!$search ? '' : $search}}" aria-controls="help-page-table">
                                        <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="admin-signup-search-img">
                                    </div>     
                                    @php 
                                    $select_options = [10,12,20];
                                    @endphp
                                    <div class="position-relative datatable-filter-div">
                                        <select name="" class="datatable-filter-count" id="admin-signup-filter-count">
                                            @foreach($select_options as $select_option)
                                                <option value="{{$select_option}}">{{$select_option}} Items</option>
                                            @endforeach
                                        </select>
                                        <img src="/assets/images/svg/filter-arrow_icon.svg" alt="" class="position-absolute datatable-filter-img">
                                    </div>
                                    <form id="admin_signup_from" action="/admin/signup-request" method="GET"></form>
                                    <div class="datatable-export">
                                        <div class="datatable-print">
                                            <a href="">
                                                <img src="/assets/images/svg/print-report-icon.svg" alt="" class="position-absolute" id="admin-customer-print-icon">
                                            </a>
                                        </div>
                                        <div class="datatable-report position-relative">
                                            <a href="#">
                                                <img src="/assets/images/svg/export-report-icon.svg" alt="" class="position-absolute" id="admin-signup-report-icon">
                                            </a>
                                            <div class="dropdown-menu export-drop-down-table d-none" aria-labelledby="export-admin-customers" id="export-signup-admins-drop">
                                                <a href="/admin/exportAllSignupsInExcel" class="dropdown-item export-signup-admins-item" data-type="csv">Export to Excel</a>
                                                <a href='/admin/exportAllSignupInPdf' class="dropdown-item export-signup-admins-item" data-type="pdf">Export to PDF</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="data-tables table-responsive">
                                <table id="backend_signup_request" class="text-center datatable-dark">
                                    <thead class="text-capitalize">
                                        <tr>
                                            <th width="20%">Full Name</th>
                                            <th width="20%">Email</th>
                                            <th width="20%">{{ config('constants.label.admin.contact_no') }}</th>
                                            <th width="20%">Company Name</th>
                                            <th width="10%">Request Date</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{$user->full_name}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>{{$user->phone_no}}</td>
                                            <td>{{$user->company_name}}</td>
                                            <td>{!!$user->created_at->format('Y-m-d H:i:s')!!}</td>
                                            <td>
                                                @if($user['user_id'] != null)
                                                <a href="{{env('APP_URL')}}admin/user/{{$user->user_id}}/change-status/{{$user->activation_token}}" target="_blank" class="btn btn-rounded text-capitalize btn-primary bm-btn-primary">View Details</a>
                                                @else
                                                <a href="{{env('APP_URL')}}admin/user/{{$user->email}}/change-status" target="_blank" class="btn btn-rounded btn-primary text-capitalize bm-btn-primary">View Details</a>
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
            </div>
        </div>
    </div>    
</div>
@endsection
