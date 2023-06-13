
@extends('backend.layouts.master')

@section('title')
Manager Create - Admin Panel
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .form-check-label {
        text-transform: capitalize;
    }
</style>
@endsection


@section('admin-content')

<div class="home-content">
    <span class="page_title">Search manager</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <div class="col-12 mt-4">
                    @include('backend.layouts.partials.messages')
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="form-row align-items-center form-row align-items-center col-12 col-md-8 col-lg-8 mx-auto d-flex align-items-center flex-wrap">
                                <div class="form-groups col-12 col-md-9 col-lg-9">
                                    <label for="name">{{ config('constants.label.admin.search_manager_email') }}</label>
                                    <input type="text" class="form-control" id="search-customer-no" name="customer_search" placeholder="Enter Contact Email" value="" required>
                                </div>
                                <div class="col-12 col-md-3">
                                    <button class="position-relative bm-btn-primary text-capitalize btn btn-rounded px-4 btn-primary col-12 manager-search" id='user-search'>{{ config('constants.label.admin.buttons.customer_search') }}</button>
                                </div>
                            </div>
                        </div>
                    </div> 

                    <div class="alert alert-success d-none text-center" id="customer_response_alert">Manager Details Found</div>
                    <div class="card userDetails-container mt-3" id="manager_list" style="display:none;">
                        <div class="card-body">
                            <div class="userDetails-container">        
                                <h4 class="header-title">Manager New Customer</h4>
                                <div id="backend_managers_table_display"></div>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    const constants = <?php echo json_encode($constants); ?>;
    const searchWords = <?php echo json_encode($searchWords); ?>;
    function ValidateEmail(emailaddress){
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(emailaddress)){
            return true;
        }    
        return false;
    }    
    $(document).on('click','.manager-search',function(){
        $('#backend_managers_create').remove();
        $search_text = $('#search-customer-no').val();
        if($search_text == ''){
            $('#customer_response_alert').addClass('alert-danger').addClass('text-white').addClass('bm-alert-danger').removeClass('alert-success').removeClass('bm-btn-primary').removeClass('d-none').html(constants.validation.admin.search_manager_email);     
            $('.userDetails-container').css({'display':'none'});
            return false;

        }
        $.ajax({
            type: 'POST',
            url: '/admin/get_manager_info',
            dataType: "JSON",
            data: { "_token": "{{ csrf_token() }}",'search_text':$search_text},
            beforeSend:function(){
                $(document.body).append('<div id="preloader" style="opacity:0.5"><div class="loader"></div></div>');
                $('.multiple-container').fadeOut();
                $('.multiple-container').find('.card-body').html('');
                $('.userDetails-container').fadeOut();
            },
            success: function (res) {
                if(!res.success){
                    $('#customer_response_alert').addClass('alert-danger').addClass('text-white').addClass('bm-alert-danger').removeClass('alert-success').removeClass('bm-btn-primary').removeClass('d-none').html(res.error);   
                    return false; 
                } else {
                    $('#customer_response_alert').addClass('d-none');
                    let managers = "";
                    res.salespersons.forEach(manager => {
                        let sales_person_no = manager.salespersonno;
                        let single_manager = `<tr><td>${manager.salespersonno}</td><td>${manager.salespersonname}</td><td>${manager.ardivisionno}</td>
                        <td>
                            <a class="btn btn-rounded text-capitalize btn-primary bm-btn-primary text-white" target="_blank" href="/admin/manager/${sales_person_no}/customers/0">All Customers</a>
                            </td>
                            </tr>`;
                            managers += single_manager
                    });
                    let datatable = `<table id="backend_managers_create" class="text-center datatable-dark dataTable backend_datatables">
                                    <thead class="text-capitalize">
                                        <th> Sales Person Number</th>
                                        <th> Sales Person Name</th>
                                        <th> AR Division Number</th>
                                        <th> Actions</th>
                                    </thead>
                                    <tbody>
                                        ${managers}
                                    </tbody>
                                </table>`;
                    $('#manager_list').css({'display':'block'});
                    $('.userDetails-container').css({'display':'block'});
                    $('#backend_managers_table_display').html(datatable);
                    $('#backend_managers_create').DataTable( {
                            searching: true,
                            lengthChange: true,
                            pageLength:10,
                            paging: true,
                            ordering: false,
                            info: false,
                            responsive: true,
                            autoWidth: false,
                            columns: [
                                { "width": "25%" },
                                { "width": "25%" },
                                { "width": "25%" },
                                { "width": "25%" },
                            ]
                    });
                }

                return false;
            },
            complete:function(){
                    $(document.body).find('#preloader').remove();            }
            });
    }) 
</script>
@endsection