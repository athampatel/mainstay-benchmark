@extends('layouts.dashboard')

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection

@section('content')
<div class="backdrop">
    <div class="loader"></div>
</div>
<div class="home-content">
    <div class="padding-y-40 d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex  align-items-center flex-wrap range-filter">
            <div class="page_title p-0">Analysis</div>
            <div class="d-flex analysis-filter">
                <label for="" class="position-relative">
                    <span>By Item</span>
                    <select name="type" id="analysis_item_select" class="rounded analysis_select">
                        <option value="">Select Item</option>
                        <option value="product-line">By Product Line</option>
                        <option value="product">By Product Item</option>
                    </select>
                    <div class="down-arrow"></div>
                </label>                
                <label class="position-relative">
                    <span>By Range</span>
                    <select name="range"id="analysis_range_select"class="rounded analysis_select">
                        <option value="0" selected>Select Range</option>
                        <option value="1">Last Month</option>
                        <option value="2">Quarterly</option>
                        <option value="3">Half Yearly</option>
                        <option value="4">By Range</option>
                    </select>
                    <div class="down-arrow"></div>
                </label>
                <label class="position-relative date-range-field d-none">                   
                    <input type="text" name="daterange" value="" placeholder="select range" class="analysis_select" />
                </label>
            </div>
        </div>
        <div class="toggle-switcher">
            <label class="switch1">
                <input type="checkbox" id="tab_input">
                <span class="slider1"></span>
            </label>
        </div>
    </div>
    <div class="padding-y-40 open-orders analysis_table_container d-none" id="analysis_table_container">
        <div class="row">
            <div class="col-12">
                <div class="card box">
                    <div class="card-header col-12 p-3 d-flex border-0">
                        <div class="col-6 d-flex align-items-center d-none d-lg-block">
                        </div>
                        <div class="col-12 col-lg-6 d-flex align-items-center justify-content-end flex-wrap col-filter">            
                            <div class="position-relative item-search"">
                                <input type="text" class="form-control form-control-sm datatable-search-input" placeholder="Search in All Columns" id="analysis-page-search" aria-controls="">
                                <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="analysis-page-search-img">
                            </div> 
                            <div class="position-relative datatable-filter-div">
                                <select name="" class="datatable-filter-count" id="analysis-page-filter-count">
                                    <option value="5">5 Items</option>
                                    <option value="10">10 Items</option>
                                    <option value="12" selected>12 Items</option>
                                    <option value="20">20 Items</option>
                                </select>
                                <img src="/assets/images/svg/filter-arrow_icon.svg" alt="" class="position-absolute datatable-filter-img">
                            </div>
                            <div class="datatable-export">
                                <div class="datatable-print">
                                    <a href="">
                                        <img src="/assets/images/svg/print-report-icon.svg" alt="" class="position-absolute" id="analysis-print-icon">
                                    </a>
                                </div>
                                <div class="datatable-report">
                                    <a href="">
                                        <img src="/assets/images/svg/export-report-icon.svg" alt="" class="position-absolute" id="analysis-report-icon">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body col-12 padding-y-0">
                        <div class="table-responsive" id="invoice-order-page-table-div">
                        </div>
                        <div class="col-12 pb-2">
                        </div>
                    </div>
                </div>
            </div>	
        </div>
    </div>
    <div class="padding-y-40 open-orders analysis_table_container" id="analysis_table_chart">
        <div class="col-12">
            <div class="card box" style="background:rgb(66, 68, 72)">
                <div class="card-header col-12 p-3 d-flex align-items-center border-0 justify-content-end">
                    <div class="col-6 d-flex align-items-center d-none d-lg-block">
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 d-flex align-items-center justify-content-end">
                      <a class="btn btn-rounded btn-medium btn-bordered mr-2 export-chart-btn">EXPORT REPORT</a>
                      <a class="btn btn-rounded btn-medium btn-primary-dark">MORE DETAILS</a>
                    </div>
                  </div>
                <div id="analysis_page_chart" class="col-12"></div>
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
     <script>
         let response_ = [];
         const constants = <?php echo json_encode($constants); ?>;
    </script>
    <script src="/assets/js/moment.js"></script>
    <script src="/assets/js/analysis-page.js"></script>
@endsection