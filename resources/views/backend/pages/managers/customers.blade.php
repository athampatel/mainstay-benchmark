
@extends('backend.layouts.master')

@section('title')
Regional Manager Customers - Admin Panel
@endsection
@section('admin-content')   
<div class="home-content">
    <span class="page_title">Regional Manager Customers</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
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
                                        <div class="datatable-print admin">
                                            <a href="">
                                                <img src="/assets/images/svg/print-report-icon.svg" alt="" class="position-absolute" id="admin-managers-print-icon">
                                            </a>
                                        </div>
                                        <div class="datatable-report admin position-relative">
                                            <a href="/admin/exportAllCustomers">
                                                <img src="/assets/images/svg/export-report-icon.svg" alt="" class="position-absolute" id="admin-managers-report-icon">
                                            </a>
                                            <div class="dropdown-menu export-drop-down-table d-none" aria-labelledby="export-admin-customers" id="export-admin-managers-drop">
                                                <a href="/admin/exportAllManagersInExcel" class="dropdown-item export-admin-managers-item" data-type="csv">Export to Excel</a>
                                                <a href='/admin/exportAllManagersInPdf' class="dropdown-item export-admin-managers-item" data-type="pdf">Export to PDF</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                                              
                            <div class="clearfix"></div>
                            <div class="data-tables table-responsive">
                                @include('backend.layouts.partials.messages')
                                <table id="backend_managers" class="text-center datatable-dark dataTable backend_datatables">
                                    <thead class="text-capitalize">
                                        <tr>
                                            <th width="10%">
                                                {{-- {{config('constants.label.admin.manager_no')}} --}}
                                                Customer Number
                                                <span data-col='person_number' data-table='managers' data-ordertype='asc' class="asc">&#x2191;</span>
                                                <span data-col='person_number' data-table='managers' data-ordertype='desc' class="desc">&#x2193;</span>
                                            </th>
                                            <th width="10%">
                                                Name
                                                <span data-col='name' data-table='managers' data-ordertype='asc' class="asc">&#x2191;</span>
                                                <span data-col='name' data-table='managers' data-ordertype='desc' class="desc">&#x2193;</span>
                                            </th>
                                            <th width="10%">
                                                Email
                                                <span data-col='email' data-table='managers' data-ordertype='asc' class="asc">&#x2191;</span>
                                                <span data-col='email' data-table='managers' data-ordertype='desc' class="desc">&#x2193;</span>
                                            </th>                                   
                                            <th width="10%">
                                                Action
                                            </th>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($manager_customers as $customer)
                                    {{-- {{dd($customer)}} --}}
                                    <tr>
                                            <td> <a class="" href="">{{ $customer['customerno'] }}</a></td>
                                            <td>{{ $customer['customername'] }}</td>
                                            <td>{{ $customer['emailaddress'] }}</td>                                                                
                                            <td>
                                                <div class="status-btns">
                                                    {{-- @if($customer->user_id != '')                                                         
                                                        <a class="btn btn-rounded btn-medium btn-bordered" href="{{  route('admin.users.index') }}?manager={{$user->user_id}}" title="View Customers">Customers</a>
                                                    @else --}}
                                                        {{-- <a class="btn btn-rounded text-capitalize btn-light bm-btn-white text-white " href="{{ route('admin.admins.create') }}/?manager={{$user->id}}" title="Create Account">Create</a> --}}
                                                        @if($customer['is_exits'])
                                                        <a class="btn btn-rounded btn-medium btn-primary text-capitalize d-block" target="_blank" href="{{ route('admin.users.login', ['id' => $customer['user_detail']['user_id'],'user_detail_id' =>$customer['user_detail']['id']]) }}" style="padding:0.5rem !important;">Login As</a>
                                                        @else 
                                                        <a class="btn btn-rounded text-capitalize btn-danger bm-btn-danger text-white" href="javascript:void(0)" style="padding:0.5rem !important;">Not In</a>
                                                        @endif
                                                    {{-- @endif
                                                    <a class="btn btn-rounded text-capitalize btn-primary bm-btn-primary text-white" href="{{ route('admin.manager.customers',['id' => $customer->customerno]) }}">All Customers</a> --}}
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
            </div>
        </div>
    </div>
</div> 
@endsection
@section('scripts')
<script>
    const searchWords = <?php echo json_encode($searchWords); ?>;
</script>
@endsection