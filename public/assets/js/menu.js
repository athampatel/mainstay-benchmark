let sidebar = document.querySelector(".sidebar");
let sidebarBtn = document.querySelector(".sidebarBtn");
/*sidebarBtn.onclick = function() {
	sidebar.classList.toggle("active");
	if(sidebar.classList.contains("active")){
		sidebarBtn.classList.replace("bx-menu" ,"bx-menu-alt-right");
	} else
		sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
}*/

$(document).on("click", "#logout", function(e){
    // e.preventDefault()
    // console.log('__clicked');
    // $.ajax({
    //     type: 'POST',
    //     url: '/logout',
    //     dataType: "JSON",
    //     data: { "_token": $('meta[name="csrf-token"]').attr('content')},
    //     success: function (res) {
    //         window.location.reload();
    //         // window.location = 'http://www.google.com';
    //     }
    // });
    // $('#logout-link').click(function(e) {
        e.preventDefault();
        $('<form action="/logout" method="post">' +
          '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
          '</form>').submit();
          console.log('__submitted');
    //   });
});