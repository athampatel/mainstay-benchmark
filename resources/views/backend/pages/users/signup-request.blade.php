
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
                                        <div class="datatable-print admin">
                                            <a href="">
                                                <img src="/assets/images/svg/print-report-icon.svg" alt="" class="position-absolute" id="admin-customer-print-icon">
                                            </a>
                                        </div>
                                        <div class="datatable-report admin position-relative">
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
                                <table id="backend_signup_request" class="text-center datatable-dark backend_datatables">
                                    <thead class="text-capitalize">
                                        <tr>
                                            <th>
                                                Full Name
                                                <span data-col='full_name' data-table='signups' data-ordertype='asc' class="asc">&#x2191;</span>
                                                <span data-col='full_name' data-table='signups' data-ordertype='desc' class="desc">&#x2193;</span>
                                            </th>
                                            <th>
                                                Email
                                                <span data-col='email' data-table='signups' data-ordertype='asc' class="asc">&#x2191;</span>
                                                <span data-col='email' data-table='signups' data-ordertype='desc' class="desc">&#x2193;</span>
                                            </th>
                                            <th>
                                                {{ config('constants.label.admin.contact_no') }}
                                                <span data-col='phone_no' data-table='signups' data-ordertype='asc' class="asc">&#x2191;</span>
                                                <span data-col='phone_no' data-table='signups' data-ordertype='desc' class="desc">&#x2193;</span>
                                            </th>
                                            <th>
                                                Company Name
                                                <span data-col='company_name' data-table='signups' data-ordertype='asc' class="asc">&#x2191;</span>
                                                <span data-col='company_name' data-table='signups' data-ordertype='desc' class="desc">&#x2193;</span>
                                            </th>
                                            <th>
                                                Request Date
                                                <span data-col='created_at' data-table='signups' data-ordertype='asc' class="asc">&#x2191;</span>
                                                <span data-col='created_at' data-table='signups' data-ordertype='desc' class="desc">&#x2193;</span>
                                            </th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{$user->full_name}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>{{$user->phone_no}}</td>
                                            <td>{{$user->company_name}}</td>
                                            {{-- <td>{!!$user->created_at->format('Y-m-d H:i:s')!!}</td> --}}
                                            <td>{!!$user->created_at->format('M d, Y')!!}</td>
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
                            @if(!empty($users))
                                @if($paginate['last_page'] > 1)
                                    <div class="mt-3">
                                        <x-pagination-component :pagination="$paginate" :search="$search" />
                                    </div>
                                @endif    
                            @endif                          
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>

<table id="print_table" class="text-center datatable-dark backend_datatables">
    <thead class="text-capitalize">
        <tr>
            <th width="5%">S.No</th>
            <th width="20%">Full Name</th>
            <th width="20%">Email</th>
            <th width="20%">{{ config('constants.label.admin.contact_no') }}</th>
            <th width="20%">Company Name</th>
            <th width="10%">Request Date</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($print_users as $print_user)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$print_user->full_name}}</td>
            <td>{{$print_user->email}}</td>
            <td>{{$print_user->phone_no}}</td>
            <td>{{$print_user->company_name}}</td>
            <td>
                {{$print_user->created_at->format('M d, Y')}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
@section('scripts')
<script>
const searchWords = <?php echo json_encode($searchWords); ?>;
const orderCol = <?php echo json_encode($order); ?>;
const orderType = <?php echo json_encode($order_type); ?>;
$('th span').css({'opacity':0.3})
$(`[data-col='${orderCol}'][data-ordertype='${orderType}']`).css({'opacity':1});
</script>
@endsection