
@extends('backend.layouts.master')

@section('title')
Regional Managers - Admin Panel
@endsection
@section('admin-content')   
<div class="home-content">
    <span class="page_title">Regional Managers</span>
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
                                                {{config('constants.label.admin.manager_no')}}
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
                                                Account
                                            </th>
                                            <th width="10%">
                                                Action
                                            </th>                                            
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
                                                        <a class="btn btn-rounded text-capitalize btn-light bm-btn-white text-white " href="{{ route('admin.admins.create') }}/?manager={{$user->id}}" title="Create Account">Create</a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-wrapper btns-2">
                                                    @if($user->user_id != '')  
                                                        <a class="btn btn-rounded btn-medium btn-primary" href="{{ route('admin.admins.index') }}/{{$user->user_id}}/edit">View</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @if(!empty($managers))
                                    @if($paginate['last_page'] > 1)
                                        <x-pagination-component :pagination="$paginate" :search="$search" />
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
<table id="print_table" class="text-center datatable-dark dataTable backend_datatables">
    <thead class="text-capitalize">
        <tr>
            <th width="10%" class="text-dark">{{config('constants.label.admin.manager_no')}}</th>
            <th width="10%" class="text-dark">Name</th>
            <th width="10%" class="text-dark">Email</th>                                  
        </tr>
    </thead>
    <tbody>
    @foreach ($print_managers as $print_manager)
        <tr>
            <td class="text-dark">{{ $print_manager->person_number }}</td>
            <td class="text-dark">{{ $print_manager->name }}</td>
            <td class="text-dark">{{ $print_manager->email }}</td>                                                               
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