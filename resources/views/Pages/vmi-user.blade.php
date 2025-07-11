
@extends('layouts.dashboard')

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection

@section('title')
{{config('constants.page_title.customers.vmi')}} - Benchmark
@endsection


@section('content')
<div class="home-content">
    <span class="page_title">VMI</span>
    <div class="padding-y-40 open-orders">
        <div class="row">
            <div class="col-12">
                <div class="card box min-height-75 border-bottom-radius-0 mb-0">
                    <div class="card-header col-12 p-3 d-flex border-0">
                        <div class="col-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-end flex-wrap col-filter">            
                            <div class="position-relative item-search">
                                <input type="text" class="form-control form-control-sm datatable-search-input" placeholder="Search Benchmark Item Number" id="vmi-page-search" aria-controls="">
                                <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="vmi-page-search-img">
                            </div> 
                             <x-select-limit-dropdown 
                                name="vmi-page-filter-count"                             
                                selected="inactive"
                                class="datatable-filter-count"
                                id="vmi-page-filter-count"
                            />
                            <div class="vmi-datatable-export cursor-pointer">
                                <div class="vmi-datatable-report position-relative">
                                    <span class="user-select-none cursor-pointer">Download</span>
                                    <a href="">
                                        <img src="/assets/images/svg/cloud_download.svg" alt="" class="position-absolute" id="vmi-report-icon">
                                    </a>
                                    <div class="dropdown-menu export-drop-down-table vmi d-none" aria-labelledby="export-admin-customers" id="export-vmi-page-drop">
                                        <a class="dropdown-item export-vmi-page-item" data-type="csv" id="vmi-page-export-csv">Export to Excel</a>
                                        <a class="dropdown-item export-vmi-page-item" data-type="pdf" id="vmi-page-export-pdf">Export to PDF</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table_loader d-none">
                        <div class="chart-loader1"></div>
                    </div>
                    <div class="card-body col-12 padding-y-0">
                        <div class="page-table-loader-div d-none">
                            <div class="chart-loader1"></div>
                        </div>
                        <div class="table-responsive" id="vmi_table_disp"></div>
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
     <script src="/assets/js/vmi-page.js?v={{$version}}"></script>
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const constants = <?php echo json_encode($constants); ?>;
        const searchWords = <?php echo json_encode($searchWords); ?>;
        const env_maximum = '{{config('app.export_max')}}';
    </script>
@endsection