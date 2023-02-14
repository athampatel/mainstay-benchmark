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
    {{-- <div class="table-card" style="padding:0 2.5rem;">
        <div class="row">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-2 col-12">
                <div class="col-12">
                   <div class="card box card-background" style="background-color:#424448;border-radius:0.625rem;color:#fff;">
                        <div class="card-body col-12 p-3">
                            <div class="table-responsive">
                                <table id="open-orders-page-table" class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Customer name</th>
                                            <th>Customer email</th>
                                            <th>Total items</th>
                                            <th>Price</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td><span class="order-id">#{{$order['salesorderno']}}</span></td>
                                                <td>{{Auth::user()->name}}</td>
                                                <td>{{Auth::user()->email}}</td>
                                                <td>{{$order['total_quantity']}}</td>
                                                <td>${{$order['total_amount']}}</td>
                                                <td>{{$order['format_date']}}</td>
                                                <td class="order-status">Open</td>
                                                <td class="order-action"><a href="/order-change-order/{{$order['salesorderno']}}">change <img src="/assets/images/svg/change_order_pin_icon.svg" alt=""></a></td>
                                            </tr>
                                        @endforeach
                                            @for($i =1 ;$i<50;$i++)
                                                <tr>
                                                    <td><span class="order-id">#{{$i}}</span></td>
                                                    <td>{{Auth::user()->name}}</td>
                                                    <td>{{Auth::user()->email}}</td>
                                                    <td>10</td>
                                                    <td>175.1</td>
                                                    <td>Apr 8,2022</td>
                                                    <td><img src="/assets/images/svg/location_pin_icon.svg" alt="">london</td>
                                                    <td class="order-status">Open</td>
                                                    <td class="order-action">change<img src="/assets/images/svg/change_order_pin_icon.svg" alt=""></td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                </table>
                            </div>
                        </div>
                   </div>
                </div>	
            </div>
        </div>
    </div> --}}
    <div class="padding-y-40 open-orders">
        <div class="row">
            <div class="col-12">
                <div class="card box">
                    <div class="card-header col-12 p-3 d-flex border-0">
                        <div class="col-6 d-flex align-items-center">
                        </div>
                        <div class="col-6 d-flex align-items-center justify-content-end">            
                            <div class="position-relative">
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
                    <div class="card-body col-12 padding-y-0">
                        <div class="table-responsive" id="open-orders-page-table-div">
                            {{-- <table id="open-orders-page-table" class="table bench-datatable border-0">
                                <thead>
                                    <tr>
                                        <th class="border-0">ID</th>
                                        <th class="border-0">Customer name</th>
                                        <th class="border-0">Customer email</th>
                                        <th class="border-0">Total items</th>
                                        <th class="border-0">Price</th>
                                        <th class="border-0">Date</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="open-orders-page-table-body"> --}}
                                {{-- @for($i = 0; $i < 50; $i++)
                                    <tr>
                                        <td><a href="javascript:void(0)" class="item-number font-12 btn btn-rounded">#89742-{{$i}}</a></td>
                                        <td><a href="javascript:void(0)" class="customer-name">Adams Baker</a></td>
                                        <td><a href="mailto:adamsbaker@mail.com" class="customer-email">adamsbaker@mail.com</a></td>
                                        <td>2</td>
                                        <td>$245</td>
                                        <td>Apr 08, 2021</td>
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
                                @endfor --}}
                                {{-- </tbody>
                            </table> --}}
                        </div>
                        <div class="col-12 pb-2">
                            {{-- <x-pagination-component :pagination="[]" /> --}}
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
     <script src="/assets/js/open-orders-page.js"></script>
@endsection