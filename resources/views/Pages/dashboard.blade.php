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
          <div class="table-responsive" id="dashboard-recent-invoice-order-table-div">
							
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