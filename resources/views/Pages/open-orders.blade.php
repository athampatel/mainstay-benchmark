@extends('layouts.dashboard')

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection

@section('title')
{{config('constants.page_title.customers.open_order')}} - Benchmark
@endsection


@section('content')
<div class="backdrop d-none">
    <div class="loader"></div>
</div>
<div class="home-content">
    <span class="page_title">Open orders</span>
    <div class="padding-y-40 open-orders">
        <div class="row">
            <div class="col-12">
                <div class="card box min-height-75 mb-0 border-bottom-radius-0">
                    <div class="card-header col-12 p-3 d-flex border-0">
                        <div class="col-12 col-lg-12 col-md-12  d-flex align-items-center justify-content-end flex-wrap col-filter">            
                            <div class="position-relative item-search">
                                <input type="text" class="form-control form-control-sm datatable-search-input" placeholder="Search By Order Number" id="open-orders-page-search" aria-controls="help-page-table">
                                <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="open-orders-page-search-img">
                            </div> 
                            <x-select-limit-dropdown 
                                name="open-orders-page-filter-count"                             
                                selected="inactive"
                                class="datatable-filter-count"
                                id="open-orders-page-filter-count"
                            />
                            <div class="datatable-export justify-content-center gap-15 cursor-pointer" id="open-order-page-export">
                                <div class="user-select-none">Export</div>
                                <div class="d-flex justify-content-center align-items-center position-relative">
                                    <a href="" class="d-flex justify-content-center align-items-center">
                                        <img src="/assets/images/svg/export-report-icon.svg" alt="" class="position-absolute" id="open-orders-report-icon">
                                    </a>
                                    <div class="dropdown-menu export-drop-down-table customer d-none" aria-labelledby="export-admin-customers" id="export-open-orders-page-drop">
                                        <a href="" class="dropdown-item export-open-orders-page-item" data-type="csv" id="export-open-csv">Export to Excel</a>
                                        <a href="" class="dropdown-item export-open-orders-page-item" data-type="pdf" id="export-open-pdf">Export to PDF</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table_loader d-none">
                        <div class="chart-loader1"></div>
                    </div>
                    <div class="card-body col-12 padding-y-0 position-relative">
                        <div class="table-responsive overflow-hidden" id="open-orders-page-table-div">
                        </div>
                    </div>
                </div>
                <div class="col-12 pb-2 card box mt-0 box-shadow-none border-top-radius-0">
                    <div id="pagination_disp"></div>
                </div>
            </div>	
        </div>
    </div>
</div>
@endsection
@php 
    $version = time(); 
@endphp
@section('scripts')
     <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
     <script src="/assets/js/open-orders-page.js?v={{$version}}"></script>
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const constants = <?php echo json_encode($constants); ?>;
        const searchWords = <?php echo json_encode($searchWords); ?>;
        const env_maximum = '{{ config('app.export_max') }}';
    </script>
@endsection