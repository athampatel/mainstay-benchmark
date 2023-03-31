$(function () {
	"use strict";
	/* perfect scrol bar */
	//new PerfectScrollbar('.header-message-list');
	//new PerfectScrollbar('.header-notifications-list');
	// search bar
	if($(document.body).find('.equal-height').length > 0){
	 	$('.equal-height').matchHeight({byRow: true, property: 'height'}); // my comment
	}


	$(".mobile-search-icon").on("click", function () {
		$(".search-bar").addClass("full-search-bar");
		$(".page-wrapper").addClass("search-overlay");
		$('#full_search_input').focus();
	});
	$(".search-close").on("click", function () {
		$(".search-bar").removeClass("full-search-bar");
		$(".page-wrapper").removeClass("search-overlay");
	});
	$(".mobile-toggle-menu").on("click", function () {
		$(".wrapper").addClass("toggled");
	});
	// toggle menu button
	$(".toggle-icon").click(function () {
		if ($(".wrapper").hasClass("toggled")) {			
			$(".wrapper").removeClass("toggled");
			$(".sidebar-wrapper").unbind("hover");
			$(this).removeClass('active');
		} else {
			$(".wrapper").addClass("toggled");
			$(".sidebar-wrapper").hover(function () {
				$(".wrapper").addClass("sidebar-hovered");
			}, function () {
				$(".wrapper").removeClass("sidebar-hovered");
			});
			$(this).addClass('active');
		}
	});
	/* Back To Top */
	$(document).ready(function () {
		$(window).on("scroll", function () {
			if ($(this).scrollTop() > 300) {
				$('.back-to-top').fadeIn();
			} else {
				$('.back-to-top').fadeOut();
			}
		});
		$('.back-to-top').on("click", function () {
			$("html, body").animate({
				scrollTop: 0
			}, 600);
			return false;
		});
	});
	// === sidebar menu activation js
	$(function () {
		for (var i = window.location, o = $(".metismenu li a").filter(function () {
			return this.href == i;
		}).addClass("").parent().addClass("mm-active");;) {
			if (!o.is("li")) break;
			o = o.parent("").addClass("mm-show").parent("").addClass("mm-active");
		}
	});
	// metismenu
	$(function () {
		$('#menu').metisMenu();
	});
	// chat toggle
	$(".chat-toggle-btn").on("click", function () {
		$(".chat-wrapper").toggleClass("chat-toggled");
	});
	$(".chat-toggle-btn-mobile").on("click", function () {
		$(".chat-wrapper").removeClass("chat-toggled");
	});
	// email toggle
	$(".email-toggle-btn").on("click", function () {
		$(".email-wrapper").toggleClass("email-toggled");
	});
	$(".email-toggle-btn-mobile").on("click", function () {
		$(".email-wrapper").removeClass("email-toggled");
	});
	// compose mail
	$(".compose-mail-btn").on("click", function () {
		$(".compose-mail-popup").show();
	});
	$(".compose-mail-close").on("click", function () {
		$(".compose-mail-popup").hide();
	});
	/*switcher*/
	$(".switcher-btn").on("click", function () {
		$(".switcher-wrapper").toggleClass("switcher-toggled");
	});
	$(".close-switcher").on("click", function () {
		$(".switcher-wrapper").removeClass("switcher-toggled");
	});
	$("#lightmode").on("click", function () {
		$('html').attr('class', 'light-theme');
	});
	$("#darkmode").on("click", function () {
		$('html').attr('class', 'dark-theme');
	});
	$("#semidark").on("click", function () {
		$('html').attr('class', 'semi-dark');
	});
	$("#minimaltheme").on("click", function () {
		$('html').attr('class', 'minimal-theme');
	});
	$("#headercolor1").on("click", function () {
		$("html").addClass("color-header headercolor1");
		$("html").removeClass("headercolor2 headercolor3 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8");
	});
	$("#headercolor2").on("click", function () {
		$("html").addClass("color-header headercolor2");
		$("html").removeClass("headercolor1 headercolor3 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8");
	});
	$("#headercolor3").on("click", function () {
		$("html").addClass("color-header headercolor3");
		$("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8");
	});
	$("#headercolor4").on("click", function () {
		$("html").addClass("color-header headercolor4");
		$("html").removeClass("headercolor1 headercolor2 headercolor3 headercolor5 headercolor6 headercolor7 headercolor8");
	});
	$("#headercolor5").on("click", function () {
		$("html").addClass("color-header headercolor5");
		$("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor3 headercolor6 headercolor7 headercolor8");
	});
	$("#headercolor6").on("click", function () {
		$("html").addClass("color-header headercolor6");
		$("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor3 headercolor7 headercolor8");
	});
	$("#headercolor7").on("click", function () {
		$("html").addClass("color-header headercolor7");
		$("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor6 headercolor3 headercolor8");
	});
	$("#headercolor8").on("click", function () {
		$("html").addClass("color-header headercolor8");
		$("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor6 headercolor7 headercolor3");
	});
	
	
	
   // sidebar colors 


    $('#sidebarcolor1').click(theme1);
    $('#sidebarcolor2').click(theme2);
    $('#sidebarcolor3').click(theme3);
    $('#sidebarcolor4').click(theme4);
    $('#sidebarcolor5').click(theme5);
    $('#sidebarcolor6').click(theme6);
    $('#sidebarcolor7').click(theme7);
    $('#sidebarcolor8').click(theme8);

    function theme1() {
      $('html').attr('class', 'color-sidebar sidebarcolor1');
    }

    function theme2() {
      $('html').attr('class', 'color-sidebar sidebarcolor2');
    }

    function theme3() {
      $('html').attr('class', 'color-sidebar sidebarcolor3');
    }

    function theme4() {
      $('html').attr('class', 'color-sidebar sidebarcolor4');
    }
	
	function theme5() {
      $('html').attr('class', 'color-sidebar sidebarcolor5');
    }
	
	function theme6() {
      $('html').attr('class', 'color-sidebar sidebarcolor6');
    }

    function theme7() {
      $('html').attr('class', 'color-sidebar sidebarcolor7');
    }

    function theme8() {
      $('html').attr('class', 'color-sidebar sidebarcolor8');
    }
});

// login page password show 
$(document).on("click", "#show-password-icon", function(e){
	if($('#inputChoosePassword').get(0).type == 'text'){
		$('#inputChoosePassword').get(0).type = 'password';
		$(this).removeClass('bx-show');
		$(this).addClass('bx-hide');
	} else {
		$(this).addClass('bx-show');
		$(this).removeClass('bx-hide');
		$('#inputChoosePassword').get(0).type = 'text';
	}
});

// Reset password page new password show
$(document).on("click", "#show-new-password-icon", function(e){
	if($('#inputNewPassword').get(0).type == 'text'){
		$('#inputNewPassword').get(0).type = 'password';
		$(this).removeClass('bx-show');
		$(this).addClass('bx-hide');
	} else {
		$(this).addClass('bx-show');
		$(this).removeClass('bx-hide');
		$('#inputNewPassword').get(0).type = 'text';
	}
});

// Reset password page confirm password show
$(document).on("click", "#show-confirm-password-icon", function(e){
	if($('#inputConfirmPassword').get(0).type == 'text'){
		$('#inputConfirmPassword').get(0).type = 'password';
		$(this).removeClass('bx-show');
		$(this).addClass('bx-hide');
	} else {
		$(this).addClass('bx-show');
		$(this).removeClass('bx-hide');
		$('#inputConfirmPassword').get(0).type = 'text';
	}
});

if($(document.body).find('#example2').length > 0){
	var table = $('#example2').DataTable( {
		lengthChange: false,
		pageLength:5,
		paging: false,
		searching: false,
		ordering: false,
		info: false,
		//buttons: [ 'copy', 'excel', 'pdf', 'print']
	});
	table.buttons().container().appendTo( '#example2_wrapper .col-md-6:eq(0)' );
}

// search key function
document.onkeyup = function(e){
	// e.preventDefault();
	if (e.ctrlKey && e.key === 'k') {
		e.preventDefault();
		console.log('___crtl+k');
	  }
}

$(document).on('keyup','#full_search_input',function(e){
    e.preventDefault()
    let searchText = $(e.currentTarget).val();
    let matches = searchWords.filter(search => {
      return search.name.toLowerCase().includes(searchText)
    });
    let modal_body_display = "";
    matches.forEach(match => {
        let disp = `<div>
                        <a href="${match.link}">${match.name}</a>
                    </div>`;
        modal_body_display += disp;
    });
    $('#search_modal_disp_body').html(modal_body_display);
    $("#searchmodal").css("display", "block");
    if(searchText == ''){
        $("#searchmodal").css("display", "none");
    }
    if(matches.length == 0){
        let no_results_found = '<div>No Results found</div>';
        $('#search_modal_disp_body').html(no_results_found);
    }
})

$(window).scroll(function (e) {
    $("#searchmodal").css("display", "none");
})

$(document).on('click','#change_order_Request_nav',function(){
	window.location = '/requests/change_orders';
})

// setTimeout(() => {
// 	$('.home-content').removeClass('welcome_loader');
// }, 2000);