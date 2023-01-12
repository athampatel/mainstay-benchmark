@extends('layouts.dashboard')

@section('content')
<div class="home-content">
    <span class="page_title">Dashboard</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-3 row-cols-xl-3 col-12">
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
                  <div class="button">View Inventory</div>
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
                  <div class="button">View Incoming Inventory</div>
                </div>
							</div>
						</div>
					</div>
					<div class="col">
            <div class="card box">						
							<div class="card-body col-12 d-flex align-items-center">
                <div class="box-icon">
                  <div class="icon-wrapper rounder-border">
                    <img src="assets/images/svg/open-orders.svg" />
                  </div>
                </div>
                <div class="box-details">
                  <div class="name">Open Orders</div>
                  <div class="date">01-22-2023</div>
                  <div class="button">View Open Order</div>
                </div>
							</div>
						</div>
					</div>					
				</div> <!--- rown ends here--->

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-2 col-12">
     <div class="col">
        <div class="card box">
          <div class="card-header col-12 p-3 d-flex align-items-center">
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
					<div id="chart1" class="col-12 p-2"></div>
        </div>
     </div>	
     <div class="col">
        <div class="card box">
          <div class="card-header col-12 p-3 d-flex align-items-center">
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
					<div id="chart2" class="col-12 p-2"></div>
        </div>
     </div>	
    </div>

    <!---ROW 3-->

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-2 col-12">
     <div class="col-3">
        <div class="card box">
          <div class="card-header col-12 p-3">
            <div class="col-6 d-flex align-items-center">
              <div class="box-icon small-icon rounder-border">
                <img src="assets/images/svg/home_rounded_color.svg" />
              </div>  
              <h4 class="mb-0 text-uppercase">Column Chart</h4>
            </div>
          </div>
					<div id="chart3" class="col-12 p-2"></div>
        </div>
     </div>	
     <div class="col-9">
        <div class="card box">
          <div class="card-header col-12 p-3">
            <div class="col-6 d-flex align-items-center">
              <div class="box-icon">
                <img src="assets/images/svg/home_rounded_color.svg" />
              </div>  
              <h4 class="mb-0 text-uppercase">Column Chart</h4>
            </div>
          </div>
					<div id="chart4" class="col-12 p-2"></div>
        </div>
     </div>	
    </div>

    </div>
  </div>  <!--- home-content ---->
@endsection
