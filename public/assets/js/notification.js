jQuery(document).ready(function(){
    $.ajax({ 
        type: 'POST',
        url: '/get-bottom-notifications',
        dataType: "JSON",
        data : {'is_notify':is_notify_admin},
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (res) {
            if(res.success){
                $('#bottom_notification_disp').html(res.notification_code);
                $('.navbar_notification_icon .notification_count').removeClass('d-none');
                $('.navbar_notification_icon .notification_count').text(res.notifications_all.length);
                const bottom_nofication_arrow = document.querySelector('.notfication_bottom .notification');
                const notification_bottom = document.querySelector('.notfication_bottom');
                const notification_cancel = document.querySelector('.notification_bottomn_cancel');
                if(bottom_nofication_arrow){
                    bottom_nofication_arrow.onclick = function(){
                        notification_bottom.classList.toggle('active');
                        notification_cancel.classList.remove('animate_fade_right');
                    }
                }
                const bottom_nofication_close = document.querySelector('.messages .header .close');
                if(bottom_nofication_close){
                    bottom_nofication_close.onclick = function(){
                        notification_bottom.classList.remove('active');
                    }
                }
                if(notification_cancel){
                    notification_cancel.onclick = function(){
                        notification_bottom.classList.add('d-none');
                        notification_cancel.classList.add('d-none');
                    }
                }
            }
        }
    }); 
});

setInterval(() => {
    $.ajax({ 
        // type: 'POST',
        // url: '/get-new-bottom-notifications',
        // contentType: 'multipart/form-data',
        // cache: false,
        // contentType: false,
        // processData: false,
        // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: '/get-new-bottom-notifications',
        dataType: "JSON",
        data : {'is_notify':is_notify_admin},
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (res) {
            const notification_bottom = document.querySelector('.notfication_bottom');
            if(res.success){ 
                if(notification_bottom){
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
                    if(bottom_nofication_arrow){
                        bottom_nofication_arrow.onclick = function(){
                            notification_bottom.classList.toggle('active');
                        }
                    }
                    
                    const bottom_nofication_close = document.querySelector('.messages .header .close');
                    if(bottom_nofication_close){
                        bottom_nofication_close.onclick = function(){
                            notification_bottom.classList.remove('active');
                        }
                    }
                    if(notification_cancel){
                        notification_cancel.onclick = function(){
                            notification_bottom.classList.add('d-none');
                            notification_cancel.classList.add('d-none');
                        }
                    }
                }
            } 
        }
    });
},60000);

$(document).on('click','.navbar_notification_icon',function(){
    if($('.notfication_bottom').hasClass("d-none")){
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

