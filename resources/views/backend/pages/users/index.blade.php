
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
                                        <div class="datatable-print admin">
                                            <a href="">
                                                <img src="/assets/images/svg/print-report-icon.svg" alt="" class="position-absolute" id="admin-customer-print-icon">
                                            </a>
                                        </div>
                                        <div class="datatable-report admin position-relative">
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
                                            <th>{{ config('constants.label.admin.customer_no') }}
                                                <span data-col='customerno' data-table='customers' data-ordertype='asc' class="asc">&#x2191;</span>
                                                <span data-col='customerno' data-table='customers' data-ordertype='desc' class="desc">&#x2193;</span>
                                            </th>
                                            <th>
                                                Profile Picture 
                                            </th>
                                            <th>Name
                                                <span data-col='name' data-table='customers' data-ordertype='asc' class="asc">&#x2191;</span>
                                                <span data-col='name' data-table='customers' data-ordertype='desc' class="desc">&#x2193;</span>
                                            </th>
                                            <th>Email
                                                <span data-col='email' data-table='customers' data-ordertype='asc' class="asc">&#x2191;</span>
                                                <span data-col='email' data-table='customers' data-ordertype='desc' class="desc">&#x2193;</span>
                                            </th>
                                            <th>{{ config('constants.label.admin.ar_division_no') }}
                                                <span data-col='ardivisionno' data-table='customers' data-ordertype='asc' class="asc">&#x2191;</span>
                                                <span data-col='ardivisionno' data-table='customers' data-ordertype='desc' class="desc">&#x2193;</span>
                                            </th>
                                            <th>{{ config('constants.label.admin.relational_manager') }}
                                                <span data-col='sales_person' data-table='customers' data-ordertype='asc' class="asc">&#x2191;</span>
                                                <span data-col='sales_person' data-table='customers' data-ordertype='desc' class="desc">&#x2193;</span>
                                            </th>
                                            <th>Status
                                                <span data-col='active' data-table='customers' data-ordertype='asc' class="asc">&#x2191;</span>
                                                <span data-col='active' data-table='customers' data-ordertype='desc' class="desc">&#x2193;</span>
                                            </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td> <a class="" href="{{ route('admin.users.edit', $user->id) }}">{{ $user->customerno }}</a></td>
                                            <td>
                                                @if($user->profile_image)
                                                    <img src="/{{$user->profile_image}}" id="admin_customers_profile" class="rounded-circle datatable_profile"/>
                                                @else
                                                    <img src="/assets/images/svg/user_logo.png" id="admin_customers_profile" class="rounded-circle datatable_profile"/>
                                                @endif  
                                            </td>
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
                                                <div class="btn-wrapper btns-2 no-wrap">
                                                    <a class="btn btn-rounded btn-medium btn-primary text-capitalize" href="{{ route('admin.users.edit', $user->id) }}">Edit</a>
                                                    <a class="btn btn-rounded btn-medium btn-bordered bm-btn-delete text-capitalize" href="{{ route('admin.users.destroy', $user->id) }}"
                                                        onclick="event.preventDefault();deleteCustomer({{$user->id}})">
                                                        Delete
                                                    </a>
                                                    <a class="btn btn-rounded btn-medium btn-primary text-capitalize" href="{{ route('admin.users.login', $user->id) }}">Login As</a>
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

<table id="print_table" class="text-center datatable-dark backend_datatables">
    <thead class="text-capitalize">
        <tr>
            <th width="10%">{{ config('constants.label.admin.customer_no') }}</th>
            <th width="10%">Profile Picture</th>
            <th width="10%">Name</th>
            <th width="10%">Email</th>
            <th width="10%">{{ config('constants.label.admin.ar_division_no') }}</th>
            <th width="10%">{{ config('constants.label.admin.relational_manager') }}</th>
            <th width="10%">Status</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($print_users as $print_user)
        <tr>
            <td> <a class="" href="{{ route('admin.users.edit', $print_user->id) }}">{{ $print_user->customerno }}</a></td>
            <td>
                @if($print_user->profile_image)
                    <img src="/{{$print_user->profile_image}}" id="admin_customers_profile" class="rounded-circle datatable_profile"/>
                @else
                    <img src="/assets/images/svg/user_logo.png" id="admin_customers_profile" class="rounded-circle datatable_profile"/>
                @endif  
            </td>
            <td>{{ $print_user->name }}</td>
            <td>{{ $print_user->email }}</td>
            <td>{{ $print_user->ardivisionno }}</td>
            <td>
                @if($print_user->sales_person != '')
                    {{$print_user->sales_person}} ({{$print_user->person_number}})
                @else
                    -
                @endif
            </td>                                    
            <td>
                <div class="status-btns">
                    @if( $print_user->active == 1)
                        Active <br>
                    @elseif( $print_user->active == 0 && $print_user->is_deleted == 0)
                        New <br>
                    @endif

                    @if($print_user->is_vmi == 1 && $print_user->vmi_companycode != '' )
                        Inventory
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

@section('scripts')
<script>
const searchWords = <?php echo json_encode($searchWords); ?>;
const orderCol = <?php echo json_encode($order); ?>;
const orderType = <?php echo json_encode($order_type); ?>;
$('th span').css({'opacity':0.3})
$(`[data-col='${orderCol}'][data-ordertype='${orderType}']`).css({'opacity':1});
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
