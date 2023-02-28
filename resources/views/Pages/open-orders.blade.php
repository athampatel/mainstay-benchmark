@extends('layouts.dashboard')

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection

@section('content')
<div class="backdrop d-none">
    <div class="loader"></div>
</div>
<div class="home-content">
    <span class="page_title">Open order</span>
    <div class="padding-y-40 open-orders">
        <div class="row">
            <div class="col-12">
                <div class="card box">
                    <div class="card-header col-12 p-3 d-flex border-0">
                        <div class="col-6 d-flex align-items-center d-none d-lg-block">
                        </div>
                        <div class="col-12 col-lg-6 col-md-12  d-flex align-items-center justify-content-end flex-wrap col-filter">            
                            <div class="position-relative item-search">
                                <input type="text" class="form-control form-control-sm datatable-search-input" placeholder="Search in All Columns" id="open-orders-page-search" aria-controls="help-page-table">
                                <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="open-orders-page-search-img">
                            </div> 
                            <div class="position-relative datatable-filter-div">
                                <select name="" class="datatable-filter-count" id="open-orders-page-filter-count">
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
                                        <img src="/assets/images/svg/print-report-icon.svg" alt="" class="position-absolute" id="dashboard-open-orders-print-icon">
                                    </a>
                                </div>
                                <div class="datatable-report">
                                    <a href="">
                                        <img src="/assets/images/svg/export-report-icon.svg" alt="" class="position-absolute" id="dashboard-open-orders-report-icon">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body col-12 padding-y-0 position-relative">
                        <div class="page-table-loader-div d-none">
                            <div class="chart-loader1"></div>
                        </div>
                        <div class="table-responsive overflow-hidden" id="open-orders-page-table-div">
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
{{-- @php 
    $notifications = [
        [
            'title' => 'New Customers',
            'desc' =>  '5 new user registered',
            'time' =>  '5 Sec ago'
        ],
        [
            'title' => 'New Managers',
            'desc' =>  '2 new managers registered',
            'time' =>  '10 Sec ago'
        ],
        [
            'title' => 'Order Shipped',
            'desc' =>  'your order shipped',
            'time' =>  '10 Sec ago'
        ],
        [
            'title' => 'Order Shipped',
            'desc' =>  'your order shipped',
            'time' =>  '10 Sec ago'
        ],
        [
            'title' => 'Order Shipped',
            'desc' =>  'your order shipped',
            'time' =>  '10 Sec ago'
        ],
        [
            'title' => 'Order Shipped',
            'desc' =>  'your order shipped',
            'time' =>  '10 Sec ago'
        ],
        [
            'title' => 'Order Shipped',
            'desc' =>  'your order shipped',
            'time' =>  '10 Sec ago'
        ],
    ]
@endphp
<x-bottom-notification-component :count="count($notifications)" :notifications="$notifications" /> --}}

@endsection

@section('scripts')
     <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
     <script src="/assets/js/open-orders-page.js"></script>
    <script>
        const constants = <?php echo json_encode($constants); ?>;
    </script>
@endsection