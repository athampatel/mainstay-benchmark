(function($) {
    "use strict";


    $('.show-clients').on('click', function() {
        var _target = $(this).data('target');
        var _action  = 0;
        if($(this).hasClass('active')){
            $(this).removeClass('active');
        }else{
            $(this).addClass('active');
            _action = 1;
        }

        $('.company-row.'+_target).each(function(){
            if(_action == 1){
                $(this).slideDown();
            }else{
                $(this).slideUp();
            }

        });
    });

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

        if (window.innerWidth <= 1364) {
            $(".page-container").addClass("sbar_collapsed");
        }else{
            $(".page-container").removeClass("sbar_collapsed");

        }

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