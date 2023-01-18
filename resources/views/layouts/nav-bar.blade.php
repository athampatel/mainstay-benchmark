{{-- new code  --}}
<nav>
    <div class="sidebar-button">
        <div>
            <img src="/assets/images/svg/menu_icon_gray.svg" class="sidebarBtn" height="22px" />
        </div>
        {{-- <div class="search-box"> --}}
        <div style="padding-right: 20px;">
            <img src="/assets/images/svg/search_icon_gray.svg" height="25px" />
            {{-- <input type="text" placeholder="Search..."> --}}
        </div>
    </div>
    <div class="notification_section">
        <div class="notification_icons">
            <div class="notification_count_section">
                <div class="notification_icon">
                    <img src="/assets/images/svg/user_setting_icon_gray.svg" height="22px" />
                </div>
                <div class="notification_count">7</div>
            </div>
            <div class="notification_count_section">
                <div class="notification_icon">
                    <img src="/assets/images/svg/message_icon_gray.svg" height="22px" />
                </div>
                <div class="notification_count">10</div>
            </div>
            <div class="notification_count_section active">
                <div class="notification_icon">
                    <img src="/assets/images/svg/notification_icon_gray.svg" height="22px" />
                </div>
                <div class="notification_count">2</div>
            </div>
        </div>
        <div class="profile-details">
            <div>
                <img src="/assets/images/svg/user_logo.png" />
            </div>
            <div>
                <div class="profile_position">Admin</div>
                {{-- <div class="profile_name">John Deo</div> --}}
                <div class="profile_name">{{Auth::user()->name}}</div>
            </div>
        </div>
    </div>
  </nav>
