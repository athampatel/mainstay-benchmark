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
                  <a class="btn btn-primary btn-small font-12" href="#">View Inventory</a>
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
                  <a class="btn btn-primary btn-small font-12" href="#">View Incoming Inventory</a>
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
                  <div class="date">$21,827.13</div>
                  <a class="btn btn-primary btn-small font-12" href="#">View Open Order</a>
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
					<div id="customer_sales_history" class="col-12 p-2"></div>
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
     <div class="col-4">
        <div class="card box">
          <div class="card-header col-12 p-3">
            <div class="col-12 d-flex align-items-center">              
              <h4 class="mb-0 title-4">Total Customer Spending</h4>
            </div>
          </div>
					<div id="chart3" class="col-12 p-2"></div>
        </div>
     </div>	
     <div class="col-8">
        <div class="card box">
          <div class="card-header col-12 p-3 d-flex">
            <div class="col-6 d-flex align-items-center">
              <h4 class="mb-0 title-4">Recent Invoiced Orders</h4>
            </div>
            <div class="col-6 d-flex align-items-center justify-content-end">              
              <a class="btn btn-rounded btn-medium btn-bordered mr-2">EXPORT REPORT</a>
              <a class="btn btn-rounded btn-medium btn-primary">MORE DETAILS</a>
            </div>
          </div>
          <div class="card-body col-12 p-3">
          <div class="table-responsive">
							<table id="example2" class="table table-bordered">
                <thead>
									<tr>
										<th>Item ID</th>
										<th>Customer name</th>
										<th>Customer email</th>
										<th>Total items</th>
										<th>Price</th>
										<th>Date</th>
                    <th>Location</th>
                    <th>Status</th>
									</tr>
								</thead>
								<tbody id="invoice-orders-table-body">
									{{-- <tr>
										<td><a href="javascript:void(0)" class="item-number font-12 btn btn-primary btn-rounded">#89742</a></td>
										<td><a href="javascript:void(0)" class="customer-name">Adams Baker</a></td>
                    <td><a href="mailto:adamsbaker@mail.com" class="customer-name">adamsbaker@mail.com</a></td>
										<td>2</td>
										<td>$245</td>
										<td>Apr 08, 2021</td>
										<td><span class="svg-icon location-icon">
                              <svg xmlns="http://www.w3.org/2000/svg" width="8.542" height="11.46" viewBox="0 0 8.542 11.46"><path class="location-svg" d="M260.411,154a4.266,4.266,0,0,0-4.266,4.266c0,2.494,2.336,5.48,3.551,6.872a.952.952,0,0,0,1.428,0c1.217-1.385,3.563-4.37,3.563-6.872A4.266,4.266,0,0,0,260.411,154Zm0,6.7a2.439,2.439,0,1,1,1.724-.714A2.438,2.438,0,0,1,260.411,160.7Z" transform="translate(-256.145 -154)" fill="#9fcc47"/></svg>
                        </span>London
                    </td>
                    <td>Completed</td>
									</tr> --}}
                  {{-- <tr>
                  <td><a href="javascript:void(0)" class="item-number font-12 btn btn-primary btn-rounded">#89742</a></td>
										<td><a href="javascript:void(0)" class="customer-name">Adams Baker</a></td>
                    <td><a href="mailto:adamsbaker@mail.com" class="customer-name">adamsbaker@mail.com</a></td>
										<td>2</td>
										<td>$245</td>
										<td>Apr 08, 2021</td>
										<td><span class="svg-icon location-icon">
                              <svg xmlns="http://www.w3.org/2000/svg" width="8.542" height="11.46" viewBox="0 0 8.542 11.46"><path class="location-svg" d="M260.411,154a4.266,4.266,0,0,0-4.266,4.266c0,2.494,2.336,5.48,3.551,6.872a.952.952,0,0,0,1.428,0c1.217-1.385,3.563-4.37,3.563-6.872A4.266,4.266,0,0,0,260.411,154Zm0,6.7a2.439,2.439,0,1,1,1.724-.714A2.438,2.438,0,0,1,260.411,160.7Z" transform="translate(-256.145 -154)" fill="#9fcc47"/></svg>
                        </span>London
                    </td>
                    <td>Completed</td>
									</tr>
                  <tr>
                  <td><a href="javascript:void(0)" class="item-number font-12 btn btn-primary btn-rounded">#89742</a></td>
										<td><a href="javascript:void(0)" class="customer-name">Adams Baker</a></td>
                    <td><a href="mailto:adamsbaker@mail.com" class="customer-name">adamsbaker@mail.com</a></td>
										<td>2</td>
										<td>$245</td>
										<td>Apr 08, 2021</td>
										<td><span class="svg-icon location-icon">
                              <svg xmlns="http://www.w3.org/2000/svg" width="8.542" height="11.46" viewBox="0 0 8.542 11.46"><path class="location-svg" d="M260.411,154a4.266,4.266,0,0,0-4.266,4.266c0,2.494,2.336,5.48,3.551,6.872a.952.952,0,0,0,1.428,0c1.217-1.385,3.563-4.37,3.563-6.872A4.266,4.266,0,0,0,260.411,154Zm0,6.7a2.439,2.439,0,1,1,1.724-.714A2.438,2.438,0,0,1,260.411,160.7Z" transform="translate(-256.145 -154)" fill="#9fcc47"/></svg>
                        </span>London
                    </td>
                    <td>Completed</td>
									</tr>
                  <tr>
                  <td><a href="javascript:void(0)" class="item-number font-12 btn btn-primary btn-rounded">#89742</a></td>
										<td><a href="javascript:void(0)" class="customer-name">Adams Baker</a></td>
                    <td><a href="mailto:adamsbaker@mail.com" class="customer-name">adamsbaker@mail.com</a></td>
										<td>2</td>
										<td>$245</td>
										<td>Apr 08, 2021</td>
										<td><span class="svg-icon location-icon">
                              <svg xmlns="http://www.w3.org/2000/svg" width="8.542" height="11.46" viewBox="0 0 8.542 11.46"><path class="location-svg" d="M260.411,154a4.266,4.266,0,0,0-4.266,4.266c0,2.494,2.336,5.48,3.551,6.872a.952.952,0,0,0,1.428,0c1.217-1.385,3.563-4.37,3.563-6.872A4.266,4.266,0,0,0,260.411,154Zm0,6.7a2.439,2.439,0,1,1,1.724-.714A2.438,2.438,0,0,1,260.411,160.7Z" transform="translate(-256.145 -154)" fill="#9fcc47"/></svg>
                        </span>London
                    </td>
                    <td>Completed</td>
									</tr>
                  <tr>
                  <td><a href="javascript:void(0)" class="item-number font-12 btn btn-primary btn-rounded">#89742</a></td>
										<td><a href="javascript:void(0)" class="customer-name">Adams Baker</a></td>
                    <td><a href="mailto:adamsbaker@mail.com" class="customer-name">adamsbaker@mail.com</a></td>
										<td>2</td>
										<td>$245</td>
										<td>Apr 08, 2021</td>
										<td><span class="svg-icon location-icon">
                              <svg xmlns="http://www.w3.org/2000/svg" width="8.542" height="11.46" viewBox="0 0 8.542 11.46"><path class="location-svg" d="M260.411,154a4.266,4.266,0,0,0-4.266,4.266c0,2.494,2.336,5.48,3.551,6.872a.952.952,0,0,0,1.428,0c1.217-1.385,3.563-4.37,3.563-6.872A4.266,4.266,0,0,0,260.411,154Zm0,6.7a2.439,2.439,0,1,1,1.724-.714A2.438,2.438,0,0,1,260.411,160.7Z" transform="translate(-256.145 -154)" fill="#9fcc47"/></svg>
                        </span>London
                    </td>
                    <td>Completed</td>
									</tr>
                  <tr>
                  <td><a href="javascript:void(0)" class="item-number font-12 btn btn-primary btn-rounded">#89742</a></td>
										<td><a href="javascript:void(0)" class="customer-name">Adams Baker</a></td>
                    <td><a href="mailto:adamsbaker@mail.com" class="customer-name">adamsbaker@mail.com</a></td>
										<td>2</td>
										<td>$245</td>
										<td>Apr 08, 2021</td>
										<td><span class="svg-icon location-icon">
                              <svg xmlns="http://www.w3.org/2000/svg" width="8.542" height="11.46" viewBox="0 0 8.542 11.46"><path class="location-svg" d="M260.411,154a4.266,4.266,0,0,0-4.266,4.266c0,2.494,2.336,5.48,3.551,6.872a.952.952,0,0,0,1.428,0c1.217-1.385,3.563-4.37,3.563-6.872A4.266,4.266,0,0,0,260.411,154Zm0,6.7a2.439,2.439,0,1,1,1.724-.714A2.438,2.438,0,0,1,260.411,160.7Z" transform="translate(-256.145 -154)" fill="#9fcc47"/></svg>
                        </span>London
                    </td>
                    <td>Completed</td>
									</tr>					 --}}
								</tbody>
								<tfoot>
                <tr>
										<th>Item ID</th>
										<th>Customer name</th>
										<th>Customer email</th>
										<th>Total items</th>
										<th>Price</th>
										<th>Date</th>
                    <th>Location</th>
                    <th>Status</th>
									</tr>
								</tfoot>
							</table>
						</div>
            <div class="d-flex col-12 justify-content-end pb-2">
                <a href="" class="item-number font-12 btn btn-primary btn-rounded">View more</a>
              </div>
          </div>
        </div>
     </div>	
    </div>

    </div>
  </div>  <!--- home-content ---->
@endsection

<script type="text/javascript">
  // const logged_customer= '{{Auth::user()}}';
</script>
