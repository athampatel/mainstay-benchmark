
@extends('backend.layouts.master')

@section('title')
Role Page - Admin Panel
@endsection

@section('admin-content')
<div class="home-content">
    <span class="page_title">Roles</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title float-left">Roles List</h4>
                            <div class="row">
                                <div class="col-12 col-lg-3 col-md-12">
                                    <p class="float-right mb-2">
                                    @if (Auth::guard('admin')->user()->can('role.create'))
                                        <a class="btn btn-primary btn-rounded text-capitalize text-white" href="{{ route('admin.roles.create') }}">{{ config('constants.label.admin.buttons.create_new_role') }}</a>
                                    @endif
                                    </p>         
                                </div>
                                <div class="col-12 col-lg-9 col-md-12 d-flex align-items-center justify-content-end flex-wrap col-filter"> 
                                    <div class="position-relative item-search">
                                        <input type="text" class="form-control1 form-control-sm datatable-search-input-admin" placeholder="Search in All Columns" id="admin_roles_search" value="{{!$search ? '' : $search}}" aria-controls="help-page-table">
                                        <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="admin-roles-search-img">
                                    </div> 
                                    @php 
                                    $select_options = [10,12,20];
                                    @endphp
                                    <div class="position-relative datatable-filter-div">
                                        <select name="" class="datatable-filter-count" id="admin-roles-filter-count">
                                            @foreach($select_options as $select_option)
                                                <option value="{{$select_option}}" {{$select_option == $paginate['per_page'] ? 'selected' :'' }}>{{$select_option}} Items</option>
                                            @endforeach
                                        </select>
                                        <img src="/assets/images/svg/filter-arrow_icon.svg" alt="" class="position-absolute datatable-filter-img">
                                    </div>
                                    <form id="roles_from" action="/admin/roles" method="GET"></form>
                                    <div class="datatable-export">
                                        <div class="datatable-print admin">
                                            <a href="">
                                                <img src="/assets/images/svg/print-report-icon.svg" alt="" class="position-absolute" id="admin-roles-print-icon">
                                            </a>
                                        </div>
                                        <div class="datatable-report admin position-relative">
                                            <a href="">
                                                <img src="/assets/images/svg/export-report-icon.svg" alt="" class="position-absolute" id="admin-roles-report-icon">
                                            </a>
                                            <div class="dropdown-menu export-drop-down-table d-none" aria-labelledby="export-admin-customers" id="export-admin-roles-drop">
                                                <a href="/admin/exportAllUserRolesInExcel" class="dropdown-item export-admin-roles-item" data-type="csv">Export to Excel</a>
                                                <a href='/admin/exportAllUserRolesInpdf' class="dropdown-item export-admin-roles-item" data-type="pdf">Export to PDF</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="data-tables table-responsive">
                                @include('backend.layouts.partials.messages')
                                <table id="backend_roles" class="text-center datatable-dark backend_datatables">
                                    <thead class="text-capitalize">
                                        <tr>
                                            <th width="5%">{{ config('constants.label.admin.sl') }}</th>
                                            <th width="10%">Name</th>
                                            <th width="60%">Permissions</th>
                                            <th width="15%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td>{{ $loop->index+1 }}</td>
                                            <td>{{ $role->name }}</td>
                                            <td>
                                                <div class="d-flex flex-wrap justify-content-center">    
                                                @foreach ($role->permissions as $perm)
                                                    <span class="badge badge-info mr-1">
                                                        {{ $perm->name }}
                                                    </span>
                                                @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-wrapper btns-2">
                                                @if (Auth::guard('admin')->user()->can('admin.edit'))
                                                    <a class="btn btn-rounded btn-medium btn-primary" href="{{ route('admin.roles.edit', $role->id) }}">Edit</a>
                                                @endif

                                                @if (Auth::guard('admin')->user()->can('admin.edit'))
                                                    <a class="btn btn-rounded btn-medium btn-bordered bm-btn-delete" href="{{ route('admin.roles.destroy', $role->id) }}"
                                                    onclick="event.preventDefault();deleteRole({{$role->id}})">
                                                        Delete
                                                    </a>
                                                    <form id="delete-form-{{ $role->id }}" action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display: none;">
                                                        @method('DELETE')
                                                        @csrf
                                                    </form>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @if($paginate['last_page'] > 1)
                                    <x-pagination-component :pagination="$paginate" :search="$search" />
                                @endif
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
<script>
const searchWords = <?php echo json_encode($searchWords); ?>;
function deleteRole(id){
    Swal.fire({
    title: 'Are you sure?',
    text: "You want to delete the Role",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#A2D45E',
    cancelButtonColor: '#CE202F',
    confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    })
}
</script>
@endsection