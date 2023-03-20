
@extends('layouts.dashboard')

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection

@section('content')
<div class="home-content">
    <span class="page_title">VMI</span>
    <div class="padding-y-40 open-orders">
        <div class="row">
            <div class="col-12">
                <div class="card box min-height-75">
                    <div class="card-header col-12 p-3 d-flex border-0">
                        
                        <div class="col-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-end flex-wrap col-filter">            
                            <div class="position-relative item-search">
                                <input type="text" class="form-control form-control-sm datatable-search-input" placeholder="Search in All Columns" id="vmi-page-search" aria-controls="">
                                <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="vmi-page-search-img">
                            </div> 
                            <div class="position-relative datatable-filter-div">
                                <select name="" class="datatable-filter-count" id="vmi-page-filter-count">
                                    <option value="12" selected>12 Items</option>
                                    <option value="15">15 Items</option>
                                    <option value="20">20 Items</option>
                                    <option value="50">50 Items</option>
                                    <option value="100">100 Items</option>
                                </select>
                                <img src="/assets/images/svg/filter-arrow_icon.svg" alt="" class="position-absolute datatable-filter-img">
                            </div>
                            <div class="vmi-datatable-export">
                                <div class="vmi-datatable-print">
                                    <span>Print</span>
                                    <a href="">
                                        <img src="/assets/images/svg/print-report-icon.svg" alt="" class="position-absolute" id="vmi-print-icon">
                                    </a>
                                </div>
                                <div class="vmi-datatable-report">
                                    <span>Download</span>
                                    <a href="">
                                        <img src="/assets/images/svg/cloud_download.svg" alt="" class="position-absolute" id="vmi-report-icon">
                                    </a>
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
                        <div class="col-12 pb-2">
                            <div id="pagination_disp"></div>
                        </div>
                    </div>
                </div>
            </div>	
        </div>
    </div>
</div>
@endsection

@section('scripts')
     <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
     <script src="/assets/js/vmi-page.js"></script>
    <script>
        const constants = <?php echo json_encode($constants); ?>;
    </script>
@endsection