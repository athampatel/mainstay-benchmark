jQuery(document).ready(function(){
    //alert("Notifications");
    var _userId = $('#user_id').val();
    var usertype = $('#user_type').val();
    var formData = {userId:_userId,usertype:usertype};    
    $.ajax({ 
        type: 'POST',
        url: '/get-notifications',
        contentType: 'multipart/form-data',
        cache: false,
        contentType: false,
        processData: false,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: formData,
        success: function (res) {
           // let res1 = JSON.parse(res);
            if(res.type == 1){
                $('.notification_count_section .notification_count').html(res.count);
                $('.notification-area .nofity-list').html(res.html);
                $('.notify-title').html('You have '+res.count+' new notifications');
            }
        }
    }); 
});

