
@extends('backend.layouts.master')

@section('title')
Customers - Admin Panel
@endsection


@section('admin-content')  

<div class="home-content">
    <span class="page_title">Customers</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">                
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-3 col-md-12">
                                    <p class="float-right mb-2">
                                        <a class="btn btn-primary btn-rounded text-white text-capitalize" href="{{ route('admin.users.create') }}">{{ config('constants.label.admin.buttons.create_customer') }}</a>
                                    </p>            
                                </div>                  
                                <div class="col-12 col-lg-9 col-md-12  d-flex align-items-center justify-content-end flex-wrap col-filter"> 
                                    <div class="position-relative item-search">   
                                        <input type="text" class="form-control1 form-control-sm datatable-search-input-admin" placeholder="Search in All Columns" id="admin_customer_search" value="{{!$search ? '' : $search}}" aria-controls="help-page-table">
                                        <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="admin-customer-search-img">
                                    </div>     
                                    @php 
                                    $select_options = [10,12,20];
                                    @endphp
                                    <div class="position-relative datatable-filter-div">  
                                        <select name="" class="datatable-filter-count" id="admin-customer-filter-count">
                                            @foreach($select_options as $select_option)
                                                <option value="{{$select_option}}" {{$select_option == $paginate['per_page'] ? 'selected' :'' }}>{{$select_option}} Items</option>
                                            @endforeach
                                        </select>
                                        <img src="/assets/images/svg/filter-arrow_icon.svg" alt="" class="position-absolute datatable-filter-img">
                                    </div>
                                    <form id="customer_from" action="/admin/customers" method="GET"></form>                                    
                                    <div class="datatable-export">
                                        <div class="datatable-print">
                                            <a href="">
                                                <img src="/assets/images/svg/print-report-icon.svg" alt="" class="position-absolute" id="admin-customer-print-icon">
                                            </a>
                                        </div>
                                        <div class="datatable-report position-relative">
                                            {{-- <a href="/admin/exportAllCustomers"> --}}
                                            <a href="">
                                                <img src="/assets/images/svg/export-report-icon.svg" alt="" class="position-absolute" id="admin-customer-report-icon">
                                            </a>
                                            <div class="dropdown-menu export-drop-down-table d-none" aria-labelledby="export-admin-customers" id="export-admin-customers-drop">
                                                <a href="/admin/exportAllCustomers" class="dropdown-item export-admin-customer-item" data-type="csv">Export to Excel</a>
                                                <a href='/admin/exportAllCustomerInPdf' class="dropdown-item export-admin-customer-item" data-type="pdf">Export to PDF</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="data-tables table-responsive">
                                @include('backend.layouts.partials.messages')
                                <table id="backend_customers" class="text-center datatable-dark backend_datatables">
                                    <thead class="text-capitalize">
                                        <tr>
                                            <th width="10%">{{ config('constants.label.admin.customer_no') }}</th>
                                            <th width="10%">Name</th>
                                            <th width="10%">Email</th>
                                            <th width="10%">{{ config('constants.label.admin.ar_division_no') }}</th>
                                            <th width="10%">{{ config('constants.label.admin.relational_manager') }}</th>
                                            <th width="10%">Status</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td> <a class="" href="{{ route('admin.users.edit', $user->id) }}">{{ $user->customerno }}</a></td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->ardivisionno }}</td>
                                            <td>
                                                @if($user->sales_person != '')
                                                    {{$user->sales_person}} ({{$user->person_number}})
                                                @else
                                                    -
                                                @endif
                                            </td>                                    
                                            <td>
                                                <div class="status-btns">
                                                    @if( $user->active == 1)
                                                        {{-- <span class="btn btn-success btn-rounded text-white" style="padding:5px;pointer-events:none;">Active</span>            --}}
                                                        <span class="btn bm-btn-light-blue btn-rounded text-white text-capitalize" style="padding:5px;pointer-events:none;">Active</span>           
                                                    @elseif( $user->active == 0 && $user->is_deleted == 0)
                                                        <a href="{{env('APP_URL')}}admin/user/{{$user->id}}/change-status/{{$user->activation_token}}" target="_blank" class="btn btn-rounded btn-light text-dark bm-btn-secondary text-capitalize" style="padding:5px;">New</a>
                                                    @endif

                                                    @if($user->is_vmi == 1 && $user->vmi_companycode != '' )
                                                        <a data-customer="{{$user->id}}" class="btn btn-rounded btn-info text-capitalize text-white bm-btn-info"  href="{{ route('admin.users.inventory',$user->id) }}" title="Add / Update Inventory">Inventory</a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-wrapper btns-2">
                                                    <a class="btn btn-rounded btn-medium btn-primary text-capitalize" href="{{ route('admin.users.edit', $user->id) }}">Edit</a>
                                                    <a class="btn btn-rounded btn-medium btn-bordered bm-btn-delete text-capitalize" href="{{ route('admin.users.destroy', $user->id) }}"
                                                    onclick="event.preventDefault();deleteCustomer({{$user->id}})">
                                                        Delete
                                                    </a>
                                                    {{-- <a class="btn btn-rounded btn-medium btn-bordered" href="{{ route('admin.users.destroy', $user->id) }}"
                                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $user->id }}').submit();">
                                                        Delete
                                                    </a> --}}
                                                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: none;">
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if(!empty($users))
                                @if($paginate['last_page'] > 1)
                                    <div class="mt-3">
                                        <x-pagination-component :pagination="$paginate" :search="$search" />
                                    </div>
                                @endif    
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
{{-- admin search modal work start --}}
<div class="modal fade show" id="searchmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" id="search_modal_disp_body">
                <div class="">
                    <a href="/roles">Roles</a>
                </div>
                <div class="">
                    <a href="/roles">Customers</a>
                </div>
                <div class="">
                    <a href="/roles">admins</a>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- admin search modal work start --}}
@php 
$searchWords = [
    [ 'name' => 'Roles','link' => '/admin/roles'],
    [ 'name' => 'Customers','link' => '/admin/customers'],
    [ 'name' => 'admins','link' => '/admin/admins'],
    [ 'name' => 'Signups','link' => '/admin/signups'],
    [ 'name' => 'roleres','link' => '/admin/signups'],
];
@endphp
@endsection

@section('scripts')
<script>
const searchWords = <?php echo json_encode($searchWords); ?>;
function deleteCustomer(id){
    Swal.fire({
    title: 'Are you sure?',
    text: "You want to delete the customer",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#9FCC47',
    cancelButtonColor: '#424448',
    confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
        document.getElementById(`delete-form-${id}`).submit();
        }
    })
}
</script>
@endsection
