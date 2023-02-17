
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
                <!-- data table start -->
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            {{-- test working start --}}
                            <div class="row">
                                <div class="col-12 col-lg-3 col-md-12">
                                    <p class="float-right mb-2">
                                        <a class="btn btn-primary btn-rounded text-white" href="{{ route('admin.users.create') }}">Create Customer</a>
                                    </p>            
                                </div>
                                <div class="col-12 col-lg-9 col-md-12  d-flex align-items-center justify-content-end flex-wrap col-filter"> 
                                    <div class="position-relative item-search">
                                            {{-- <form id="customer_filter" action="/admin/customers" method="GET"> --}}
                                                <input type="text" class="form-control1 form-control-sm datatable-search-input-admin" placeholder="Search in All Columns" id="admin_customer_search" value="{{!$search ? '' : $search}}" aria-controls="help-page-table">
                                                <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="admin-customer-search-img">
                                            {{-- </form> --}}
                                        </div> 
                                        {{-- {{dd($paginate['per_page'])}} --}}
                                        @php 
                                        $select_options = [10,12,20];
                                        @endphp
                                        <div class="position-relative datatable-filter-div">
                                            {{-- <form action="" method="get"> --}}
                                                <select name="" class="datatable-filter-count" id="admin-customer-filter-count">
                                                    @foreach($select_options as $select_option)
                                                        <option value="{{$select_option}}" {{$select_option == $paginate['per_page'] ? 'selected' :'' }}>{{$select_option}} Items</option>
                                                    @endforeach
                                                </select>
                                                <img src="/assets/images/svg/filter-arrow_icon.svg" alt="" class="position-absolute datatable-filter-img">
                                            {{-- </form> --}}
                                        </div>
                                        <form id="customer_from" action="/admin/customers" method="GET">
                                            {{-- @csrf --}}
                                        </form>
                                    {{-- </form> --}}
                                    <div class="datatable-export">
                                        <div class="datatable-print">
                                            <a href="">
                                                <img src="/assets/images/svg/print-report-icon.svg" alt="" class="position-absolute" id="admin-customer-print-icon">
                                            </a>
                                        </div>
                                        <div class="datatable-report">
                                            <a href="/admin/exportAllCustomers">
                                                <img src="/assets/images/svg/export-report-icon.svg" alt="" class="position-absolute" id="admin-customer-report-icon">
                                            </a>
                                        </div>
                                    </div>
                                   {{-- form submit work start --}}
                                    {{-- <form id="customer-page-form" action="http://localhost:8081/admin/customers" method="GET" style="display:none;">
                                        <input type="hidden" name="_token" value="UVbairuWI4ETpda1GR7s6dIfkx0ynp1C9XRH2vSi">                
                                    </form> --}}
                                   {{-- form submit work end --}}
                                </div>
                            </div>
                            {{-- test working end --}}    
                            <div class="clearfix"></div>
                            <div class="data-tables table-responsive">
                                @include('backend.layouts.partials.messages')
                                {{-- <div class="table_disp"></div> --}}
                                <table id="backend_customers" class="text-center datatable-dark">
                                    <thead class="text-capitalize">
                                        <tr>
                                            <th width="10%">Customer No</th>
                                            <th width="10%">Name</th>
                                            <th width="10%">Email</th>
                                            <th width="10%">AR Division no</th>
                                            <th width="10%">Benchmark Regional Manager</th>
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
                                                        <span class="btn btn-success btn-rounded text-white" style="padding:5px;pointer-events:none;">Active</span>           
                                                    @elseif( $user->active == 0 && $user->is_deleted == 0)
                                                        <a href="{{env('APP_URL')}}/admin/user/{{$user->id}}/change-status/{{$user->activation_token}}" target="_blank" class="btn btn-rounded btn-light text-dark" style="padding:5px;">New</a>
                                                    @endif

                                                    @if($user->is_vmi == 1)
                                                        <a data-customer="{{$user->id}}" class="btn btn-rounded btn-info text-white"  href="{{ route('admin.users.inventory',$user->id) }}" title="Add / Update Inventory">Inventory</a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>

                                                <div class="btn-wrapper btns-2">
                                                    <a class="btn btn-rounded btn-medium btn-primary" href="{{ route('admin.users.edit', $user->id) }}">Edit</a>
                                                
                                                    <a class="btn btn-rounded btn-medium btn-bordered" href="{{ route('admin.users.destroy', $user->id) }}"
                                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{ $user->id }}').submit();">
                                                        Delete
                                                    </a>

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
                            {{-- <div class="pagination_disp"></div> --}}
                            <div class="mt-3">
                                <x-pagination-component :pagination="$paginate" :search="$search" />
                            </div>    
                        </div>
                    </div>
                </div>
                <!-- data table end -->
            </div>
        </div>
    </div>    
</div>
@endsection
