
@extends('backend.layouts.master')
@section('title')
VMI Inventory - Admin Panel
@endsection

@section('admin-content')

<div class="backdrop d-none">
    <div class="loader"></div>
</div>

<div class="home-content">
    <span class="page_title">Inventory Item Lists</span>
    <div class="padding-y-40 open-orders">
        <div class="col-12 mt-2">
            <div class="card">
                <div class="card-body d-flex justify-content-between">
                    <div class="">
                        <h4 class="header-title float-left mb-0">Actions</h4>                   
                    </div>
                    <div class="">
                        <a href="javascript:void(0)" id="vmi_inventory_edit" class="btn bm-btn-primary btn-rounded">Edit</a>
                        <a href="javascript:void(0)" id="vmi_inventory_save" class="btn bm-btn-primary d-none btn-rounded">Save</a>
                        <a href="javascript:void(0)" id="vmi_inventory_cancel" class="btn bm-btn-red d-none btn-rounded text-white">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success text-center d-none" id="vmi_inventory_message"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card box min-height-75 mt-0">
                    <div class="card-header col-12 p-3 d-flex border-0">
                        <div class="col-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-end flex-wrap col-filter">            
                            <div class="position-relative item-search">
                                <input type="text" class="form-control1 min-height-0 form-control-sm datatable-search-input-admin" placeholder="Search in All Columns" id="vmi-inventory-search" aria-controls="">
                                <img src="/assets/images/svg/grid-search.svg" alt="" class="position-absolute datatable-search-img" id="vmi-inventory-search-img">
                            </div> 
                            <div class="position-relative datatable-filter-div">
                                <select name="" class="datatable-filter-count" id="admin-vmi-filter-count">
                                    <option value="12" selected>12 Items</option>
                                    <option value="15">15 Items</option>
                                    <option value="20">20 Items</option>
                                    <option value="50">50 Items</option>
                                    <option value="100">100 Items</option>
                                </select>
                                <img src="/assets/images/svg/filter-arrow_icon.svg" alt="" class="position-absolute datatable-filter-img">
                            </div>
                            <div class="vmi-datatable-export">
                                <div class="vmi-datatable-print">
                                    <span>Print</span>
                                    <a href="">
                                        <img src="/assets/images/svg/print-report-icon.svg" alt="" class="position-absolute" id="vmi-print-icon">
                                    </a>
                                </div>
                                <a href="/admin/ExportVmiInventory?user_detail_id={{$user_detail_id}}&company_code={{$company_code}}" class="vmi-inventory-report" id=""> 
                                    <span>Download</span>
                                    <div>
                                        <img src="/assets/images/svg/cloud_download.svg" alt="" class="position-absolute" id="vmi_inventory_report_icon">
                                    </div>
                                </a>
                            </div>
                        </div>
                        <input type="hidden" name="company_code" id="vmi_company_code" value="{{$company_code}}">
                        <input type="hidden" name="user_detail_id" id="user_detail_id" value="{{$user_detail_id}}">
                    </div>
                    <div class="table_loader d-none">
                        <div class="chart-loader1"></div>
                    </div>
                    <div class="card-body col-12 padding-y-0">
                        <div class="page-table-loader-div d-none">
                            <div class="chart-loader1"></div>
                        </div>
                        <div class="table-responsive" id="vmi_inventory_table_disp"></div>
                        <div class="col-12 pb-2">
                            <div id="pagination_disp"></div>
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
    const constants = <?php echo json_encode($constants); ?>;
    var change_items = [];
</script>
<script src="{{ asset('assets/js/backend_vmi.js') }}"></script>
@endsection



