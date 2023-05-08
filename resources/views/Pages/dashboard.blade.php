@extends('layouts.dashboard')

@section('title')
{{config('constants.page_title.customers.dashboard')}} - Benchmark
@endsection
@section('content')
<div class="backdrop d-none">
  <div class="loader"></div>
</div>
<div class="home-content">
  <span class="page_title">Dashboard</span>
    <div class="overview-boxes widget_container_cards col-12">
      <div class="row row-cols-1 row-cols-md-3 row-cols-lg-3 row-cols-xl-3 col-12 widget-listing">
        <div class="col-sm-12 col-md-12 col-lg-4 no-right-pad">
          <div class="card box equal-height">						
            <div class="card-header col-12 p-3 d-flex align-items-center border-0 flex-wrap">
                <div class="col-12 col-md-12 col-lg-12 d-flex align-items-center">
                  <div class="box-icon small-icon rounder-border">
                    @php
                      $customer_session =  session('customers');
                    @endphp 
                  @if($user->profile_image)
                    <img src="/{{$user->profile_image}}" class="rounded-circle regin_manager_profile"/>
                  @else
                  <svg xmlns="http://www.w3.org/2000/svg" width="72" height="72" viewBox="0 0 72 72">
                    <g id="Group_1194" data-name="Group 1194" transform="translate(-332 -170)">
                      <g id="Ellipse_11" data-name="Ellipse 11" transform="translate(332 170)" fill="#424448" stroke="#9fcc47" stroke-width="1">
                        <circle cx="36" cy="36" r="36" stroke="none"/>                        
                      </g>
                      <g id="Group_1188" data-name="Group 1188" transform="translate(-2599.398 2386)">
                        <path id="Path_1047" data-name="Path 1047" d="M180.966,274.262c.318-.088.643-.163.974-.219a9.649,9.649,0,0,1,1.043-.094,20.61,20.61,0,0,0-12.342-9.437,10.892,10.892,0,0,1-1.511.606h-.006a11.862,11.862,0,0,1-7.332,0,10.7,10.7,0,0,1-1.518-.606A21.1,21.1,0,0,0,144.929,285v.937h30.81a7.774,7.774,0,0,1-.387-.937,8,8,0,0,1-.262-.937,8.232,8.232,0,0,1-.187-1.768,8.369,8.369,0,0,1,6.065-8.032Z" transform="translate(2799.469 -2445.766)" fill="#9fcc47"/>
                        <path id="Path_1048" data-name="Path 1048" d="M241.381,91.523a10.987,10.987,0,1,0-3.61-.606,10.928,10.928,0,0,0,3.61.606Z" transform="translate(2723.546 -2272.525)" fill="#9fcc47"/>
                        <path id="Path_1049" data-name="Path 1049" d="M429.71,357.481c-.075-.006-.144-.006-.219-.006a6.854,6.854,0,0,0-.8.044,8.242,8.242,0,0,0-.981.169,7.424,7.424,0,0,0-5.427,8.968c.019.081.044.162.062.244q.065.244.15.468a7.417,7.417,0,1,0,7.214-9.886Zm.931,11.592h-1.6v-1.6h1.6Zm.063-6.4-.406,4.241h-.912l-.412-4.241V360.71h1.73Z" transform="translate(2553.235 -2528.361)" fill="#9fcc47"/>
                      </g>
                    </g>
                  </svg>
                  @endif
                  </div>  
                  <h4 class="mb-0 title-4">Customer Info</h4>
                </div>             
              </div>	
              <div class="card-body col-12 d-flex align-items-center pt-0">
                <div class="box-details col-12"> 
                
                <div class="row py-1">
                  <div class="col-3 card-item-header d-flex justify-content-between"><div>Name</div><div>:</div></div>
                    <div class="col-8 card-item-body">{{$customer_session[0]->customername}}</div>
                </div>
                <div class="row py-1">
                  <div class="col-3 card-item-header d-flex justify-content-between"><div>Billing Address</div><div>:</div></div>
                  @php 
                  $session_address = "";
                  if($customer_session){
                    $session_address .= $customer_session[0]->addressline1 == "" ? '' : $customer_session[0]->addressline1 . ', ';
                    $session_address .= $customer_session[0]->addressline2 == "" ? '' : $customer_session[0]->addressline2 . ', ';
                    $session_address .= $customer_session[0]->addressline3 == "" ? '' : $customer_session[0]->addressline3 . ', ';
                    $session_address .= $customer_session[0]->state == "" ? '' : $customer_session[0]->state. ', ';
                    $session_address .= $customer_session[0]->city == "" ? '' : $customer_session[0]->city . ', ';
                    $session_address .= $customer_session[0]->zipcode == "" ? '' : $customer_session[0]->zipcode;
                  }
                  @endphp
                  <div class="col-8 card-item-body">{{$session_address}}</span></div>
                </div>
                <div class="row py-1">
                  <div class="col-3 card-item-header d-flex justify-content-between"><div>Phone #</div><div>:</div></div>
                    <div class="col-8 card-item-body">{{$customer_session[0]->phone_no}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-12 col-md-12 col-lg-4 padd-small">
          <div class="card box equal-height min-height-160">					
            <div class="card-header col-12 p-3 d-flex align-items-center border-0 flex-wrap">
              <div class="col-12 col-md-12 col-lg-12 d-flex align-items-center">
                <div class="box-icon small-icon rounder-border">
                  @if($region_manager->profile)
                      <img src="/{{$region_manager->profile}}" class="rounded-circle regin_manager_profile"/>
                  @else
                      <img src="assets/images/svg/region_manager_info_icon.svg" />
                  @endif  
                </div>  
                <h4 class="mb-0 title-4">Regional Manager Info</h4>
              </div>             
            </div>	
            <div class="card-body col-12 d-flex align-items-center pt-0">
              <div class="box-details col-12">                
                <div class="row py-1">
                  <div class="col-3 card-item-header d-flex justify-content-between"><div>Name</div><div>:</div></div>                  
                  <div class="col-8 card-item-body">{{$region_manager->name}}</div>
                </div>
                <div class="row py-1">
                  <div class="col-3 card-item-header d-flex justify-content-between"><div>Email</div><div>:</div></div>                  
                  <div class="col-8 card-item-body">{{$region_manager->email}}</div>
                </div>
                <div class="row py-1">
                  <div class="col-3 card-item-header d-flex justify-content-between"><div>Phone #</div><div>:</div></div>                  
                  <div class="col-8 card-item-body">{{$region_manager->phone_no}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-sm-12 col-md-12 col-lg-4 no-left-pad">
          <div class="card box equal-height">	
           <div class="card-header col-12 p-3 d-flex align-items-center border-0 flex-wrap">
                <div class="col-12 col-md-12 col-lg-12 d-flex align-items-center">
                  <div class="box-icon small-icon rounder-border">
                   <img src="assets/images/svg/open-orders.svg" />
                  </div>  
                  <h4 class="mb-0 title-4">Open Orders</h4>
                </div>             
              </div>	
              <div class="card-body col-12 d-flex align-items-center pt-0">
                <div class="box-details col-12 px-5">                 
                <div class="date" id="open-orders-total-amount">$ 0</div>
                <a class="btn btn-primary btn-small btn-rounded font-12 dashboard-button" href="/open-orders">View Open Orders</a>
              </div>
            </div>
          </div>
        </div>					
      </div> 

      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-2 col-12">
        <div class="col-sm-12 col-md-12 col-lg-6">
          <div class="card box item-card-block equal-height">
            <div class="card-header col-12 p-3 d-flex align-items-center border-0 flex-wrap">
              <div class="col-12 col-md-12 col-lg-6 d-flex align-items-center">
                <div class="box-icon small-icon rounder-border">
                  <img src="assets/images/svg/sale-invoice-order.svg" />
                </div>  
                <h4 class="mb-0 title-4">Sale/Invoice Orders</h4>
              </div>
              <div class="col-12 col-md-12 col-lg-6 d-flex align-items-center justify-content-end col-btn">
                <div class="position-relative">
                    <a class="btn btn-rounded btn-medium btn-bordered mr-2" id="export-sales-invoice" aria-haspopup="true" aria-expanded="false">EXPORT REPORT</a>
                    <div class="dropdown-menu export-drop-down d-none" aria-labelledby="export-sales-invoice" id="export-sales-invoice-drop">
                      <a class="dropdown-item export-sales-invoice-item" data-type="png">PNG</a>
                      <a class="dropdown-item export-sales-invoice-item" data-type="svg">SVG</a>
                      <a class="dropdown-item export-sales-invoice-item" data-type="csv">CSV</a>
                    </div>
                </div>
                <a class="btn btn-rounded btn-medium btn-primary" href="/invoice">MORE DETAILS</a>
              </div>
            </div>
            <div id="customer_sales_history" class="col-12 p-2">
              <div class="chart-loader-div d-none">
                <div class="chart-loader"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-12 col-md-12 col-lg-6 no-left-pad">
          <div class="card box item-card-block equal-height">
              <div class="card-header col-12 p-3 d-flex align-items-center border-0 flex-wrap">
                <div class="col-12 col-md-6 col-lg-6 d-flex align-items-center">
                  <div class="box-icon small-icon rounder-border">
                    <img src="assets/images/svg/open-orders.svg" />
                  </div>  
                  <h4 class="mb-0 title-4">Open Orders</h4>
                </div>
                <div class="col-12 col-md-6 col-lg-6 d-flex align-items-center justify-content-end col-btn">
                  <div class="position-relative">
                    <a class="btn btn-rounded btn-medium btn-bordered mr-2" id="export-open-oders-chart" aria-haspopup="true" aria-expanded="false">EXPORT REPORT</a>
                    <div class="dropdown-menu export-drop-down d-none" aria-labelledby="export-open-orders" id="export-open-orders-drop">
                      <a class="dropdown-item export-open-orders-item" data-type="png">PNG</a>
                      <a class="dropdown-item export-open-orders-item" data-type="svg">SVG</a>
                      <a class="dropdown-item export-open-orders-item" data-type="csv">CSV</a>
                    </div>
                  </div>
                  <a class="btn btn-rounded btn-medium btn-primary" href="/open-orders">MORE DETAILS</a>
                </div> 
              </div>
            <div id="dashboard-open-orders-chart" class="col-12 p-2">
              <div class="chart-loader-div d-none">
                <div class="chart-loader"></div>
              </div>
            </div>
          </div>
        </div>	
      </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-2 col-12">
      @if($saleby_productline)     
        <div class="col-sm-12 col-md-12 col-lg-5 no-right-pad">
            <div class="card box item-card-block equal-height">
              <div class="card-header col-12 p-3 d-flex align-items-center border-0 flex-wrap">
                <div class="col-12 col-md-6 col-lg-6 d-flex align-items-center">
                  <div class="box-icon small-icon rounder-border">
                    <img src="assets/images/svg/open-orders.svg" />
                  </div>  
                  <h4 class="mb-0 title-4">Total Customer Spending</h4>
                </div>
                <div class="col-12 col-md-6 col-lg-6 d-flex align-items-center justify-content-end col-btn">
                  <div class="position-relative">
                    <a class="btn btn-rounded btn-medium btn-bordered mr-2" id="export-total-spending-chart" aria-haspopup="true" aria-expanded="false">EXPORT REPORT</a>
                    <div class="dropdown-menu export-drop-down d-none" aria-labelledby="export-total-spending" id="export-total-spending-drop">
                      <a class="dropdown-item export-total-spending-item" data-type="png">PNG</a>
                      <a class="dropdown-item export-total-spending-item" data-type="svg">SVG</a>
                      <a class="dropdown-item export-total-spending-item" data-type="csv">CSV</a>
                    </div>
                  </div>
                </div> 
              </div>
              <div id="customer-spending-chart" class="col-12 p-2">
                <div class="chart-loader-div d-none">
                  <div class="chart-loader"></div>
                </div>
              </div>
            </div>
        </div>	
     @endif             
        <div class="col-sm-12 col-md-12  @if($saleby_productline) col-lg-7 @else col-lg-12 @endif">
            <div class="card box item-card-block equal-height">
              <div class="card-header col-12 p-3 d-flex border-0 flex-wrap">
                <div class="col-12 col-md-4 col-lg-4 d-flex align-items-center">
                  <h4 class="mb-0 title-4">Recent Invoiced Orders</h4>
                </div>
                <div class="col-12 col-md-8 col-lg-8 d-flex align-items-center justify-content-end col-filter flex-wrap">            
                </div>
              </div>
              <div class="card-body col-12 p-3">
              <div class="table-responsive" id="dashboard-recent-invoice-order-table-div">

                @if(!empty($recent_orders['orders']))    
                <table id="dashboard-recent-invoice-order-table" class="table bench-datatable">
                      <thead>
                          <tr>
                              <th class="border-0">ID</th>
                              <th class="border-0">Customer email</th>
                              <th class="border-0">Total items</th>
                              <th class="border-0">Price</th>
                              <th class="border-0">Date</th>
                              <th class="border-0">Location</th>
                          </tr>
                          </thead>
                          <tbody id="invoice-orders-table-body"></tbody>
                  </table>   
                @else
                  <div class="chart-loader-div">
                    <div class="chart-loader"></div>
                  </div>	
                @endif
                      
                
              </div>
                <div class="d-flex col-12 justify-content-end pb-2">
                    <a href="/invoice" class="item-number font-12 btn btn-primary btn-rounded">View more</a>
                  </div>
              </div>
            </div>
        </div>	
    </div>
    
    <div id="customer-spending-desc-chart"></div>
    @if(Auth::user()->is_vmi == 1)
    {{-- {{dd(Auth::user()->is_vmi)}} --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-2 col-12">
      <div class="col small-right-pad">
        <div class="card box">						
          <div class="card-body col-12 d-flex align-items-center equal-height">
            <div class="box-icon">
              <div class="icon-wrapper rounder-border">
                <img src="assets/images/svg/noun-inventory.svg" />
              </div>  
            </div>
            <div class="box-details">
              <div class="name">inventory details</div>
              <div class="date">{{ @session('vmi_physicalcountdate') ? date('m-d-Y',strtotime(@session('vmi_physicalcountdate'))) : ''}}</div>
              <a class="btn btn-primary btn-small btn-rounded font-12 dashboard-button" href="/vmi-user">View Inventory</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col small-left-pad">
        <div class="card box">						
          <div class="card-body col-12 d-flex align-items-center equal-height">
            <div class="box-icon">
              <div class="icon-wrapper rounder-border"> 
                <img src="assets/images/svg/onsite-count.svg" />
              </div>  
            </div>
            <div class="box-details">
              <div class="name">Next Onsite Count</div>
              <div class="date">{{ @session('vmi_nextonsitedate') ? date('m-d-Y',strtotime(@session('vmi_nextonsitedate'))) : ''}}</div>
              <a class="btn btn-primary btn-small btn-rounded font-12 dashboard-button" href="/vmi-user">View Next Onsite Count</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif
    </div>
  </div>  
@endsection

@section('scripts')
<script src="/assets/js/customer-dashboard.js"></script>
<script type="text/javascript">
    const constants = <?php echo json_encode($constants); ?>;
    const searchWords = <?php echo json_encode($searchWords); ?>;
    @if($saleby_productline)     
       var sell_bycat = <?php echo json_encode($saleby_productline); ?>;
       var data_bycat = <?php echo json_encode($data_productline); ?>;            
       var data_bycat_desc = <?php echo json_encode($data_productline_desc); ?>;            
    @endif 
</script>
@endsection