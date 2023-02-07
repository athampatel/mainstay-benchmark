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
    <div class="table-card" style="padding:0 2.5rem;">
        <div class="row">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-2 col-12">
                <div class="col-12">
                   <div class="card box card-background" style="background-color:#424448;border-radius:0.625rem;color:#fff;">
                        <div class="card-body col-12 p-3">
                            <div class="table-responsive">
                                <table id="vmi-page-table" class="table">
                                    <thead>
                                        <tr>
                                            <th>Customer Item Number</th>
                                            <th>Benchmark Item Number</th>
                                            <th>Item Description</th>
                                            <th>Vendor Name</th>
                                            <th>Qty on Hand</th>
                                            <th>Qunatity purchased(year)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @foreach($orders as $order)
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
                                        @endforeach --}}
                                        @for($i =1 ;$i<50;$i++)
                                            <tr>
                                                <td><span class="order-id">#8974224</span></td>
                                                <td>BCI8765451</td>
                                                <td>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Veritatis, et.</td>
                                                <td>MAYTEX CORP</td>
                                                <td>2555</td>
                                                <td>582155</td>
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
        
        if($(document.body).find('#vmi-page-table').length > 0){
            const open_table = $('#vmi-page-table').DataTable( {
                searching: true,
            });
            let searchbox = `<input type="search" class="form-control form-control-sm" placeholder="Search in All Columns" id="vmi-page-table-search" aria-controls="vmi-page-table"><img src="/assets/images/svg/grid-search.svg" alt="">`;
            $('#vmi-page-table_filter').append(searchbox);
            // let filterbox = `<label>Show <select name="vmi-page-table_length" aria-controls="vmi-page-table" class="custom-select custom-select-sm form-control form-control-sm"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> entries</label>`
            // $(filterbox).insertAfter($('#vmi-page-table_filter'));


            $('#vmi-page-table-search').keyup(function(){
                open_table.search($(this).val()).draw() ;
            }) 
        }
     </script>
@endsection