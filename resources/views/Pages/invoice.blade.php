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
    <span class="page_title">Invoiced Orders</span>
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
                            <div class="datatable-export">
                                <div class="datatable-print">
                                    <a href="">
                                        <img src="/assets/images/svg/print-report-icon.svg" alt="" class="position-absolute" id="invoice-orders-print-page-icon">
                                    </a>
                                </div>
                                <div class="datatable-report position-relative">
                                    <a href="#">
                                        <img src="/assets/images/svg/export-report-icon.svg" alt="" class="position-absolute" id="invoice-orders-report-page-icon">
                                    </a>
                                    <div class="dropdown-menu export-drop-down-table d-none" aria-labelledby="export-admin-customers" id="export-invoice-orders-drop">
                                        <a href="/admin/exportAllCustomers" class="dropdown-item export-invoice-orders-item" data-type="csv">Export to Excel</a>
                                        <a href='/admin/exportAllCustomerInPdf' class="dropdown-item export-invoice-orders-item" data-type="pdf">Export to PDF</a>
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
    <script>
        const constants = <?php echo json_encode($constants); ?>;
        const searchWords = <?php echo json_encode($searchWords); ?>;
        // @if(!empty($recent_orders['orders']))     
            // var recent_orders = <?php echo json_encode($recent_orders); ?>;       
        // @endif
    </script>
     <script src="/assets/js/invoice-orders-page.js"></script>
@endsection