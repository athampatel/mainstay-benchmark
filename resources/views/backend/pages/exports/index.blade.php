@extends('backend.layouts.master')

@section('title')
Customer Export Requests - Admin Panel
@endsection

@section('admin-content')

<div class="home-content">
    <span class="page_title">Change Export Request</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-3 col-md-12">
                                    <p class="float-right mb-2">
                                        {{-- <a class="btn btn-primary btn-rounded text-capitalize bm-btn-primary text-white" href="{{ route('admin.users.create') }}">{{ config('constants.label.admin.buttons.create_request') }}</a> --}}
                                    </p>            
                                </div>
                                <div class="col-12 col-lg-9 col-md-12  d-flex align-items-center justify-content-end flex-wrap col-filter"> 
                                    <div class="position-relative item-search">
                                        <input type="text" class="form-control1 form-control-sm datatable-search-input-admin" placeholder="Search in All Columns" id="admin_exports_search" value="{{!$search ? '' : $search}}" aria-controls="help-page-table">
                                        <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="admin-exports-search-img">
                                    </div> 
                                    @php 
                                    $select_options = [10,12,20];
                                    @endphp
                                    <div class="position-relative datatable-filter-div">
                                        <select name="" class="datatable-filter-count" id="admin-exports-filter-count">
                                            @foreach($select_options as $select_option)
                                                <option value="{{$select_option}}" {{$select_option == $paginate['per_page'] ? 'selected' :'' }}>{{$select_option}} Items</option>
                                            @endforeach
                                        </select>
                                        <img src="/assets/images/svg/filter-arrow_icon.svg" alt="" class="position-absolute datatable-filter-img">
                                    </div>
                                    <form id="admins_exports_from" action="/admin/customers/exports" method="GET"></form>
                                    <div class="datatable-export">
                                        <div class="datatable-print admin">
                                            <a href="">
                                                <img src="/assets/images/svg/print-report-icon.svg" alt="" class="position-absolute" id="admin-exports-print-icon">
                                            </a>
                                        </div>
                                        <div class="datatable-report admin position-relative">
                                            <a href="">
                                                <img src="/assets/images/svg/export-report-icon.svg" alt="" class="position-absolute" id="admin-customer-exports-icon">
                                            </a>
                                            <div class="dropdown-menu export-drop-down-table d-none" aria-labelledby="export-admin-customers" id="export-admin-exports-drop">
                                                <a href="/admin/exportAllExports" class="dropdown-item export-admin-exports-item" data-type="csv">Export to Excel</a>
                                                <a href='/admin/exportAllExportsInPdf' class="dropdown-item export-admin-exports-item" data-type="pdf">Export to PDF</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>     
                            <div class="clearfix"></div>
                            <div class="data-tables table-responsive">
                                @include('backend.layouts.partials.messages')
                                <table id="backend_export_requests" class="text-center datatable-dark backend_datatables">
                                    <thead class="text-capitalize">
                                        <tr>
                                            <th width="10%">{{ config('constants.label.admin.customer_no') }}</th>
                                            {{-- <th width="10%">AR Division Number</th> --}}
                                            <th width="10%">Resource</th>
                                            <th width="10%">Type</th>
                                            <th width="10%">Requested Date</th>
                                            <th width="10%">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($customer_export_count as $_request)
                                        <tr>
                                            <td> <a>{{ $_request['customer_no'] }}</a></td>
                                            {{-- <td>{{ $_request['ardivisiono'] }}</td> --}}
                                            <td>{{ $_request['resource'] }}</td>                                                                
                                            <td>{{$_request['type'] == 1 ? 'CSV' : 'PDF'}}</td>
                                            <td>{{ \Carbon\Carbon::parse($_request['created_at'])->format('M d, Y') }}</td>
                                            <td>
                                                <a class="btn btn-info btn-rounded text-white" href="/admin/customer/request/{{$_request['id']}}">View Info</a>
                                            </td>                                    
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($paginate['last_page'] > 1)
                            <div class="mt-3">
                                <x-pagination-component :pagination="$paginate" :search="$search" />
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const searchWords = <?php echo json_encode($searchWords); ?>;
// function deleteCustomer(id){
//     Swal.fire({
//     title: 'Are you sure?',
//     text: "You want to delete the customer",
//     icon: 'warning',
//     showCancelButton: true,
//     confirmButtonColor: '#9FCC47',
//     cancelButtonColor: '#424448',
//     confirmButtonText: 'Yes, delete it!'
//     }).then((result) => {
//         if (result.isConfirmed) {
//         document.getElementById(`delete-form-${id}`).submit();
//         }
//     })
// }
</script>
@endsection