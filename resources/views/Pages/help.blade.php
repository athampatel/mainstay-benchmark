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
                              <table id="help-page-table" class="table">
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
                                  </tbody>
                              </table>
                              <x-pagination-component :pagination="$pagination" />
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
</script>
@endsection