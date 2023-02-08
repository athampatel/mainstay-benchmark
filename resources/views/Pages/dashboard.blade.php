@extends('layouts.dashboard')

@section('content')
<div class="home-content">
  <span class="page_title">Dashboard</span>
    <div class="overview-boxes widget_container_cards col-12">
      <div class="row row-cols-1 row-cols-md-3 row-cols-lg-3 row-cols-xl-3 col-12">
        {{-- customer info card --}}
        <div class="col">
          <div class="card box">						
            <div class="card-body col-12 d-flex align-items-center height_144">
              <div class="box-icon">
                <div class="icon-wrapper rounder-border">
                  <img src="assets/images/svg/customer_info_icon.svg" />
                </div>  
              </div>
              <div class="box-details">
                <div class="name">Customer info</div>
                <div class="row py-1">
                  <div class="col-4 card-item-header d-flex justify-content-between"><div>Name</div><div>:</div></div>
                  <div class="col-8 card-item-body">Adams Baker</div>
                </div>
                <div class="row py-1">
                  <div class="col-4 card-item-header d-flex justify-content-between"><div>Bill to Address</div><div>:</div></div>
                  <div class="col-8 card-item-body">3589 sagamore Pkwy N, Suite 220, Indiana, Lafayette,47904</span></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        {{-- Region manager info card --}}
        <div class="col">
          <div class="card box">						
            <div class="card-body col-12 d-flex align-items-center height_144">
              <div class="box-icon">
                <div class="icon-wrapper rounder-border"> 
                  <img src="assets/images/svg/region_manager_info_icon.svg" />
                </div>  
              </div>
              <div class="box-details w-75">
                <div class="name">Region Manager info</div>
                <div class="row py-1">
                  <div class="col-4 card-item-header d-flex justify-content-between"><div>Name</div><div>:</div></div>
                  <div class="col-6 card-item-body">Tom Hanney</div>
                </div>
                <div class="row py-1">
                  <div class="col-4 card-item-header d-flex justify-content-between"><div>Email</div><div>:</div></div>
                  <div class="col-6 card-item-body">tomhanney@gmail.com</div>
                </div>
                <div class="row py-1">
                  <div class="col-4 card-item-header d-flex justify-content-between"><div>Phone Number</div><div>:</div></div>
                  <div class="col-6 card-item-body">999 333 4568</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        {{-- Open orders card --}}
        <div class="col">
          <div class="card box">						
            <div class="card-body col-12 d-flex align-items-center height_144">
              <div class="box-icon">
                <div class="icon-wrapper rounder-border">
                  <img src="assets/images/svg/open-orders.svg" />
                </div>
              </div>
              <div class="box-details">
                <div class="name">Open Orders</div>
                <div class="date">$21,827.13</div>
                <a class="btn btn-primary btn-small font-12 dashboard-button" href="#">View Open Order</a>
              </div>
            </div>
          </div>
        </div>					
      </div> 

      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-2 col-12">
        <div class="col">
          <div class="card box">
            <div class="card-header col-12 p-3 d-flex align-items-center border-0">
              <div class="col-6 d-flex align-items-center">
                <div class="box-icon small-icon rounder-border">
                  <img src="assets/images/svg/sale-invoice-order.svg" />
                </div>  
                <h4 class="mb-0 title-4">Sale/Invoice Orders</h4>
              </div>
              <div class="col-6 d-flex align-items-center justify-content-end">
                <a class="btn btn-rounded btn-medium btn-bordered mr-2">EXPORT REPORT</a>
                <a class="btn btn-rounded btn-medium btn-primary">MORE DETAILS</a>
              </div>
            </div>
            <div id="customer_sales_history" class="col-12 p-2"></div>
          </div>
        </div>

        <div class="col">
          <div class="card box">
              <div class="card-header col-12 p-3 d-flex align-items-center border-0">
                <div class="col-6 d-flex align-items-center">
                  <div class="box-icon small-icon rounder-border">
                    <img src="assets/images/svg/open-orders.svg" />
                  </div>  
                  <h4 class="mb-0 title-4">Open Orders</h4>
                </div>
                <div class="col-6 d-flex align-items-center justify-content-end">
                  <a class="btn btn-rounded btn-medium btn-bordered mr-2">EXPORT REPORT</a>
                  <a class="btn btn-rounded btn-medium btn-primary">MORE DETAILS</a>
                </div> 
              </div>
            <div id="dashboard-open-orders-chart" class="col-12 p-2"></div>
          </div>
        </div>	
      </div>

    <!---ROW 3-->

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-2 col-12">
     <div class="col-4">
        <div class="card box">
          <div class="card-header col-12 p-3 border-0">
            <div class="col-12 d-flex align-items-center">              
              <h4 class="mb-0 title-4">Total Customer Spending</h4>
            </div>
          </div>
					<div id="customer-spending-chart" class="col-12 p-2"></div>
        </div>
     </div>	
     <div class="col-8">
        <div class="card box">
          <div class="card-header col-12 p-3 d-flex border-0">
            <div class="col-6 d-flex align-items-center">
              <h4 class="mb-0 title-4">Recent Invoiced Orders</h4>
            </div>
            <div class="col-6 d-flex align-items-center justify-content-end">            
              <div class="position-relative">
                <input type="text" class="datatable-search-input" placeholder="Search in All Columns" id="open-orders-chart-search" aria-controls="help-page-table">
                <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="open-orders-chart-search-img">
              </div> 
              {{-- <a class="btn btn-rounded btn-medium btn-bordered mr-2">EXPORT REPORT</a> --}}
              {{-- <a class="btn btn-rounded btn-medium btn-primary">MORE DETAILS</a> --}}
              <div class="position-relative datatable-filter-div">
                <select name="" class="datatable-filter-count" id="dashboard-open-orders-filter-count">
                  <option value="5" selected>05 Items</option>
                  <option value="10">10 Items</option>
                  <option value="20">20 Items</option>
                </select>
                <img src="/assets/images/svg/filter-arrow_icon.svg" alt="" class="position-absolute datatable-filter-img">
              </div>
              {{-- export icons --}}
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
          <div class="card-body col-12 p-3">
          <div class="table-responsive">
							<table id="dashboard-recent-invoice-order-table" class="table bench-datatable">
                <thead>
									<tr>
										<th class="border-0">ID</th>
										<th class="border-0">Customer name</th>
										<th class="border-0">Customer email</th>
										<th class="border-0">Total items</th>
										<th class="border-0">Price</th>
										<th class="border-0">Date</th>
                    <th class="border-0">Location</th>
                    <th class="border-0">Status</th>
                    <th class="border-0">Action</th>
									</tr>
								</thead>
								<tbody id="invoice-orders-table-body">
									@for($i = 0; $i < 50; $i++)
                  <tr>
										<td><a href="javascript:void(0)" class="item-number font-12 btn btn-primary btn-rounded">#89742-{{$i}}</a></td>
										<td><a href="javascript:void(0)" class="customer-name">Adams Baker</a></td>
                    <td><a href="mailto:adamsbaker@mail.com" class="customer-email">adamsbaker@mail.com</a></td>
										<td>2</td>
										<td>$245</td>
										<td>Apr 08, 2021</td>
										<td class="location"><span class="svg-icon location-icon">
                              <svg xmlns="http://www.w3.org/2000/svg" width="8.542" height="11.46" viewBox="0 0 8.542 11.46"><path class="location-svg" d="M260.411,154a4.266,4.266,0,0,0-4.266,4.266c0,2.494,2.336,5.48,3.551,6.872a.952.952,0,0,0,1.428,0c1.217-1.385,3.563-4.37,3.563-6.872A4.266,4.266,0,0,0,260.411,154Zm0,6.7a2.439,2.439,0,1,1,1.724-.714A2.438,2.438,0,0,1,260.411,160.7Z" transform="translate(-256.145 -154)" fill="#9fcc47"/></svg>
                        </span>London
                    </td>
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
                  @endfor
								</tbody>
							</table>
						</div>
            <div class="d-flex col-12 justify-content-end pb-2">
                <a href="" class="item-number font-12 btn btn-primary btn-rounded">View more</a>
              </div>
          </div>
        </div>
     </div>	
    </div>

    @if(Auth::user()->is_vmi == 1)
    {{-- vmi users --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-2 col-12">
      <div class="col">
        <div class="card box">						
          <div class="card-body col-12 d-flex align-items-center">
            <div class="box-icon">
              <div class="icon-wrapper rounder-border">
                <img src="assets/images/svg/noun-inventory.svg" />
              </div>  
            </div>
            <div class="box-details">
              <div class="name">inventory details</div>
              <div class="date">01-22-2023</div>
              <a class="btn btn-primary btn-small font-12 dashboard-button" href="#">View Inventory</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card box">						
          <div class="card-body col-12 d-flex align-items-center">
            <div class="box-icon">
              <div class="icon-wrapper rounder-border"> 
                <img src="assets/images/svg/onsite-count.svg" />
              </div>  
            </div>
            <div class="box-details">
              <div class="name">Next Onsite Count</div>
              <div class="date">01-22-2023</div>
              <a class="btn btn-primary btn-small font-12 dashboard-button" href="#">View Next Onsite Count</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif

    </div>
  </div>  <!--- home-content ---->
@endsection

@section('scripts')
<script src="/assets/js/customer-dashboard.js"></script>
@endsection