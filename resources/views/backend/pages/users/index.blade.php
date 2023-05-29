
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
                                        <input type="text" class="form-control1 form-control-sm datatable-search-input-admin" placeholder="Search All Columns" id="admin_customer_search" value="{{!$search ? '' : $search}}" aria-controls="help-page-table">
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
                           
                            @if(empty($users))
                            <p>&nbsp;</p>
                            <div class="alert alert-info bm-alert-info">
                                
                                    <div>
                                        <p class="text-center">No customer contact has been added yet.</p>
                                    </div>
                                </div>

                            @else
                            {{-- users table work start --}}
                            <div class="data-tables table-responsive">
                                @include('backend.layouts.partials.messages')
                                <table id="backend_customers" class="text-center datatable-dark dataTable backend_datatables">
                                    <thead class="text-capitalize">
                                        <tr>
                                            <th  style="text-align:left;padding:20px 10px;font-size:0.9rem">{{ config('constants.label.admin.customer_no') }}</th>
                                            <th colspan="7" style="text-align:left;padding:20px 10px;font-size:0.9rem">
                                                Company Name
                                            </th>                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                @foreach($users as $user)
                                    @php
                                        $user_details = $user['user_details'];
                                    @endphp
                                    <tr>
                                        <th style="text-align:left;padding:20px 10px;font-size:0.75rem">
                                            <a href="javascript:void(0)" data-target="row-{{$user['customerno']}}" class="show-clients" title="View Contacts">
                                                <span class="arrow"></span>
                                                <span class="customerno">{{$user['customerno']}}</span>
                                        </a>
                                        </th>
                                        <th colspan="7 text-left" style="text-align:left;padding:20px 10px;font-size:0.75rem">{{$user_details[0]['customername']}}<span style="color:#A2D45E"></span></th>
                                    </tr>

                                    <tr class="company-row row-header animate row-{{$user['customerno']}}" style="display:none">
                                        {{-- <th>{{ config('constants.label.admin.customer_no') }} </th>             --}}                                                 
                                        <th width="10%">
                                            Profile picture
                                        </th>
                                        <th width="10%">
                                        {{ config('constants.label.admin.customer_name') }}
                                           {{-- <span data-col='username' data-table='admins' data-ordertype='asc' class="asc">&#x2191;</span>
                                            <span data-col='username' data-table='admins' data-ordertype='desc' class="desc">&#x2193;</span> --}}
                                        </th>
                                        {{-- <th width="10%"> --}}
                                            {{-- {{ config('constants.label.admin.contact_name') }}                                             --}}
                                            {{--<span data-col='username' data-table='admins' data-ordertype='asc' class="asc">&#x2191;</span>
                                            <span data-col='username' data-table='admins' data-ordertype='desc' class="desc">&#x2193;</span>--}}
                                        {{-- </th> --}}
                                        <th>Contact Code </th>   
                                        <th width="10%">
                                            {{ config('constants.label.admin.contact_email') }}
                                           
                                            {{--<span data-col='username' data-table='admins' data-ordertype='asc' class="asc">&#x2191;</span>
                                            <span data-col='username' data-table='admins' data-ordertype='desc' class="desc">&#x2193;</span>--}}
                                        </th>
                                       <!-- <th width="10%">
                                            {{ config('constants.label.admin.ar_division_no') }}
                                            {{-- <span data-col='name' data-table='admins' data-ordertype='asc' class="asc">&#x2191;</span>
                                            <span data-col='name' data-table='admins' data-ordertype='desc' class="desc">&#x2193;</span>--}}
                                        </th> --->
                                        <th width="10%">
                                            {{ config('constants.label.admin.relational_manager') }}
                                            {{--<span data-col='email' data-table='admins' data-ordertype='asc' class="asc">&#x2191;</span>
                                            <span data-col='email' data-table='admins' data-ordertype='desc' class="desc">&#x2193;</span>--}}
                                        </th>
                                        <th width="25%">Status</th>
                                        <th width="15%">Action</th>
                                    </tr>

                                    @foreach($user_details as $usr)
                                    @php
                                        $account = $usr['user'];
                                        $sales_person_account = isset($usr['user_sales_person']) ? $usr['user_sales_person']['sales_person'] : [];
                                    @endphp
                                    <tr class="company-row animate row-{{$user['customerno']}}" style="display:none">
                                        {{-- <td> <a href="{{ route('admin.users.edit', $user['id']) }}">{{ $usr['customerno'] }}</a></td> --}}
                                        
                                            <td>
                                                @if($usr['user']['profile_image'] != '')
                                                    <img src="/{{$account['profile_image']}}" height="45" width="45" id="admin_customers_profile" class="rounded-circle datatable_profile"/>
                                                @else
                                                    <img src="/assets/images/svg/user_logo.png" height="45" width="45" id="admin_customers_profile" class="rounded-circle datatable_profile"/>
                                                @endif  
                                            </td>
                                            {{-- <td>&nbsp;</td> --}}
                                            {{-- <td>{{ $account['name'] }}</td> --}}
                                            <td>{{ $usr['customername'] }}</td>
                                            <td> <a href="{{ route('admin.users.edit', $user['id']) }}">{{ $usr['contactcode'] }}</a></td>
                                            <td>{{ $account['email'] }}</td>
                                            <!--<td>{{ $usr['ardivisionno'] }}</td> -->
                                            <td>
                                                {{ !empty($sales_person_account) ? $sales_person_account['name'] : ''}}
                                            </td>  
                                            <td>
                                                <div class="status-btns">
                                                    @if( $account['active'] == 1)
                                                        <span class="btn bm-btn-light-blue btn-rounded text-white text-capitalize" style="padding:5px;pointer-events:none;">Active</span>           
                                                    @elseif( $account['active'] == 0 && $account['is_deleted'] == 0)
                                                        <a href="{{config('app.url')}}admin/user/{{$account['id']}}/change-status/{{$account['activation_token']}}" target="_blank" class="btn btn-rounded btn-light text-dark bm-btn-secondary text-capitalize" style="padding:5px;">New</a>
                                                    @endif

                                                    @if($account['is_vmi'] == 1 && $usr['vmi_companycode'] != '' )
                                                        <a data-customer="{{$user['id']}}" class="btn btn-rounded btn-info text-capitalize text-white bm-btn-info"  href="{{ route('admin.users.inventory',$account['id']) }}" title="Add / Update Inventory">Inventory</a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                            <div class="btn-wrapper btns-2 no-wrap">
                                                    <a class="btn btn-rounded btn-medium btn-primary text-capitalize d-block" href="{{ route('admin.users.edit', $account['id']) }}">Edit</a>
                                                    <a class="btn btn-rounded btn-medium btn-bordered bm-btn-delete text-capitalize" href="{{ route('admin.users.destroy', $account['id'])}}"
                                                        onclick="event.preventDefault();deleteCustomer({{$account['id']}})">
                                                        Delete
                                                    </a>
                                                    <a class="btn btn-rounded btn-medium btn-primary text-capitalize d-block" target="_blank" href="{{ route('admin.users.login', ['id' => $account['id'],'user_detail_id' =>$usr['id']]) }}">Login As</a>
                                                    <form id="delete-form-{{ $account['id'] }}" action="{{ route('admin.users.destroy', $account['id']) }}" method="POST" style="display: none;">
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>
                                                </div>
                                               
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                    </tbody>
                                </table>
                            </div> 
                            @endif  
                            {{-- new design work end --}}
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

{{-- <table id="print_table" class="text-center datatable-dark backend_datatables">
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
        @foreach ($print_user['users'] as $printusr)    
            <tr>
                <td> <a class="" href="{{ route('admin.users.edit', $print_user['id']) }}">{{ $printusr['customerno'] }}</a></td>
                <td>
                    @if($print_user['profile_image'])
                        <img src="/{{$print_user['profile_image']}}" height="45" width="45" id="admin_customers_profile" class="rounded-circle datatable_profile"/>
                    @else
                        <img src="/assets/images/svg/user_logo.png" height="45" width="45" id="admin_customers_profile" class="rounded-circle datatable_profile"/>
                    @endif  
                </td>
                <td>{{ $print_user['name'] }}</td>
                <td>{{ $print_user['email'] }}</td>
                <td>{{ $printusr['ardivisionno'] }}</td>
                <td>
                    @if($printusr['sales_person'] != '')
                        {{$printusr['sales_person']}} ({{$printusr['person_number']}})
                    @else
                        -
                    @endif
                </td>                                    
                <td>
                    <div class="status-btns">
                        @if( $print_user['active'] == 1)
                            Active <br>
                        @elseif( $print_user['active'] == 0 && $print_user['is_deleted'] == 0)
                            New <br>
                        @endif

                        @if($print_user['is_vmi'] == 1 && $printusr['vmi_companycode'] != '' )
                            Inventory
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table> --}}
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
        // document.quer(`delete-form-${id}`).submit();
        console.log(id,'___delete from id');
        document.querySelector(`#delete-form-${id}`).submit();
        }
    })
}

$(document).on('click','.do_customer',function(e) {
    e.preventDefault();
    $(e.currentTarget).closest('.dynamic-values').find('.user_information').slideToggle("fast");
    $(e.currentTarget).toggleClass('active1');
})
$(document).ready(function(){
    $('.backend_customers').DataTable({
            searching: true,
            lengthChange: true,
            paging: true,
            ordering: false,
            info: false,
            responsive: true,
            autoWidth: false,
            columns: [
                {
                    className: 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: '',
                },
                { "width": "8%" },
                { "width": "12%" },
                { "width": "12%" },
                { "width": "12%" },
                { "width": "12%" },
                { "width": "12%" },
                // { "width": "12%" },
                { "width": "16%",}
            ]
    //         createdRow: function(row, data, dataIndex) {
    //   $(row).addClass('clickable-row');
    // }
    });

    $('.dynamic-values').find('.user_information').slideUp("fast");

})
</script>
@endsection