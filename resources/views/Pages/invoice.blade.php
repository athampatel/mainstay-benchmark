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
    <span class="page_title">Invoice</span>
    <div class="padding-y-40 open-orders">
        <div class="row">
            <div class="col-12">
                <div class="card box">
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
                                    <option value="5" >5 Items</option>
                                    <option value="10">10 Items</option>
                                    <option value="12" selected>12 Items</option>
                                    <option value="20">20 Items</option>
                                </select>
                                <img src="/assets/images/svg/filter-arrow_icon.svg" alt="" class="position-absolute datatable-filter-img">
                            </div>
                            <div class="datatable-export">
                                <div class="datatable-print">
                                    <a href="">
                                        <img src="/assets/images/svg/print-report-icon.svg" alt="" class="position-absolute" id="invoice-orders-print-page-icon">
                                    </a>
                                </div>
                                <div class="datatable-report">
                                    <a href="">
                                        <img src="/assets/images/svg/export-report-icon.svg" alt="" class="position-absolute" id="invoice-orders-report-page-icon">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body col-12 padding-y-0">
                        <div class="table-responsive overflow-hidden" id="invoice-orders-page-table-div">
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
                            <tbody id="invoice-orders-page-table-body">
                                    @foreach ($recent_orders['orders'] as $invoice)                              
                                    <tr>
                                        <td class="text-center"><a href="{{url('/invoice-detail')}}/{{$invoice['salesorderno']}}" class="item-number font-12 btn btn-primary btn-rounded">#{{$invoice['invoiceno']}}</a></td>
                                        <td class="text-center"><a href="javascript:void(0)" class="customer-name">{{$invoice['shiptoname']}}</a></td> 
                                        <td class="text-center"><a href="mailto:adamsbaker@mail.com" class="customer-email">{{Auth::user()->email}}</a></td> 
                                        <td class="text-center">{{$invoice['customerpono']}} </td>
                                        {{-- <td>${{$price}}</td> --}}
                                        <td class="text-center">{{$invoice['total_qty']}}</td>
                                        <td class="text-center">${{number_format($invoice['total'],0,".",",")}}</td>
                                        <td class="text-center">{{date('m-d-Y',strtotime($invoice['invoicedate']))}}</td>
                                        {{-- <td class="location text-center"><span class="svg-icon location-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="8.542" height="11.46" viewBox="0 0 8.542 11.46"><path class="location-svg" d="M260.411,154a4.266,4.266,0,0,0-4.266,4.266c0,2.494,2.336,5.48,3.551,6.872a.952.952,0,0,0,1.428,0c1.217-1.385,3.563-4.37,3.563-6.872A4.266,4.266,0,0,0,260.411,154Zm0,6.7a2.439,2.439,0,1,1,1.724-.714A2.438,2.438,0,0,1,260.411,160.7Z" transform="translate(-256.145 -154)" fill="#9fcc47"/></svg>
                                        </span> {{ucwords($invoice['shiptocity'])}}
                                        </td>
                                        
                                       <td class="status">{{ $invoice['orderstatus'] == 'C' ? 'Completed': 'Open'}}</td> 
                                        <td class="action text-center">
                                            <a href="/change-order/{{$invoice['salesorderno']}}">
                                                Change
                                            </a>
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16.899" height="16.87" viewBox="0 0 16.899 16.87">
                                                    <g id="pen" transform="translate(-181.608 -111.379)">
                                                        <path id="Path_955" data-name="Path 955" d="M197.835,114.471,195.368,112a1.049,1.049,0,0,0-1.437,0l-11.468,11.5a.618.618,0,0,0-.163.325l-.434,3.552a.52.52,0,0,0,.163.461.536.536,0,0,0,.38.163h.054l3.552-.434a.738.738,0,0,0,.325-.163l11.5-11.5a.984.984,0,0,0,.3-.7,1.047,1.047,0,0,0-.3-.732Zm-12.119,12.038-2.684.325.325-2.684,9.76-9.76,2.359,2.359Zm10.519-10.546-2.359-2.332.786-.786,2.359,2.359Z" transform="translate(0 0)" fill="#F96969" stroke="#F96969" stroke-width="0.5"/>
                                                    </g>
                                                </svg>                         
                                            </span>
                                        </td>--}}
                                        <td class="status">Shipped</td>
                                        <td class="action text-center">
                                        <a href="/invoice-detail/{{$invoice['salesorderno']}}" class="btn btn-primary btn-rounded text-capitalize text-dark open-view-details" target="_blank">
                                            view details
                                        </a>
                                    </td>
                                    </tr>
                                @endforeach

                               
                            </tbody>
                        </table>

                        </div>
                        <div class="page-table-loader-div d-none">
                            <div class="chart-loader1"></div>
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
    <script>
        const constants = <?php echo json_encode($constants); ?>;
        @if(!empty($recent_orders['orders']))     
            var recent_orders = <?php echo json_encode($recent_orders); ?>;       
        @endif
    </script>
     <script src="/assets/js/invoice-orders-page.js"></script>
@endsection