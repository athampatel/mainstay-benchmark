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
                <div class="card box">
                    <div class="card-header col-12 p-3 d-flex border-0">
                        <div class="col-6 d-flex align-items-center d-none d-lg-block">
                        </div>
                        <div class="col-12 col-lg-6 col-md-12 d-flex align-items-center justify-content-end flex-wrap col-filter">            
                            <div class="position-relative item-search">
                                <input type="text" class="form-control form-control-sm datatable-search-input" placeholder="Search in All Columns" id="vmi-page-search" aria-controls="">
                                <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="vmi-page-search-img">
                            </div> 
                            <div class="position-relative datatable-filter-div">
                                <select name="" class="datatable-filter-count" id="vmi-page-filter-count">
                                    <option value="5">5 Items</option>
                                    <option value="10">10 Items</option>
                                    <option value="12" selected>12 Items</option>
                                    <option value="20">20 Items</option>
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
                    <div class="card-body col-12 padding-y-0">
                        <div class="table-responsive" id="vmi_table_disp">
                            {{-- <table id="vmi-page-table" class="table bench-datatable border-0">
                                <thead>
                                    <tr>
                                        <th class="border-0">Customer Item Number</th>
                                        <th class="border-0">Benchmark Item Number</th>
                                        <th class="border-0">Item Description</th>
                                        <th class="border-0">Vendor Name</th>
                                        <th class="border-0">Oty on Hand</th>
                                        <th class="border-0">Quantity purchased(Year)</th>                        
                                    </tr>
                                </thead>
                                <tbody id="">
                                @for($i = 0; $i < 50; $i++)
                                    <tr>
                                        <td><a href="javascript:void(0)" class="item-number font-12 btn btn-rounded">#8974224</a></td>
                                        <td><a href="javascript:void(0)" class="customer-name">BC18765451</a></td>
                                        <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum fermentum ut augue sit amet molestie.</td>
                                        <td>MAYTEX CROP</td>
                                        <td>2555</td>
                                        <td>582155</td>
                                    </tr>
                                @endfor
                                </tbody>
                            </table> --}}
                        </div>
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