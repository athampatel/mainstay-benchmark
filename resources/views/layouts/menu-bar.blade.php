@php 
    $menus = [
        [ 'name' => 'products & inventory', 'icon_name' => 'products_gray.svg', 'active' => 1,'link'=> 'dashboard',],
        [ 'name' => 'invoiced orders', 'icon_name' => 'invoice_order_gray.svg','active' => 0,'link'=> 'invoice',],
        [ 'name' => 'open orders', 'icon_name' => 'open_orders_gray.svg','active' => 0,'link'=> 'open-orders',],
        [ 'name' => 'change order', 'icon_name' => 'change_order_gray.svg','active' => 0,'link'=> 'change-order',],
        [ 'name' => 'vmi user', 'icon_name' => 'vmi_user_gray.svg','active' => 0,'link'=> 'vmi-user',],
        [ 'name' => 'analysis', 'icon_name' => 'analysis_menu_gray.svg','active' => 0,'link'=> 'analysis',],
        [ 'name' => 'account settings', 'icon_name' => 'account_settings_gray.svg','active' => 0,'link'=> 'account-settings',],
        [ 'name' => 'help', 'icon_name' => 'help_menu_gray.svg','active' => 0,'link'=> 'help',],
        [ 'name' => 'logout', 'icon_name' => 'logout_menu_gray.svg','active' => 0,'link'=> 'logout',],
    ];
@endphp
<div class="sidebar">
    <div class="logo-details">
      <img src="assets/images/logo.svg" alt="company logo" />
    </div>
      <ul class="nav-links">
        @foreach ($menus as $menu)
            <li class="<?php echo $menu['active'] == 1 ? 'active':''; ?>">
                @if($menu['name'] == 'logout')
                    <a href="" id="{{$menu['link']}}">
                        <img src="assets/images/svg/{{$menu['icon_name']}}" alt="menu icon"  />
                        <span class="links_name">{{$menu['name']}}</span>
                    </a>
                @else 
                    <a href="{{$menu['link']}}" id="{{$menu['link']}}">
                        <img src="assets/images/svg/{{$menu['icon_name']}}" alt="menu icon"  />
                        <span class="links_name">{{$menu['name']}}</span>
                    </a>
                @endif
            </li>
        @endforeach
      </ul>
  </div>