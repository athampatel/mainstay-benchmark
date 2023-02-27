@extends('layouts.dashboard')

@section('content')
<div class="home-content">
    <span class="page_title">Help</span>
    {{-- <div class="">
        <div class="col-9">
            <div class="card box">
              <div class="card-header col-12 p-3">
                <div class="col-6 d-flex align-items-center">
                  <div class="box-icon">
                    <img src="assets/images/svg/home_rounded_color.svg" />
                  </div>  
                  <h4 class="mb-0 text-uppercase">Customer Sales History</h4>
                </div>
               </div>
                <div id="cutomersaleshistory" class="col-12 p-2"></div>
            </div>
         </div>	
    </div> --}}
    {{-- <table>
      <thead>
        <th>Id</th>
        <th>Title</th>
        <th>Body</th>
      </thead>
      <tbody>
        @foreach ($posts as $post)    
          <tr>
            <td>{{$post->id}}</td>
            <td>{{$post->title}}</td>
            <td>{{$post->body}}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{ $posts->links() }} --}}


    <div class="table-card" style="padding:0 2.5rem;" id="table-table">
      <div class="row">
          <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-2 col-12">
              <div class="col-12">
                 <div class="card box card-background" style="background-color:#424448;border-radius:0.625rem;color:#fff;">
                      <div class="card-body col-12 p-3">
                          <div class="table-responsive">
                              {{-- <table id="help-page-table" class="table">
                                <thead>
                                  <tr>
                                    <th>Id</th>
                                    <th>Title</th>
                                    <th>Body</th>
                                  </tr>
                                </thead>
                                  <tbody>
                                      @foreach($posts as $post)
                                          <tr>
                                            <td>{{$post->id}}</td>
                                            <td>{{$post->title}}</td>
                                            <td>{{$post->body}}</td>
                                          </tr>
                                      @endforeach
                                  </tbody> --}}
                              </table>
                              {{-- <x-pagination-component :pagination="$pagination" /> --}}
                          </div>
                      </div>
                 </div>
              </div>	
          </div>
      </div>
  </div>
</div>
{{-- @php 
    $notifications = [
        [
            'title' => 'New Customers',
            'desc' =>  '5 new user registered',
            'time' =>  '5 Sec ago'
        ],
        [
            'title' => 'New Managers',
            'desc' =>  '2 new managers registered',
            'time' =>  '10 Sec ago'
        ],
        [
            'title' => 'Order Shipped',
            'desc' =>  'your order shipped',
            'time' =>  '10 Sec ago'
        ],
    ]
@endphp
<x-bottom-notification-component :count="count($notifications)" :notifications="$notifications" /> --}}
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
<script src="assets/js/moment.js"></script>
<script>
 if($(document.body).find('#help-page-table').length > 0){
      const open_table = $('#help-page-table').DataTable( {
          searching: true,
          lengthChange: false,
          pageLength:5,
          paging: false,
          // searching: false,
          ordering: false,
          info: false,
      });
      let searchbox = `<div><input type="search" class="form-control form-control-sm" placeholder="Search in All Columns" id="help-page-table-search" aria-controls="help-page-table"><img src="/assets/images/svg/grid-search.svg" alt=""></div>`;
      let countfilter = `<div id="counter_length_filter"><select id="counter_length_select"><option value="10">10 Items</option><option value="20">20 Items</option><option value="30">30 Items</option></select><div class="counter_down-arrow"></div></div>`;
      searchbox += countfilter;
      $('#help-page-table_filter').append(searchbox);
      $('#help-page-table-search').keyup(function(){
          open_table.search($(this).val()).draw() ;
      }) 
  }

  // const bottom_nofication_arrow = document.getElementById('bottom_message_arrow');
  // const notification_bottom = document.querySelector('.notfication_bottom');
  // const notification_cancel = document.querySelector('.notification_bottomn_cancel');
  // bottom_nofication_arrow.onclick = function(){
  //     notification_bottom.classList.toggle('active');
  // }
  // // bottom notification close
  // const bottom_nofication_close = document.querySelector('.messages .header .close');
  // bottom_nofication_close.onclick = function(){
  //     notification_bottom.classList.remove('active');
  // }
  // notification_cancel.onclick = function(){
  //     notification_bottom.classList.add('d-none');
  //     notification_cancel.classList.add('d-none');
  // }
</script>
@endsection