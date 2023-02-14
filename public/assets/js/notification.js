jQuery(document).ready(function(){
    //alert("Notifications");
    var _userId = $('#user_id').val();
    var formData = {userId:_userId};
   /* $.ajax({
        type: 'POST',
        url: '/get-notifications',
        contentType: 'multipart/form-data',
        cache: false,
        contentType: false,
        processData: false,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: formData,
        success: function (res) {
            let res1 = JSON.parse(res);
        }
    }); */
});

