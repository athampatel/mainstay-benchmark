
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
                                            <input type="text" class="form-control1 form-control-sm datatable-search-input-admin" placeholder="Search in All Columns" id="admin_managers_customers_search" value="{{!$search ? '' : $search}}" aria-controls="help-page-table">
                                            <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="admin-managers-search-img">
                                        </div> 
                                        @php 
                                        $select_options = [10,12,20];
                                        @endphp
                                        <div class="position-relative datatable-filter-div">
                                            <select name="" class="datatable-filter-count" id="admin-managers-customers-filter-count">
                                                @foreach($select_options as $select_option)
                                                    <option value="{{$select_option}}" {{$select_option == 12 ? 'selected' :'' }}>{{$select_option}} Items</option>
                                                @endforeach
                                            </select>
                                            <img src="/assets/images/svg/filter-arrow_icon.svg" alt="" class="position-absolute datatable-filter-img">
                                        </div>
                                        {{-- {{dd(app('request')->request->all())}}--}}
                                        {{-- is_param_id/is_param_exists --}}
                                        {{-- <form id="managers_customers_from" action="/admin/admins/manager" method="GET"></form> --}}
                                        <form id="managers_customers_from" action="/admin/manager/customers" method="GET"></form>
                                        <input type="hidden" name="" id="sales_person_number" value="{{$sales_person_number}}">
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
                                <div class="backend_manager_customers_display"></div>
                                <div class="col-12 pb-2 card box mb-0 mt-0 border-top-radius-0 box-shadow-none border-none">
                                    <div id="pagination_disp" style="width: 100%;"></div>
                                </div>
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
<script src="{{ asset('assets/js/backend_manager_customers.js') }}"></script>
@endsection