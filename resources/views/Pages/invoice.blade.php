@extends('layouts.dashboard')

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection

@section('title')
{{config('constants.page_title.customers.invoice')}} - Benchmark
@endsection

@section('content')
<div class="home-content">
    <span class="page_title">Invoiced Orders</span>
    <div style="display: inline-block;">
        <label for="" class="position-relative" id="analysis_year_select_label">
            <span class="year_filter">By Year</span>
            @php
            $year = intval(date('Y'));
            @endphp
            <select name="type" id="invoice_year_select" class="rounded analysis_select">
                @for($i = $year ; $i >= 2018; $i-- )
                    <option value="{{$i}}" {{$i == $year ? 'selected' : ''}}>{{$i}}</option>
                @endfor
            </select>
            <div class="down-arrow"></div>
        </label>
    </div>
    <div class="padding-y-40 open-orders">
        <div class="row">
            <div class="col-12">
                <div class="card box min-height-75 mb-0 border-bottom-radius-0">
                    <div class="card-header col-12 p-3 d-flex border-0">
                        <div class="col-6 d-flex align-items-center d-none d-lg-block">
                        </div>
                        <div class="col-6 d-flex align-items-center justify-content-end flex-wrap col-filter">            
                            <div class="position-relative item-search">
                                <input type="text" class="form-control form-control-sm datatable-search-input" placeholder="Search in All Columns" id="invoice-orders-page-search" aria-controls="help-page-table">
                                <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="invoice-orders-page-search-img">
                            </div> 
                            <div class="position-relative datatable-filter-div">
                                <select name="" class="datatable-filter-count" id="invoice-orders-page-filter-count">
                                    <option value="12" selected>12 Items</option>
                                    <option value="15">15 Items</option>
                                    <option value="20">20 Items</option>
                                    <option value="50">50 Items</option>
                                    <option value="100">100 Items</option>
                                </select>
                                <img src="/assets/images/svg/filter-arrow_icon.svg" alt="" class="position-absolute datatable-filter-img">
                            </div>
                            <div class="datatable-export justify-content-center gap-15 cursor-pointer" id="invoice-order-export">
                                <div class="user-select-none">Export</div>
                                <div class="d-flex justify-content-center align-items-center position-relative">
                                    <a href="" class="d-flex justify-content-center align-items-center">
                                        <img src="/assets/images/svg/export-report-icon.svg" alt="" class="position-absolute" id="dashboard-invoice-orders-report-icon">
                                    </a>
                                    <div class="dropdown-menu export-drop-down-table customer d-none" aria-labelledby="" id="export-invoice-page-drop">
                                        <a href="/invoice-export/csv" class="dropdown-item export-invoice-page-item" data-type="csv" id="invoice-csv-export">Export to Excel</a>
                                        <a href="/invoice-export/pdf" class="dropdown-item export-invoice-page-item" data-type="pdf" id="invoice-pdf-export">Export to PDF</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table_loader d-none">
                        <div class="chart-loader1"></div>
                    </div>
                    <div class="card-body col-12 padding-y-0">
                        <div class="table-responsive overflow-hidden" id="invoice-orders-page-table-div">
                        </div>
                    </div>
                </div>
                <div class="col-12 pb-2 card box mt-0 border-top-radius-0 box-shadow-none">
                    <div id="pagination_disp"></div>
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
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const constants = <?php echo json_encode($constants); ?>;
        const searchWords = <?php echo json_encode($searchWords); ?>;
        const env_maximum = '{{ env('EXPORT_MAXIMUM') }}';
    </script>
     <script src="/assets/js/invoice-orders-page.js"></script>
@endsection