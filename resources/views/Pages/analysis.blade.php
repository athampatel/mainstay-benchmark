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
    <div class="row row-cols-1 row-cols-md-1 row-cols-lg-1 row-cols-xl-1 col-12">
        <div class="col">
            <div class="padding-y-40 d-flex justify-content-between align-items-center">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="page_title p-0">Analysis</div>
                    <div class="d-flex analysis-filter">
                        <label for="" class="position-relative">
                            <span>By Item</span>
                            <select name="" id="analysis_item_select" class="rounded analysis_select">
                                <option value="" selected>Select Item</option>
                            </select>
                            <div class="down-arrow"></div>
                        </label>
                        <label class="position-relative">
                            <span>By Range</span>
                            <select name=""id="analysis_range_select"class="rounded analysis_select">
                                <option value="0" selected>Select Range</option>
                                <option value="1">Last Month</option>
                                <option value="2">Quarterly</option>
                                <option value="3">Half Yearly</option>
                                <option value="4">By Range</option>
                            </select>
                            <div class="down-arrow"></div>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="switch1">
                        <input type="checkbox" id="tab_input">
                        <span class="slider1"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="padding-y-40 open-orders analysis_table_container d-none" id="analysis_table_container">
        <div class="row">
            <div class="col-12">
                <div class="card box">
                    <div class="card-header col-12 p-3 d-flex border-0">
                        <div class="col-sm-12 col-md-12 col-lg-6 d-flex align-items-center">
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6 d-flex align-items-center justify-content-end analysis_table_options">            
                            <div class="position-relative">
                                <input type="text" class="form-control form-control-sm datatable-search-input" placeholder="Search in All Columns" id="analysis-page-search" aria-controls="">
                                <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="analysis-page-search-img">
                            </div> 
                            <div class="position-relative datatable-filter-div">
                                <select name="" class="datatable-filter-count" id="analysis-page-filter-count">
                                    <option value="12" selected>12 Items</option>
                                    <option value="5">5 Items</option>
                                    <option value="10">10 Items</option>
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
                            {{-- <table id="analysis-page-table" class="table bench-datatable border-0">
                                <thead>
                                    <tr>
                                        <th class="border-0">Invoice Number</th>
                                        <th class="border-0 text-center">Invoice Date</th>
                                        <th class="border-0">Customer PO Number</th>
                                        <th class="border-0">Ship-to City,State</th>
                                        <th class="border-0">Total Number of Items</th>
                                        <th class="border-0">Total Invoiced Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                @for($i = 0; $i < 50; $i++)
                                    <tr>
                                        <td><a href="javascript:void(0)" class="item-number font-12 btn btn-rounded">#89742-{{$i}}</a></td>
                                        <td class="text-center">Apr 08,2021</td>
                                        <td>123456</td>
                                        <td class="location">
                                            <span class="svg-icon location-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="8.542" height="11.46" viewBox="0 0 8.542 11.46"><path class="location-svg" d="M260.411,154a4.266,4.266,0,0,0-4.266,4.266c0,2.494,2.336,5.48,3.551,6.872a.952.952,0,0,0,1.428,0c1.217-1.385,3.563-4.37,3.563-6.872A4.266,4.266,0,0,0,260.411,154Zm0,6.7a2.439,2.439,0,1,1,1.724-.714A2.438,2.438,0,0,1,260.411,160.7Z" transform="translate(-256.145 -154)" fill="#9fcc47"/></svg>
                                            </span>
                                            London
                                        </td>
                                        <td>2</td>
                                        <td>$245</td>
                                    </tr>
                                @endfor
                                </tbody>
                            </table> --}}
                        </div>
                        <div class="col-12 pb-2">
                            {{-- <x-pagination-component :pagination="$pagination" /> --}}
                        </div>
                    </div>
                </div>
            </div>	
        </div>
    </div>
    <div class="row row-cols-1 row-cols-md-1 row-cols-lg-1 row-cols-xl-1 col-12" id="analysis_table_chart" style="padding:0 2.5rem; padding-top:14px;">
        <div class="col">
            <div class="card box" style="background:rgb(66, 68, 72)">
                <div class="card-header col-12 p-3 d-flex align-items-center">
                    <div class="col-6 d-flex align-items-center">
                    </div>
                    <div class="col-6 d-flex align-items-center justify-content-end">
                      <a class="btn btn-rounded btn-medium btn-bordered mr-2 export-chart-btn">EXPORT REPORT</a>
                      <a class="btn btn-rounded btn-medium btn-primary-dark">MORE DETAILS</a>
                    </div>
                  </div>
                <div id="analysis_page_chart" class="col-12 p-2"></div>
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
    </script>
    <script src="/assets/js/moment.js"></script>
    <script src="/assets/js/analysis-page.js"></script>
@endsection