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
    <div class="" style="display: flex; justify-content:space-between;width:calc(100% - 50px);align-items:center;">
        <div style="display:flex;justify-content:center;align-items:center;">
            <div class="page_title">Analysis</div>
            <div style="display:flex;gap:20px;">
                <label for="" style="color: #424448;font-weight:500; position:relative;">
                    By Item
                    <select name="" id="analysis_item_select" class="rounded analysis_select">
                        <option value="" selected>Select Item</option>
                    </select>
                    <div class="down-arrow"></div>
                </label>
                <label for="" style="color: #424448;font-weight:500; position:relative;">
                    By Range
                    <select name=""id="analysis_range_select"class="rounded analysis_select">
                        <option value="0" selected>Select Range</option>
                        <option value="1" selected>Last Month</option>
                        <option value="2" selected>Quarterly</option>
                        <option value="3" selected>Half Yearly</option>
                        <option value="4" selected>By Range</option>
                    </select>
                    <div class="down-arrow"></div>
                </label>
            </div>
        </div>
        <div>
            <label class="switch1">
                <input type="checkbox" id="tab_input">
                <span class="slider1"></span>
            </label>
        </div>
    </div>
    <div class="table-card d-none" style="padding:0 2.5rem;" id="table-table">
        <div class="row">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-2 col-12">
                <div class="col-12">
                   <div class="card box card-background" style="background-color:#424448;border-radius:0.625rem;color:#fff;">
                        <div class="card-body col-12 p-3">
                            <div class="table-responsive">
                                <table id="vmi-page-table" class="table">
                                    <thead>
                                        <tr>
                                            <th>Invoice Number</th>
                                            <th>Invoice Date</th>
                                            <th>Customer PO Number</th>
                                            <th>Ship-to City,State</th>
                                            <th>Total Number of Items</th>
                                            <th>Total Invoiced Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($response as $res)
                                            <tr>
                                                <td><span class="order-id">#{{$res['no']}}</span></td>
                                                <td>{{$res['date']}}</td>
                                                <td>{{$res['custpono']}}</td>
                                                <td>{{$res['city']}}</td>
                                                <td>{{$res['total_items']}}</td>
                                                <td>{{$res['total_amount']}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                   </div>
                </div>	
            </div>
        </div>
    </div>
    {{-- <div class="col-12 p-2" id="analysis_page_chart"></div> --}}
    <div class="row row-cols-1 row-cols-md-1 row-cols-lg-1 row-cols-xl-1 col-12" id="table-chart" style="padding:0 2.5rem;">
        <div class="col">
            <div class="card box" style="background:rgb(66, 68, 72)">
                <div class="card-header col-12 p-3 d-flex align-items-center">
                    <div class="col-6 d-flex align-items-center">
                      {{-- <div class="box-icon small-icon rounder-border">
                        <img src="assets/images/svg/sale-invoice-order.svg" />
                      </div>  
                      <h4 class="mb-0 title-4">Sale/Invoice Orders</h4> --}}
                    </div>
                    <div class="col-6 d-flex align-items-center justify-content-end">
                      <a class="btn btn-rounded btn-medium btn-bordered mr-2">EXPORT REPORT</a>
                      <a class="btn btn-rounded btn-medium btn-primary">MORE DETAILS</a>
                    </div>
                  </div>
                <div id="analysis_page_chart" class="col-12 p-2"></div>
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
     <script src="assets/js/moment.js"></script>
     {{-- moment js --}}
     {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" integrity="sha512-CryKbMe7sjSCDPl18jtJI5DR5jtkUWxPXWaLCst6QjH8wxDexfRJic2WRmRXmstr2Y8SxDDWuBO6CQC6IE4KTA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
     
     <script>
         /*================================
        datatable active
        ==================================*/
        if ($('#dataTable').length) {
            $('#dataTable').DataTable({
                responsive: true
            });
        }
        
        let response_ = <?php echo json_encode($response); ?>;
        // let today = moment().format('DD-MM-yyyy');
        // console.log(today,'__today');
        // console.log(response_,'__response');

        if($(document.body).find('#vmi-page-table').length > 0){
            const open_table = $('#vmi-page-table').DataTable( {
                searching: true,
            });
            let searchbox = `<input type="search" class="form-control form-control-sm" placeholder="Search in All Columns" id="vmi-page-table-search" aria-controls="vmi-page-table"><img src="/assets/images/svg/grid-search.svg" alt="">`;
            $('#vmi-page-table_filter').append(searchbox);
            $('#vmi-page-table-search').keyup(function(){
                open_table.search($(this).val()).draw() ;
            }) 
        }

        $(document).on('change','#analysis_item_select',function(){
            // let val = $(this).val();
            // console.log(val,'___val');
            console.log('__changed');
        })
        $(document).on('change','#analysis_range_select',function(){
            $(this).closest('.down-arrow').css("transform", "rotate(-180deg)");
        })

        $(document).on('change','#tab_input',function(){
            if($('#tab_input').is(':checked')){
                // console.log('__checked');
                // graph view
                $('#table-chart').removeClass('d-none');
                $('#table-table').addClass('d-none');
                // table-table
            } else {
                $('#table-chart').addClass('d-none');
                $('#table-table').removeClass('d-none');
            }
        })
        getChartData(response_);
        function getChartData(res){
        let arr1 = [];
          res.forEach(da => {
            let month  =moment(da.date,'yyyy-mm-dd').format('mm')
            // console.log(month,'__month')
            if(arr1[month]){
                arr1[month] += da.total_amount;
            } else {
                arr1[month] = da.total_amount;
            }
          });
          let final = [];
          for(let num = 01;num<=12;num++){
            // let i1 = num.toString();
            let num1 = num < 9 ? `0${num}`: num;
            if(arr1[num1]){
                final.push(arr1[num1]);
            } else {
                final.push(0);
            }
          }
          return final;
        }
            $counts = getChartData(response_);
            
            var options = {
                series: [{
                        name: 'sales',
                        data: $counts
                    },
                ],
                chart: {
                    foreColor: '#9ba7b2',
                    type: 'bar',
                    height: 750,
                    zoom:{
                        enabled:false
                    },
                    toolbar : {
                        show:true
                    },
                    dropShadow:{
                        enabled:true,
                        top:3,
                        left:14,
                        blur:4,
                        opacity:0.10,
                    }
                },
                stroke: {
                    width: 2,
                    curve:'straight'
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '36%',
                    },
                },
                dataLabels: {
                     enabled: false //changeable
                },
                xaxis: {
                    type:'month',
                    categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        gradientToColors: ['#A4CD3C'],
                        shadeIntensity: 1,
                        type: 'horizontal',
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100, 100, 100]
                    },
                },
                markers: {
                    size: 4,
                    colors: ["#A4CD3C"],
                    strokeColors: "#fff",
                    strokeWidth: 2,
                    hover: {
                        size: 7,
                    }
                },
                colors: ["#A4CD3C"],
                
                yaxis: {
                    title: {
                        text: ''
                    }
                },
            };

            var chart = new ApexCharts(document.querySelector("#analysis_page_chart"), options);
            chart.render();
        // }
        // chart work end

        // function printfunction(){
        //     console.log('__print work');
        // }
     </script>
@endsection