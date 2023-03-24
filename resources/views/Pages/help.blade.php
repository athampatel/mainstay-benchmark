@extends('layouts.dashboard')

@section('content')
<div class="home-content">
    <span class="page_title">Help</span>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
<script>
 if($(document.body).find('#help-page-table').length > 0){
      const open_table = $('#help-page-table').DataTable( {
          searching: true,
          lengthChange: false,
          pageLength:5,
          paging: false,
          // searching: false,
          ordering: true,
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
  const constants = <?php echo json_encode($constants); ?>;
  const searchWords = <?php echo json_encode($searchWords); ?>;
</script>
@endsection