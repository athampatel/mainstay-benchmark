{{-- new code  --}}
<div class="search-bar flex-grow-1">
    <div class="position-relative search-bar-box col-12"> 
        <input id="full_search_input" type="text" class="form-control search-control" placeholder="Type to search..." spellcheck="false" data-ms-editor="true"> <span class="position-absolute top-50 search-show translate-middle-y"><i class="bx bx-search"></i></span>
        <span class="position-absolute top-50 search-close translate-middle-y"><i class="bx bx-x"></i></span>
    </div>
</div>

<nav>
    <div class="sidebar-button">
        <a class="menu-icon toggle-icon hamburger" href="javascript:void(0)"><span></span></a>
        <!--<svg xmlns="http://www.w3.org/2000/svg" width="27" height="22" viewBox="0 0 27 22">
            <g class="Group_893" data-name="Group 893" transform="translate(-292 -35)">
                <rect class="Rectangle_25" data-name="Rectangle 25" width="27" height="4" transform="translate(292 35)" fill="#424448"/>
                <rect class="Rectangle_26" data-name="Rectangle 26" width="16" height="4" transform="translate(292 44)" fill="#424448"/>
                <rect class="Rectangle_27" data-name="Rectangle 27" width="8" height="4" transform="translate(311 44)" fill="#424448"/>
                <rect class="Rectangle_28" data-name="Rectangle 28" width="16" height="4" transform="translate(292 53)" fill="#424448"/>
            </g>
        </svg> -->
        
        {{-- <div class="search-box"> --}}
        <div class="nav-item mobile-search-icon">
            <a href="javascript:void(0)" class="search-icons">
            <svg class="Group_3" data-name="Group 3" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24.375" height="24.377" viewBox="0 0 24.375 24.377">
                <defs>
                    <clipPath class="clip-path">
                    <rect class="Rectangle_6" data-name="Rectangle 6" width="24.375" height="24.377" fill="#424448"/>
                    </clipPath>
                </defs>
                <g class="Group_9" data-name="Group 9" clip-path="url(#clip-path)">
                    <path class="Path_11" data-name="Path 11" d="M18.766,16.463c.642.64,1.29,1.283,1.936,1.928,1.061,1.06,2.136,2.108,3.178,3.187A1.633,1.633,0,1,1,21.5,23.814q-2.356-2.373-4.727-4.73c-.109-.109-.223-.214-.33-.315A10.376,10.376,0,0,1,2.911,3.144,10.38,10.38,0,0,1,18.766,16.463m-8.44.983A7.092,7.092,0,1,0,3.25,10.275a7.067,7.067,0,0,0,7.076,7.171" transform="translate(-0.001 0)" fill="#424448"/>
                </g>
            </svg>
            </a>
        </div>
    </div>
    <div class="notification_section">
        <div class="notification_icons d-flex align-items-center">
            <div class="notification_count_section item-config">
                <div class="notification_icon" id="account_setting_nav" data-toggle="tooltip" data-placement="bottom" title="Account Settings">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="21.154" height="22.644" viewBox="0 0 21.154 22.644">
                    <defs>
                        <clipPath class="clip-path">
                        <rect class="Rectangle_29" data-name="Rectangle 29" width="21.154" height="22.644" fill="#424448"/>
                        </clipPath>
                    </defs>
                    <g class="Group_895" data-name="Group 895" clip-path="url(#clip-path)">
                        <path id="Path_681" data-name="Path 681" d="M10.593,0c.4,0,.8-.011,1.195,0a1.345,1.345,0,0,1,1.284,1.305q.016.8,0,1.593a.3.3,0,0,0,.233.345A8.112,8.112,0,0,1,16.22,4.938a.244.244,0,0,0,.345.037c.446-.27.9-.526,1.353-.785a1.318,1.318,0,0,1,1.91.506q.563.961,1.113,1.929A1.3,1.3,0,0,1,20.462,8.5c-.452.276-.913.54-1.377.8a.267.267,0,0,0-.154.341,8.146,8.146,0,0,1,0,3.344.282.282,0,0,0,.158.363c.45.244.892.5,1.332.767a1.318,1.318,0,0,1,.505,1.93q-.545.956-1.1,1.905a1.315,1.315,0,0,1-1.889.513c-.455-.255-.907-.515-1.354-.784a.26.26,0,0,0-.367.027,8.191,8.191,0,0,1-2.89,1.68.309.309,0,0,0-.255.355c.015.513.01,1.027,0,1.54a1.346,1.346,0,0,1-1.344,1.356q-1.155.02-2.31,0a1.344,1.344,0,0,1-1.339-1.361c-.007-.522-.008-1.045,0-1.567a.268.268,0,0,0-.207-.311,8.182,8.182,0,0,1-2.941-1.7.243.243,0,0,0-.344-.031c-.446.27-.9.526-1.355.782a1.314,1.314,0,0,1-1.908-.509q-.564-.96-1.114-1.929a1.307,1.307,0,0,1,.5-1.889c.448-.267.9-.529,1.355-.781A.271.271,0,0,0,2.229,13a7.983,7.983,0,0,1,0-3.369.271.271,0,0,0-.164-.338c-.434-.239-.859-.492-1.287-.74A1.33,1.33,0,0,1,.241,6.573Q.782,5.63,1.33,4.692a1.314,1.314,0,0,1,1.888-.513c.454.255.91.51,1.353.784a.272.272,0,0,0,.387-.043,8.109,8.109,0,0,1,2.9-1.669.28.28,0,0,0,.228-.322c-.011-.531-.009-1.062,0-1.593A1.347,1.347,0,0,1,9.4.005c.4-.011.8,0,1.195,0h0M10.564,16.45c.628,0,1.256.005,1.883,0a1.4,1.4,0,0,0,1.434-1.2,3.212,3.212,0,0,0-.01-.92,3.342,3.342,0,0,0-6.628.234,1.541,1.541,0,0,0,1.65,1.89c.555-.035,1.114-.006,1.671-.006m2.316-8.2a2.3,2.3,0,1,0-2.308,2.311,2.286,2.286,0,0,0,2.308-2.311" transform="translate(0 0)" fill="#424448"/>
                    </g>
                    </svg>
                </div>
                <div class="notification_count">7</div>
            </div>
            <div class="notification_count_section dropdown dropdown-large position-relative item-message">
                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="notification_icon" data-toggle="tooltip" data-placement="bottom" title="Change Order Requests" id="change_order_Request_nav">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="21.446" height="21.446" viewBox="0 0 21.446 21.446">
                        <defs>
                            <clipPath class="clip-path">
                            <rect class="Rectangle_9" data-name="Rectangle 9" width="21.446" height="21.446" fill="#424448"/>
                            </clipPath>
                        </defs>
                        <g class="Group_12" data-name="Group 12" clip-path="url(#clip-path)">
                            <path class="Path_14" data-name="Path 14" d="M120.325,117.358q0,3.485,0,6.97a.539.539,0,0,1-.212.507.488.488,0,0,1-.669-.132c-.786-.872-1.6-1.723-2.344-2.628a1.55,1.55,0,0,0-1.362-.608c-3.338.031-6.676.015-10.014.015a1.451,1.451,0,0,1-1.606-1.6q0-4.667,0-9.335a1.438,1.438,0,0,1,1.585-1.591h13.033a1.433,1.433,0,0,1,1.586,1.586q0,3.409,0,6.819m-8.111-1.437h4.123a2.741,2.741,0,0,0,.3-.011.679.679,0,0,0,.592-.506.624.624,0,0,0-.229-.718,1.056,1.056,0,0,0-.556-.176q-4.224-.016-8.448-.005a1.117,1.117,0,0,0-.345.051.7.7,0,0,0,.038,1.332,1.637,1.637,0,0,0,.4.033q2.062,0,4.123,0m.006,2.506h.679c1.2,0,2.4,0,3.6,0a.709.709,0,0,0,.755-.82.732.732,0,0,0-.809-.594h-8.423a1.294,1.294,0,0,0-.2.007.7.7,0,0,0-.58.459.7.7,0,0,0,.709.948c1.425.005,2.85,0,4.274,0m-2.19-5.005c.7,0,1.409,0,2.113,0a.715.715,0,1,0,0-1.424h-.1c-1.325,0-2.649,0-3.974,0a1.313,1.313,0,0,0-.466.074.686.686,0,0,0-.407.759.717.717,0,0,0,.744.586c.7.009,1.392,0,2.088,0" transform="translate(-98.881 -103.47)" fill="#424448"/>
                            <path class="Path_15" data-name="Path 15" d="M12.872,4.341h-.335c-1.92,0-3.839,0-5.759,0A2.375,2.375,0,0,0,4.367,6.156a3.031,3.031,0,0,0-.077.7c-.008.956,0,1.911,0,2.867,0,.09,0,.179,0,.293-.354,0-.68.012-1,0a.532.532,0,0,0-.473.208c-.617.708-1.247,1.4-1.873,2.1A1.292,1.292,0,0,1,.76,12.5a.471.471,0,0,1-.755-.331,2.143,2.143,0,0,1,0-.226Q0,6.771,0,1.6A1.44,1.44,0,0,1,1.6,0H11.28a1.446,1.446,0,0,1,1.591,1.589q0,1.232,0,2.465v.287" transform="translate(-0.001 0)" fill="#424448"/>
                        </g>
                        </svg>
                    </div>                
                    <div class="notification_count alert-count">10</div>
                </a>
            </div>
            <div class="notification_count_section active item-notification navbar_notification_icon">
                <div class="notification_icon" data-toggle="tooltip" data-placement="bottom" title="Notifications">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18.871" height="20.316" viewBox="0 0 18.871 20.316">
                        <defs>
                            <clipPath class="clip-path">
                            <rect class="Rectangle_10" data-name="Rectangle 10" width="18.871" height="20.316" fill="#424448"/>
                            </clipPath>
                        </defs>
                        <g class="Group_14" data-name="Group 14" clip-path="url(#clip-path)">
                            <path class="Path_16" data-name="Path 16" d="M10.947,12.984a53.182,53.182,0,0,1,.066-7.389A6.911,6.911,0,0,1,18.322.012a6.987,6.987,0,0,1,6.713,6.3c.169,2.187.031,4.4.031,6.676Z" transform="translate(-8.521 0)" fill="#424448"/>
                            <path class="Path_17" data-name="Path 17" d="M9.488,66.009c2.566,0,5.132-.008,7.7,0,1.173.005,1.7.431,1.684,1.307S18.324,68.6,17.14,68.6q-7.7.011-15.4,0C.5,68.6-.117,68.054.018,67.114c.13-.9.769-1.107,1.558-1.106q3.956.006,7.912,0" transform="translate(0 -51.876)" fill="#424448"/>
                            <path class="Path_18" data-name="Path 18" d="M35.422,83.538a3.38,3.38,0,0,1-6.489,0Z" transform="translate(-22.74 -65.656)" fill="#424448"/>
                        </g>
                    </svg>
                </div>
                <div class="notification_count d-none">2</div>
            </div>
        </div>



        <div class="profile-details user-box dropdown border-light-2">
            <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                @if(Auth::user()->profile_image)
                    <img src="/{{Auth::user()->profile_image}}" height="45" width="45" id="nav-bar-profile-img" class="rounded-circle nav-bar-profile-img" />
                @else 
                    <img src="/assets/images/svg/user_logo.png" id="nav-bar-profile-img" />
                @endif
            <div>
                 <div class="profile_position">{{Auth::user()->name}}</div>
                @if ($customerno = Session::get('customer_no'))                   
                    <div class="profile_name" id="nav-bar-profile-name">{{$customerno}}</div>
                @endif                    
            </div>
            </a>

            @if ($customers = Session::get('customers'))
                <ul class="dropdown-menu dropdown-menu-end">
                @php
                    $sessionData = json_decode($customers,true);
                @endphp
                @foreach($sessionData as $sessioninfo)                   
                    <li>
                        <a class="dropdown-item @if( $sessioninfo['customerno'] == $customerno) selected @endif" href="@if( $sessioninfo['customerno'] == $customerno)javascript:void(0) @else {{ url('switch-account') }}/{{$sessioninfo['customerno']}}@endif"><i class="bx bx-user"></i><span>{{$sessioninfo['customerno']}}</span></a>
                    </li>
                @endforeach
                </ul>    
            @endif
    </div>
  </nav>
