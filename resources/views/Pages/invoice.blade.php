@extends('layouts.dashboard')

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection

@section('content')
{{-- <div class="backdrop d-none">
    <div class="loader"></div>
</div> --}}
<div class="home-content">
    <span class="page_title">Invoiced Orders</span>
    <div class="padding-y-40 open-orders">
        <div class="row">
            <div class="col-12">
                <div class="card box min-height-75">
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
                        {{-- @if(!empty($recent_orders['orders']))     
                        <table id="invoice-orders-page-table" class="table bench-datatable border-0">
                            <thead>
                                <tr>
                                    <th class="border-0 text-center">ID</th>
                                    <th class="border-0 text-center">Customer name</th>
                                    <th class="border-0 text-center">Customer email</th>
                                    <th class="border-0 text-center">Customer Po Number</th>
                                    <th class="border-0 text-center">Total items</th>
                                    <th class="border-0 text-center">Price</th>
                                    <th class="border-0 text-center">Date</th>
                                    <th class="border-0 text-center">Status</th>
                                    <th class="border-0 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="invoice-orders-page-table-body"> --}}
                                    {{-- @foreach ($recent_orders['orders'] as $invoice)                              
                                    <tr>
                                        <td class="text-center"><a href="{{url('/invoice-detail')}}/{{$invoice['salesorderno']}}" class="item-number font-12 btn btn-primary btn-rounded">#{{$invoice['invoiceno']}}</a></td>
                                        <td class="text-center"><a href="javascript:void(0)" class="customer-name">{{$invoice['shiptoname']}}</a></td> 
                                        <td class="text-center"><a href="mailto:adamsbaker@mail.com" class="customer-email">{{Auth::user()->email}}</a></td> 
                                        <td class="text-center">{{$invoice['customerpono']}} </td>
                                        <td class="text-center">{{$invoice['total_qty']}}</td>
                                        <td class="text-center">${{number_format($invoice['total'],0,".",",")}}</td>
                                        <td class="text-center">{{date('m-d-Y',strtotime($invoice['invoicedate']))}}</td>
                                        <td class="status">Shipped</td>
                                        <td class="action text-center">
                                        <a href="/invoice-detail/{{$invoice['salesorderno']}}" class="btn btn-primary btn-rounded text-capitalize text-dark open-view-details" target="_blank">
                                            view details
                                        </a>
                                    </td>
                                    </tr>
                                @endforeach --}}

                               
                            {{-- </tbody>
                        </table> --}}
                        {{-- @endif; --}}
                        </div>
                        {{-- @if(empty($recent_orders['orders']))  --}}
                        {{-- <div class="page-table-loader-div d-none">
                            <div class="chart-loader1"></div>
                        </div> --}}
                        <div class="col-12 pb-2">
                            <div id="pagination_disp"></div>
                        </div>
                        {{-- @endif --}}
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
    <script>
        const constants = <?php echo json_encode($constants); ?>;
        // @if(!empty($recent_orders['orders']))     
            // var recent_orders = <?php echo json_encode($recent_orders); ?>;       
        // @endif
    </script>
     <script src="/assets/js/invoice-orders-page.js"></script>
@endsection