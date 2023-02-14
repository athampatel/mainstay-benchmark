@extends('backend.layouts.master')

@section('title')
Customer Change Order Requests - Admin Panel
@endsection

@section('admin-content')

<div class="home-content">
    <span class="page_title">Change Order Request</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <!-- data table start -->
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">                                    
                            <div class="clearfix"></div>
                            <div class="data-tables">
                                @include('backend.layouts.partials.messages')
                                <table id="dataTable" class="text-center datatable-dark">
                                    <thead class="text-capitalize">
                                        <tr>
                                            <th width="10%">Customer No</th>
                                            <th width="10%">Customer Name</th>
                                            <th width="10%">Customer Email</th>                                   
                                            <th width="10%">Order Date</th>
                                            <th width="10%">Region Manager</th>
                                            <th width="10%">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($change_request as $_request)
                                    <tr>
                                            <td> <a class="" href="">{{ $_request->customerno }}</a></td>
                                            <td>{{ $_request->name }}</td>
                                            <td>{{ $_request->email }}</td>                                                                
                                            <td>{{ $_request->ordered_date}}</td>
                                            <td>{{ $_request->manager}}</td>
                                            <td>

                                                    @if($_request['request_status'] == 2)
                                                        <span class="badge badge-danger text-white ">Declined</span>
                                                    @elseif($_request['request_status'] == 1)
                                                        <span class="badge badge-success text-white">Approved</span>  
                                                    @else                                             
                                                        <span class="badge badge-warning text-white">New</span>
                                                    @endif
                                                <a class="btn btn-info text-white" href="{{ route('admin.users.change-order-view', $_request->id) }}">View       Info</a>
                                                <!--<a class="btn btn-danger text-white" href="{{ route('admin.users.destroy', $user->id) }}"
                                                onclick="event.preventDefault(); document.getElementById('delete-form-{{ $user->id }}').submit();">Delete
                                                </a> -->
                                            </td>                                    
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
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