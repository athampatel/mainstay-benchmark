let sidebar = document.querySelector(".sidebar");
let sidebarBtn = document.querySelector(".sidebarBtn");
sidebarBtn.onclick = function() {
	sidebar.classList.toggle("active");
	if(sidebar.classList.contains("active")){
		sidebarBtn.classList.replace("bx-menu" ,"bx-menu-alt-right");
	} else
		sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
}

$(document).on("click", "#logout", function(e){
    // e.preventDefault()
    $.ajax({
        type: 'POST',
        url: '/logout',
        dataType: "JSON",
        data: { "_token": $('meta[name="csrf-token"]').attr('content')},
        success: function (res) {
            window.location.reload();
            // window.location = 'http://www.google.com';
        }
    });
});