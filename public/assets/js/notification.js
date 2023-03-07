jQuery(document).ready(function(){
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
            if(res.type == 1){
                $('.notification_count_section .notification_count').html(res.count);
                $('.notification-area .nofity-list').html(res.html);
                $('.notify-title').html('You have '+res.count+' new notifications');
            }
        }
    });
    
    $.ajax({ 
        type: 'POST',
        url: '/get-bottom-notifications',
        contentType: 'multipart/form-data',
        cache: false,
        contentType: false,
        processData: false,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (res) {
            res = JSON.parse(res);
            if(res.success){
                $('#bottom_notification_disp').html(res.notification_code);
                $('.navbar_notification_icon .notification_count').removeClass('d-none');
                $('.navbar_notification_icon .notification_count').text(res.notifications_all.length);
                const bottom_nofication_arrow = document.querySelector('.notfication_bottom .notification');
                const notification_bottom = document.querySelector('.notfication_bottom');
                const notification_cancel = document.querySelector('.notification_bottomn_cancel');
                bottom_nofication_arrow.onclick = function(){
                    notification_bottom.classList.toggle('active');
                    notification_cancel.classList.remove('animate_fade_right');
                    // notification_bottom.style.animation = none;
                }
                const bottom_nofication_close = document.querySelector('.messages .header .close');
                bottom_nofication_close.onclick = function(){
                    notification_bottom.classList.remove('active');
                }
                notification_cancel.onclick = function(){
                    notification_bottom.classList.add('d-none');
                    notification_cancel.classList.add('d-none');
                }
            }
        }
    }); 
});

setInterval(() => {
    $.ajax({ 
        type: 'POST',
        url: '/get-new-bottom-notifications',
        contentType: 'multipart/form-data',
        cache: false,
        contentType: false,
        processData: false,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (res) {
            res = JSON.parse(res);
            const notification_bottom = document.querySelector('.notfication_bottom');
            if(res.success){ 
                if(notification_bottom){
                    // let previous_count = parseInt($('#message_count').text());
                    // let count = previous_count + res.new_notifications.length;
                    let count = res.new_notifications.length;
                    $('#message_count').text(count);
                    $('.notification .count').text(count);
                    $('.navbar_notification_icon .notification_count').text(count);
                    $('.notfication_bottom .messages-container').html(res.notification_message_code)
                } else {
                    $('.navbar_notification_icon .notification_count').removeClass('d-none');
                    $('.navbar_notification_icon .notification_count').text(res.all_notifications.length);
                    $('#bottom_notification_disp').html(res.all_notification_code);
                    const bottom_nofication_arrow = document.getElementById('bottom_message_arrow');
                    const notification_bottom = document.querySelector('.notfication_bottom');
                    const notification_cancel = document.querySelector('.notification_bottomn_cancel');
                    bottom_nofication_arrow.onclick = function(){
                        notification_bottom.classList.toggle('active');
                    }
                    
                    const bottom_nofication_close = document.querySelector('.messages .header .close');
                    bottom_nofication_close.onclick = function(){
                        notification_bottom.classList.remove('active');
                    }
                    notification_cancel.onclick = function(){
                        notification_bottom.classList.add('d-none');
                        notification_cancel.classList.add('d-none');
                    }
                }
            } 
        }
    });
},60000);

$(document).on('click','.navbar_notification_icon',function(){
    // $('.notfication_bottom').removeClass('d-none');
    // $('.notification_bottomn_cancel').removeClass('d-none');
    if($('.notfication_bottom').hasClass("d-none")){
        // animation
        $('.notfication_bottom').removeClass('animate_fade_out_right');
        $('.notfication_bottom').removeClass('d-none');
        $('.notification_bottomn_cancel').removeClass('d-none');     
    } else {
        $('.notfication_bottom').addClass('animate_fade_out_right');
        $('.notfication_bottom').removeClass('active');
        setTimeout(()=> {
            $('.notfication_bottom').addClass('d-none');
            $('.notification_bottomn_cancel').addClass('d-none');    
        },800);
    }
})

$(document).on('click','.bottom_notification_msg',function(e){
    e.preventDefault();
    $id = $(e.currentTarget).data('id');
    $link = $(e.currentTarget).data('link');
    $.ajax({ 
        type: 'POST',
        url: '/notification-seen',
        dataType: "JSON",
        data: {'id':$id},
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (res) {
            if(res.success){
                $(e.currentTarget).addClass('d-none');
                window.open($link, '_blank'); 
            }
        }
    });
})

