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
                        <option value="1" selected>Last Month</option>
                        <option value="2" selected>Quarterly</option>
                        <option value="3" selected>Half Yearly</option>
                        <option value="4" selected>By Range</option>
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
    <div class="padding-y-40 open-orders">
        <div class="row">
            <div class="col-12">
                <div class="card box">
                    <div class="card-header col-12 p-3 d-flex border-0">
                        <div class="col-6 d-flex align-items-center">
                        </div>
                        <div class="col-6 d-flex align-items-center justify-content-end">            
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
                        <div class="table-responsive">
                            <table id="analysis-page-table" class="table bench-datatable border-0">
                                <thead>
                                    <tr>
                                        <th class="border-0">ID</th>
                                        <th class="border-0">Customer name</th>
                                        <th class="border-0">Customer email</th>
                                        <th class="border-0">Total items</th>
                                        <th class="border-0">Price</th>
                                        <th class="border-0">Date</th>
                                        <th class="border-0">Location</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                @for($i = 0; $i < 50; $i++)
                                    <tr>
                                        <td><a href="javascript:void(0)" class="item-number font-12 btn btn-rounded">#89742-{{$i}}</a></td>
                                        <td><a href="javascript:void(0)" class="customer-name">Adams Baker</a></td>
                                        <td><a href="mailto:adamsbaker@mail.com" class="customer-email">adamsbaker@mail.com</a></td>
                                        <td>2</td>
                                        <td>$245</td>
                                        <td>Apr 08, 2021</td>
                                        <td class="location">
                                            <span class="svg-icon location-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="8.542" height="11.46" viewBox="0 0 8.542 11.46"><path class="location-svg" d="M260.411,154a4.266,4.266,0,0,0-4.266,4.266c0,2.494,2.336,5.48,3.551,6.872a.952.952,0,0,0,1.428,0c1.217-1.385,3.563-4.37,3.563-6.872A4.266,4.266,0,0,0,260.411,154Zm0,6.7a2.439,2.439,0,1,1,1.724-.714A2.438,2.438,0,0,1,260.411,160.7Z" transform="translate(-256.145 -154)" fill="#9fcc47"/></svg>
                                            </span>
                                            London
                                        </td>
                                        <td class="status">Open</td>
                                        <td class="action">
                                            <a href="#">
                                                Change
                                            </a>
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16.899" height="16.87" viewBox="0 0 16.899 16.87">
                                                    <g id="pen" transform="translate(-181.608 -111.379)">
                                                        <path id="Path_955" data-name="Path 955" d="M197.835,114.471,195.368,112a1.049,1.049,0,0,0-1.437,0l-11.468,11.5a.618.618,0,0,0-.163.325l-.434,3.552a.52.52,0,0,0,.163.461.536.536,0,0,0,.38.163h.054l3.552-.434a.738.738,0,0,0,.325-.163l11.5-11.5a.984.984,0,0,0,.3-.7,1.047,1.047,0,0,0-.3-.732Zm-12.119,12.038-2.684.325.325-2.684,9.76-9.76,2.359,2.359Zm10.519-10.546-2.359-2.332.786-.786,2.359,2.359Z" transform="translate(0 0)" fill="#F96969" stroke="#F96969" stroke-width="0.5"/>
                                                    </g>
                                                </svg>                         
                                            </span>
                                        </td>
                                    </tr>
                                @endfor
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 pb-2">
                            {{-- <x-pagination-component :pagination="$pagination" /> --}}
                        </div>
                    </div>
                </div>
            </div>	
        </div>
    </div>
    <div class="row row-cols-1 row-cols-md-1 row-cols-lg-1 row-cols-xl-1 col-12" id="table-chart" style="padding:0 2.5rem;">
        <div class="col">
            <div class="card box" style="background:rgb(66, 68, 72)">
                <div class="card-header col-12 p-3 d-flex align-items-center">
                    <div class="col-6 d-flex align-items-center">
                      {{-- <div class="box-icon small-icon rounder-border">
                        <img src="assets/images/svg/sale-invoice-order.svg" />
                      </div>  
                      <h4 class="mb-0 title-4">Sale/Invoice Orders</h4> --}}
                    </div>
                    <div class="col-6 d-flex align-items-center justify-content-end">
                      <a class="btn btn-rounded btn-medium btn-bordered mr-2">EXPORT REPORT</a>
                      <a class="btn btn-rounded btn-medium btn-primary">MORE DETAILS</a>
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
     <script src="/assets/js/analysis-page.js"></script>
     <script>
        let response_ = <?php echo json_encode($response); ?>;
     </script>
@endsection