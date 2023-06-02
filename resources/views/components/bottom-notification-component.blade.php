<div class="notfication_bottom">
    <div class="messages">
        <div class="header">
            <div>
                All Message
            </div>
            <div class="close">
                <svg xmlns="http://www.w3.org/2000/svg" width="18.154" height="18.153" viewBox="0 0 18.154 18.153">
                    <path id="Path_1057" data-name="Path 1057" d="M16.09.775,9.281,7.582,2.474.775.2,3.044,7.012,9.851.2,16.658l2.269,2.269L9.281,12.12l6.809,6.807,2.269-2.269L11.552,9.851l6.807-6.807Z" transform="translate(-0.205 -0.775)" fill="#fff"/>
                </svg>  
            </div>
        </div>
        <div class="messages-container">
            @foreach ($notifications as $notification)    
                @php
                // $link = str_replace("/","\\",$notification->action)
                $link = str_replace("/","\\",$notification['action'])
                @endphp
                {{-- <x-bottom-notification-message :title="$notification->type" :desc="$notification->text" :icon="$notification->icon_path" :time="$notification->created_at" :link="$link" :id="$notification->id" /> --}}
                <x-bottom-notification-message :title="$notification['type']" :desc="$notification['text']" :icon="$notification['icon_path']" :time="$notification['created_at']" :link="$link" :id="$notification['id']" />
            @endforeach
        </div>
        <div class="clear_notifications">
            <div class="notification_clear_msg" id="clearAllNofications">Clear All Notifications</div>
        </div>
    </div>
    <div class="notification">
        <div class="icon-container">
            <div class="icon">
                <svg id="Group_15" data-name="Group 15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18.871" height="20.316" viewBox="0 0 18.871 20.316">
                    <defs>
                        <clipPath id="clip-path">
                        <rect id="Rectangle_10" data-name="Rectangle 10" width="18.871" height="20.316" fill="#fff"/>
                        </clipPath>
                    </defs>
                    <g id="Group_14" data-name="Group 14" transform="translate(0 0)" clip-path="url(#clip-path)">
                        <path id="Path_16" data-name="Path 16" d="M10.947,12.984a53.182,53.182,0,0,1,.066-7.389A6.911,6.911,0,0,1,18.322.012a6.987,6.987,0,0,1,6.713,6.3c.169,2.187.031,4.4.031,6.676Z" transform="translate(-8.521 0)" fill="#fff"/>
                        <path id="Path_17" data-name="Path 17" d="M9.488,66.009c2.566,0,5.132-.008,7.7,0,1.173.005,1.7.431,1.684,1.307S18.324,68.6,17.14,68.6q-7.7.011-15.4,0C.5,68.6-.117,68.054.018,67.114c.13-.9.769-1.107,1.558-1.106q3.956.006,7.912,0" transform="translate(0 -51.876)" fill="#fff"/>
                        <path id="Path_18" data-name="Path 18" d="M35.422,83.538a3.38,3.38,0,0,1-6.489,0Z" transform="translate(-22.74 -65.656)" fill="#fff"/>
                    </g>
                </svg>              
            </div>
            <div class="count">
                {{count($notifications)}}
            </div>
        </div>
        <div class="message">
            You have <span id="message_count">{{count($notifications)}}</span> {{ count($notifications) > 1 ? 'notifications': 'notification' }}
        </div>
        <div class="open-close" id="bottom_message_arrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="17.806" height="9.649" viewBox="0 0 17.806 9.649">
                <path id="Path_1054" data-name="Path 1054" d="M9.56,10.17a.747.747,0,0,1-.527-.218L.876,1.794A.745.745,0,0,1,1.93.74L9.56,8.371,17.191.74a.745.745,0,1,1,1.054,1.054L10.087,9.951a.746.746,0,0,1-.527.219Z" transform="translate(-0.658 -0.521)" fill="#fff"/>
            </svg>          
        </div>
    </div>
    <div class="notification_bottomn_cancel  animate_fade_right">
        <svg xmlns="http://www.w3.org/2000/svg" width="11.681" height="11.681" viewBox="0 0 11.681 11.681"><g transform="translate(-1561.159 -1107.159)"><path d="M4,0A4,4,0,0,1,4,8,4.173,4.173,0,0,1,1.172,6.828,3.816,3.816,0,0,1,0,4,4,4,0,0,1,4,0Z" transform="translate(1563 1109)" fill="#fff"/><path d="M83.951,8.574a5.841,5.841,0,1,0,5.841,5.841A5.841,5.841,0,0,0,83.951,8.574Zm2.5,7.588a.535.535,0,1,1-.757.757l-1.748-1.748L82.2,16.92a.535.535,0,1,1-.757-.757l1.747-1.748-1.747-1.747a.535.535,0,1,1,.757-.757l1.748,1.748L85.7,11.91a.535.535,0,0,1,.757.757l-1.748,1.747Z" transform="translate(1483.049 1098.585)" fill="#424448"/></g></svg>                          
    </div>
</div>