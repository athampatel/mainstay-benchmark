@extends('layouts.dashboard')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection

@section('title')
{{config('constants.page_title.customers.analysis')}} - Benchmark
@endsection

@section('content')
<div class="backdrop d-none">
    <div class="loader"></div>
</div>
<div class="home-content page-analysis">
    <div class="padding-y-40 d-flex justify-content-between align-items-center flex-wrap filter-wrapper">
        <div class="d-flex align-items-center flex-wrap range-filter">
            <div class="page_title p-0">Analysis</div>
            <div class="d-flex analysis-filter">
                <label for="" class="position-relative" id="analysis_item_select_label">
                    <span>By Item</span>
                    <select name="type" id="analysis_item_select" class="rounded analysis_select">
                        <option value="0" selected>By Sales</option>
                        <option value="1">By Product Line</option>
                    </select>
                    <div class="down-arrow"></div>
                </label>                
                @php 
                $lastMonth = new DateTime('last month');
                @endphp
                <label class="position-relative" id="analysis_range_select_label">
                    {{-- <span>By Range</span> --}}
                    <span>Filter By</span>
                    <select name="range"id="analysis_range_select"class="rounded analysis_select">
                        <option value="" selected>Select Range</option>
                        <option value="0">By Year</option>
                        <option value="1">Last Month</option>
                        <option value="2">Last 3 Months</option>                        
                        <option value="3">Last 6 Months</option>
                        <option value="5">Last 12 Months</option>
                        <option value="4">Specific Dates</option>
                    </select>
                    <div class="down-arrow"></div>
                </label>
                <label class="position-relative date-range-field d-none">                   
                    <input type="text" name="daterange" value="" placeholder="select range" class="analysis_select" />
                </label>
                <label for="" class="position-relative d-none" id="analysis_year_select_label">
                    <span>By Year</span>
                    @php
                    $year = intval(date('Y'));
                    @endphp
                    <select name="type" id="analysis_year_select" class="rounded analysis_select">
                        @for($i = $year ; $i >= 2018; $i-- )
                            @if($urlyear != '')
                                <option value="{{$i}}" {{$i == intval($urlyear) ? 'selected' : ''}}>{{$i}}</option>
                            @else 
                                <option value="{{$i}}" {{$i == $year ? 'selected' : ''}}>{{$i}}</option>
                            @endif
                        @endfor
                    </select>
                    <div class="down-arrow"></div>
                </label>
                {{-- <label for="" class="position-relative d-none" id="product_line_select_label">
                    <span>By Product Line</span>
                    <select name="type" id="product_line_select" class="rounded analysis_select">
                        <option value="0" selected>Please select</option>
                    </select>
                    <div class="down-arrow"></div>
                </label>   --}}
                {{-- <label for="" class="position-relative d-none d-flex gap-2" id="product_line_select_label">
                    <input type="text" value="" class="form-control form-control-sm datatable-search-input" name="" id="item_code_input" placeholder="Enter Customer Item Code">
                    <button id="item_code_search">Search</button>
                </label> --}}
                <input type="hidden" name="" value="0" id="is_search_item_code">
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
        <div id="export_message_display" class="alert alert-success d-none text-center"></div>
        <div class="row">
            <div class="col-12">
                <div class="card box min-height-75 mb-0 border-bottom-radius-0">
                    <div class="card-header col-12 p-3 d-flex border-0">
                        <!--<div class="col-6 d-flex align-items-center d-none d-lg-block">
                        </div> -->
                        <div class="col-12 col-lg-12 d-flex align-items-center justify-content-end flex-wrap col-filter">            
                            <div class="position-relative item-search" id="datatable_product_line_search">
                                <input type="text" class="form-control form-control-sm datatable-search-input" id="analysis-page-search" aria-controls="">
                                <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="analysis-page-search-img">
                            </div> 

                            <x-select-limit-dropdown 
                                name="analysis-page-filter-count"                             
                                selected="inactive"
                                class="datatable-filter-count"
                                id="analysis-page-filter-count"
                            />
                            <div class="datatable-export justify-content-center gap-15 cursor-pointer d-none" id="analysis-page-export">
                                <div class="user-select-none">Export</div>
                                <div class="d-flex justify-content-center align-items-center position-relative">
                                    <a href="" class="d-flex justify-content-center align-items-center">
                                        <img src="/assets/images/svg/export-report-icon.svg" alt="" class="position-absolute" id="analysis-report-icon">
                                    </a>
                                    <div class="dropdown-menu export-drop-down-table customer d-none" aria-labelledby="export-admin-customers" id="export-analysis-page-drop">
                                        {{-- <a class="dropdown-item export-analysis-page-item" data-type="csv">Export to Excel</a> --}}
                                        <a class="dropdown-item export-analysis-page-item" data-type="pdf">Export to PDF</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table_loader d-none">
                        <div class="chart-loader1"></div>
                    </div>
                    <div class="card-body col-12 padding-y-0">
                        <div class="table-responsive" id="invoice-order-page-table-div">
                        </div>
                    </div>
                </div>
                <div class="col-12 pb-2 card box mt-0 box-shadow-none border-top-radius-0">
                    <div id="pagination_disp"></div>
                </div>
            </div>	
        </div>
    </div>
    <div class="padding-y-40 open-orders analysis_table_container" id="analysis_table_chart">
        <div class="col-12">
            <div class="card box min-height-75" style="background:rgb(66, 68, 72)">
                <div class="card-header col-12 p-3 d-flex align-items-center border-0 justify-content-end">
                    <!--<div class="col-6 d-flex align-items-center d-none d-lg-block">
                    </div> -->
                    <div class="col-12 col-md-12 col-lg-12 d-flex align-items-center justify-content-end">
                        <div class="position-relative">
                            <a class="btn btn-rounded btn-medium btn-bordered" id="export-analysis-chart" aria-haspopup="true" aria-expanded="false">EXPORT REPORT</a>
                            <div class="dropdown-menu export-drop-down d-none" aria-labelledby="export-sales-invoice" id="export-analysis-drop-down">
                              <a class="dropdown-item export-analysis-chart-item" data-type="png">PNG</a>
                              <a class="dropdown-item export-analysis-chart-item" data-type="svg">SVG</a>
                              <a class="dropdown-item export-analysis-chart-item" data-type="csv">CSV</a>
                            </div>
                        </div>
                    </div> 
                  </div>
                  <div class="table_loader d-none">
                    <div class="chart-loader1"></div>
                  </div>
                <div id="analysis_page_chart" class="col-12"></div>
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
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script>
         let response_ = [];
         const constants = <?php echo json_encode($constants); ?>;
         const searchWords = <?php echo json_encode($searchWords); ?>;
         const urlyear = <?php echo json_encode($urlyear); ?>;
    </script>
    <script src="/assets/js/analysis-page.js?v={{$version}}"></script>
@endsection