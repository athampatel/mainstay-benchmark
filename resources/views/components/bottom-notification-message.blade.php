@php 
$link = str_replace('\\','/',$link);
$icon = str_replace('\\','/',$icon) 
@endphp
<a href="#" data-link="{{$link}}" data-id="{{$id}}" class="bottom_notification_msg" style="text-decoration: none;">
    <div class="message_section">
        <div class="message_icon">
              @if($icon)
                <img src="{{URL::to('/').$icon}}" alt="">
              @else 
                <img src="{{URL::to('/').'/assets/images/svg/default_notification_icon.svg'}}" alt="">
              @endif
        </div>
        <div class="messsage_content">
            <div class="message_title">{{$title}}</div>
            <div class="message_desc">{{$desc}}</div>
        </div>
    </div>
    <div class="notification_msg_time">{{\Carbon\Carbon::parse($time)->diffForHumans()}}</div>
</a>