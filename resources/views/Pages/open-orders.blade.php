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
    <span class="page_title">Open order</span>
    <div class="table-card" style="padding:0 2.5rem;">
        <div class="row">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-2 col-12">
                <div class="col-12">
                   <div class="card box card-background" style="background-color:#424448;border-radius:0.625rem;color:#fff;">
                        <div class="card-body col-12 p-3">
                            <div class="table-responsive">
                                <table id="open-orders-table" class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Customer name</th>
                                            <th>Customer email</th>
                                            <th>Total items</th>
                                            <th>Price</th>
                                            <th>Date</th>
                                            {{-- <th>Location</th> --}}
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
                                                {{-- <td>{{$order['location']}}</td> --}}
                                                <td class="order-status">Open</td>
                                                <td class="order-action"><a href="/order-change-order/{{$order['salesorderno']}}">change <img src="/assets/images/svg/change_order_pin_icon.svg" alt=""></a></td>
                                            </tr>
                                            @endforeach
                                            {{-- @for($i =1 ;$i<50;$i++)
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
                                            @endfor --}}
                                        </tbody>
                                </table>
                            </div>
                        </div>
                   </div>
                </div>	
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
     <!-- Start datatable js -->
     <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
     {{-- Gokul --}}
     <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
     
     <script>
         /*================================
        datatable active
        ==================================*/
        if ($('#dataTable').length) {
            $('#dataTable').DataTable({
                responsive: true
            });
        }
        // const open_table = $('#open-orders-table').DataTable(); 
        // search
      

        // AjaxRequestCom('/customer-open-orders-details','GET','','customerOpenOrdersDisplay');
        // function customerOpenOrdersDisplay(res){
        //     console.log(res,'__Response');
        // }

        if($(document.body).find('#open-orders-table').length > 0){
                const open_table = $('#open-orders-table').DataTable( {
                // lengthChange: true,
                // pageLength:5,
                // paging: true,
                searching: true,
                // ordering: true,
                // filter:true,
                // info: true,
                // buttons: [ 'copy', 'excel', 'pdf', 'print'],
                // language: {
                //     oPaginate: {
                //     sNext: '<i class="fa fa-forward"></i>',
                //     sPrevious: '<i class="fa fa-backward"></i>',
                //     sFirst: '<i class="fa fa-step-backward"></i>',
                //     sLast: '<i class="fa fa-step-forward"></i>'
                //     }
                // }   
            });
            let searchbox = `<input type="search" class="form-control form-control-sm" placeholder="Search in All Columns" id="open-orders-table-search" aria-controls="open-orders-table"><img src="/assets/images/svg/grid-search.svg" alt="">`;
            $('#open-orders-table_filter').append(searchbox);

            $('#open-orders-table-search').keyup(function(){
                open_table.search($(this).val()).draw() ;
            }) 
        }
     </script>
@endsection