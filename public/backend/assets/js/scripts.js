(function($) {
    "use strict";


    if ($('#dataTable').length) {
        $('#dataTable').DataTable({
            /*processing: true,
            serverSide: true, */
           // responsive: true
        }); //{ }
    }

    $(document.body).on('click','a.random-password',function(e){
        e.preventDefault();
        var pass = '';
        var str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' +
                'abcdefghijklmnopqrstuvwxyz0123456789@#$';            
        for (let i = 1; i <= 12; i++) {
            var char = Math.floor(Math.random() * str.length + 1);
            pass += str.charAt(char)
        }
        console.log(pass);
        $(document.body).find('input.password-field').val(pass).attr('type','text');        
       // return pass;
    });  
    /*================================
    Preloader
    ==================================*/

    var preloader = $("#preloader");
    $(window).on("load", function() {
        setTimeout(function() {
            preloader.fadeOut("slow", function() {
                $(this).remove();
            });
        }, 300);
    });

    /*================================
    sidebar collapsing
    ==================================*/
    if (window.innerWidth <= 1364) {
        $(".page-container").addClass("sbar_collapsed");
    }
    $(".nav-btn").on("click", function() {
        $(".page-container").toggleClass("sbar_collapsed");
    });

    /*================================
    Start Footer resizer
    ==================================*/
    var e = function() {
        var e =
            (window.innerHeight > 0 ? window.innerHeight : this.screen.height) -
            5;
        (e -= 67) < 1 && (e = 1),
            e > 67 && $(".main-content").css("min-height", e + "px");
    };
    $(window).ready(e), $(window).on("resize", e);

    /*================================
    sidebar menu
    ==================================*/
    $("#menu").metisMenu();

    /*================================
    slimscroll activation
    ==================================*/
    $(".menu-inner").slimScroll({
        height: "auto"
    });
    $(".nofity-list").slimScroll({
        height: "435px"
    });
    $(".timeline-area").slimScroll({
        height: "500px"
    });
    $(".recent-activity").slimScroll({
        height: "calc(100vh - 114px)"
    });
    $(".settings-list").slimScroll({
        height: "calc(100vh - 158px)"
    });

    /*================================
    stickey Header
    ==================================*/
    $(window).on("scroll", function() {
        var scroll = $(window).scrollTop(),
            mainHeader = $("#sticky-header"),
            mainHeaderHeight = mainHeader.innerHeight();

        // console.log(mainHeader.innerHeight());
        if (scroll > 1) {
            $("#sticky-header").addClass("sticky-menu");
        } else {
            $("#sticky-header").removeClass("sticky-menu");
        }
    });

    /*================================
    form bootstrap validation
    ==================================*/
    $('[data-toggle="popover"]').popover();

    /*------------- Start form Validation -------------*/
    window.addEventListener(
        "load",
        function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName("needs-validation");
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener(
                    "submit",
                    function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add("was-validated");
                    },
                    false
                );
            });
        },
        false
    );

    /*================================
    Slicknav mobile menu
    ==================================*/
    $("ul#nav_menu").slicknav({
        prependTo: "#mobile_menu"
    });

    /*================================
    login form
    ==================================*/
    $(".form-gp input").on("focus", function() {
        $(this)
            .parent(".form-gp")
            .addClass("focused");
    });
    $(".form-gp input").on("focusout", function() {
        if ($(this).val().length === 0) {
            $(this)
                .parent(".form-gp")
                .removeClass("focused");
        }
    });

    /*================================
    slider-area background setting
    ==================================*/
    $(".settings-btn, .offset-close").on("click", function() {
        $(".offset-area").toggleClass("show_hide");
        $(".settings-btn").toggleClass("active");
    });

    /*================================
    Owl Carousel
    ==================================*/
    function slider_area() {
        var owl = $(".testimonial-carousel").owlCarousel({
            margin: 50,
            loop: true,
            autoplay: false,
            nav: false,
            dots: true,
            responsive: {
                0: {
                    items: 1
                },
                450: {
                    items: 1
                },
                768: {
                    items: 2
                },
                1000: {
                    items: 2
                },
                1360: {
                    items: 1
                },
                1600: {
                    items: 2
                }
            }
        });
    }
    slider_area();

    /*================================
    Fullscreen Page
    ==================================*/

    if ($("#full-view").length) {
        var requestFullscreen = function(ele) {
            if (ele.requestFullscreen) {
                ele.requestFullscreen();
            } else if (ele.webkitRequestFullscreen) {
                ele.webkitRequestFullscreen();
            } else if (ele.mozRequestFullScreen) {
                ele.mozRequestFullScreen();
            } else if (ele.msRequestFullscreen) {
                ele.msRequestFullscreen();
            } else {
                console.log("Fullscreen API is not supported.");
            }
        };

        var exitFullscreen = function() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            } else {
                console.log("Fullscreen API is not supported.");
            }
        };

        var fsDocButton = document.getElementById("full-view");
        var fsExitDocButton = document.getElementById("full-view-exit");

        fsDocButton.addEventListener("click", function(e) {
            e.preventDefault();
            requestFullscreen(document.documentElement);
            $("body").addClass("expanded");
        });

        fsExitDocButton.addEventListener("click", function(e) {
            e.preventDefault();
            exitFullscreen();
            $("body").removeClass("expanded");
        });
    }


/* CUSOMT ADMIN SCRIPT */    
    $(document.body).on('click','.nav-link.dropdown-toggle',function(e){
        $(this).parent().find('.dropdown-menu').toggleClass('show');
    });

    $('form.form-create-customers').submit(function(e){        
        var duplicate  = [];        
        var error_Email,error_no = 0;

        var CheckInp = $('input.create_customerCheck').length;
        if(CheckInp == 0)
            return true;

        var checkedItem = $('input.create_customerCheck:checked').length;
        $('#user_activate_message').addClass('d-none');
        if(checkedItem == 0){
            $('#user_activate_message').removeClass('d-none').removeClass('alert-success').addClass('alert-danger').html('Please select a checkbox to add a customer');
            $('html, body').animate({
                scrollTop: $("#user_activate_message").offset().top
            }, 1000);
            return false;
        }

        $(this).find('.card-body').each(function(){
            if($(this).find('input.create_customerCheck:checked') == true){
                $(this).find('.emailaddress').each(function(){
                    var _val = $(this).val();
                    _val = _val.toLowerCase();
                    if(duplicate.indexOf(_val) == -1){               
                        duplicate.push(_val);
                    }else{    
                        error_Email = 1;
                        $('.emailaddress').addClass('error-field');
                    }    
                });
                $(this).find('.customerno').each(function(){
                    var _val = $(this).val();
                    _val = _val.toLowerCase();
                    if(duplicate.indexOf(_val) == -1){
                        duplicate.push(_val);                        
                    }else{
                        error_no = 1;    
                        $('.customerno').addClass('error-field');
                    }
                });
            }
        });

        var message = '';
        if(error_Email &&  error_no){
            message = 'Email address and CustomerNo is not unique';
        }else if(error_Email){
            message = 'Duplicate email address';
        }else if(error_no){
            message = 'Duplicate Customer No';
        }
        if(message != ''){
            $('#user_activate_message').removeClass('d-none').removeClass('alert-success').addClass('alert-danger').html(message);
            $('html, body').animate({
                scrollTop: $("#user_activate_message").offset().top
            }, 1000);
            return false;
        }
    });

})(jQuery);


 function ValidateEmail(emailaddress){
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(emailaddress)){
            return true;
        }    
        return false;
    }
    $(document).ready(function() {
        $('.select2').select2();
        var customer_email = $('#search-customer-no').val();
        if(customer_email != ''){
            $('#user-search').trigger('click');
        }    
        $('.userDetails-container .form-control').blur(function(){
            var val = $(this).val();
            
            if($(this).attr('type') == 'email' && !ValidateEmail(val) == ''){
                $(this).addClass('error-field');
            }else if(val.trim() == '' && $(this).hasClass('required')){
                $(this).addClass('error-field');
            }else{
                $(this).removeClass('error-field');
            }
        });
        $(document.body).on('click','a.do_customer',function(e){
            $('a.do_customer').each(function(){
                $(this).removeClass('active');
            });
            var parseData = $(this).data('json');
          
            $(this).addClass('active');
            $('.userDetails-container').fadeIn();
            rendorUserForm(parseData,0);

        });
        $(document.body).on('submit','#create-customer',function(e){
            //e.preventDefault();
            $('.userDetails-container .form-control').each(function(){
                    if($(this).hasClass('required')){
                        $(this).prop('required',true);
                        var val = $(this).val();
                        if(val.trim() == ''){
                            $(this).addClass('error-field');
                        }else{
                            $(this).removeClass('error-field');
                        }
                    }     
                });
                if($('.userDetails-container').find('.error-field').length > 0){
                    return false;        
                }           
        });

    })

    $(document).on('click','#user-search',function(){
        $search_text = $('#search-customer-no').val();
        $.ajax({
            type: 'POST',
            url: '/admin/get_customer_info',
            dataType: "JSON",
            data: { "_token": "{{ csrf_token() }}",'search_text':$search_text},
            beforeSend:function(){
                $(document.body).append('<div id="preloader" style="opacity:0.5"><div class="loader"></div></div>');
            },
            success: function (res) {
                var total_records = parseInt(res.customers.length);
                if(total_records > 1){
                    $('#customer_response_alert').addClass('alert-danger').removeClass('d-none').html('Multiple records found');     
                    var customers = res.customers;  
                    var _html = '';
                    $.each(customers,function(index,value){
                        _html +='<div class="form-row duplicate-date">'+
                                    '<div class="form-group col-md-12 col-sm-12">'+
                                        '<a href="javascript:void(0)" data-json=\''+JSON.stringify(value)+'\' class="do_customer"><strong>Customer No:</strong>'+value.emailaddress+' ('+value.customerno+')'+
                                        
                                    '</a></div>'+                              
                                '</div>';
                    });
                    $('.multiple-container').fadeIn();
                    $('.multiple-container').find('.card-body').html(_html);
                    return false; 
                }else if(res.customers.length > 0){
                    $customer = res.customers[0];
                    rendorUserForm($customer,1);
                } else {
                    $('#customer_response_alert').removeClass('d-none');
                    $('#customer_response_alert').removeClass('alert-success');
                    $('#customer_response_alert').addClass('alert-danger');
                    setTimeout(() => {
                        $('#customer_response_alert').addClass('d-none');
                    },2000);
                }
                $('.userDetails-container').fadeIn();
            },
            complete:function(){
                $(document.body).find('#preloader').remove();            }
        });
    })
function rendorUserForm($customer,show){
    console.log($customer,'__customer');
    if("vmi_companycode" in $customer){
        $('#is_vmi').val(1);
    }
    $('#user_no').val($customer.customerno);
    $('#user_email').val($customer.emailaddress);
    $('#user_name').val($customer.customername);
    $('#ardivision_no').val($customer.ardivisionno);
    $('#address_line_1').val($customer.addressline1);
    $('#address_line_2').val($customer.addressline2);
    $('#address_line_3').val($customer.addressline3);
    $('#user_city').val($customer.city);
    $('#user_state').val($customer.state);
    $('#user_zipcode').val($customer.zipcode);
    $('#sales_person_divison_no').val($customer.salespersondivisionno);
    $('#sales_person_no').val($customer.salespersonno);
    $('#sales_person_name').val($customer.salespersonname);
    $('#sales_person_email').val($customer.salespersonemail);
    if(show == 1)
        $('#customer_response_alert').removeClass('d-none');
        
    setTimeout(() => {
        $('#customer_response_alert').addClass('d-none');
        $('.userDetails-container .form-control').each(function(){
            if($(this).hasClass('required')){
                $(this).prop('required',true);
                var val = $(this).val();
                if(val.trim() == ''){
                    $(this).addClass('error-field');
                }else{
                    $(this).removeClass('error-field');
                }
            }     
        });
        if($('.userDetails-container').find('.error-field').length > 0){

        }
    },2000);
    return true;
}    
