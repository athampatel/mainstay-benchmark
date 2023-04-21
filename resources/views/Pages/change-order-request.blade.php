@extends('layouts.dashboard')

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection

@section('title')
{{config('constants.page_title.customers.change_request')}} - Benchmark
@endsection

@section('content')
<div class="backdrop d-none">
    <div class="loader"></div>
</div>
<div class="home-content">
    <span class="page_title">Change Order Requests</span>
    <div class="padding-y-40 open-orders">
        <div class="row">
            <div class="col-12">
                <div class="card box min-height-75 mb-0 border-bottom-radius-0">
                    <div class="card-header col-12 p-3 d-flex border-0">
                        <div class="col-6 d-flex align-items-center d-none d-lg-block">
                        </div>
                        <div class="col-12 col-lg-6 col-md-12  d-flex align-items-center justify-content-end flex-wrap col-filter">            
                            <div class="position-relative item-search">
                                <input type="text" class="form-control form-control-sm datatable-search-input" placeholder="Search in All Columns" id="change-order-request-page-search" aria-controls="help-page-table">
                                <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="change-order-request-page-search-img">
                            </div> 
                            <div class="position-relative datatable-filter-div">
                                <select name="" class="datatable-filter-count" id="change-order-request-page-filter-count">
                                    <option value="12" selected>12 Items</option>
                                    <option value="15">15 Items</option>
                                    <option value="20">20 Items</option>
                                    <option value="50">50 Items</option>
                                    <option value="100">100 Items</option>
                                </select>
                                <img src="/assets/images/svg/filter-arrow_icon.svg" alt="" class="position-absolute datatable-filter-img">
                            </div>
                            <div class="datatable-export justify-content-center gap-15 cursor-pointer" id="change-request-page-export">
                                <div class="user-select-none">Export</div>
                                <div class="d-flex justify-content-center align-items-center position-relative">
                                    <a href="" class="d-flex justify-content-center align-items-center">
                                        <img src="/assets/images/svg/export-report-icon.svg" alt="" class="position-absolute" id="change-request-report-icon">
                                    </a>
                                    <div class="dropdown-menu export-drop-down-table customer d-none" aria-labelledby="export-admin-customers" id="export-change-request-page-drop">
                                        <a class="dropdown-item export-change-request-page-item" data-type="csv">Export to Excel</a>
                                        <a class="dropdown-item export-change-request-page-item" data-type="pdf">Export to PDF</a>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="table_loader d-none">
                        <div class="chart-loader1"></div>
                    </div>
                    <div class="card-body col-12 padding-y-0 position-relative">
                        <div class="table-responsive overflow-hidden" id="change-order-request-page-table-div">
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

@section('scripts')
     <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
     <script src="/assets/js/change-order-request-page.js"></script>
    <script>
        const constants = <?php echo json_encode($constants); ?>;
        const searchWords = <?php echo json_encode($searchWords); ?>;

        $(document).on('click','#change-request-page-export',function(e){
            e.preventDefault();
            $('#export-change-request-page-drop').toggleClass('d-none');
        })

        $(document).on('click','.export-change-request-page-item',function(e){
            e.preventDefault();
            console.log('__change_request_page_export_item_clicked');
        })
    </script>
@endsection