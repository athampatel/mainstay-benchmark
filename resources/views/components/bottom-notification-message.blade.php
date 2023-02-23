@php 
$link = str_replace('\\','/',$link);
@endphp
<a href="#" data-link="{{$link}}" data-id="{{$id}}" class="bottom_notification_msg">
    <div class="message_section">
        <div class="message_icon">
            <svg id="Group_1295" data-name="Group 1295" xmlns="http://www.w3.org/2000/svg" width="25.794" height="22.85" viewBox="0 0 25.794 22.85">
                <path id="Path_1055" data-name="Path 1055" d="M10.182,14.639a5.43,5.43,0,1,0-5.43-5.43A5.436,5.436,0,0,0,10.182,14.639ZM12.218,16H8.145A8.154,8.154,0,0,0,0,24.141V25.5H20.363V24.141A8.154,8.154,0,0,0,12.218,16Z" transform="translate(0 -2.649)" fill="#424448"/>
                <path id="Path_1056" data-name="Path 1056" d="M55.166,10.7a7.7,7.7,0,0,0,1.02-4.67A8.193,8.193,0,0,0,52.38,0l-1.5,2.262a5.534,5.534,0,0,1,2.6,4.037,5.014,5.014,0,0,1-1.455,4.054L50.41,11.971l2.2.645a8.128,8.128,0,0,1,5.812,7.519h2.715A10.834,10.834,0,0,0,55.166,10.7Z" transform="translate(-35.34)" fill="#424448"/>
              </svg>                      
        </div>
        <div class="messsage_content">
            <div class="message_title">{{$title}}</div>
            <div class="message_desc">{{$desc}}</div>
        </div>
    </div>
    <div class="notification_msg_time">{{\Carbon\Carbon::parse($time)->diffForHumans()}}</div>
</a>