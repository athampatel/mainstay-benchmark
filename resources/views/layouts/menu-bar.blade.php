@php 
    $menus = [
        [ 'name' => 'products & inventory', 'icon_name' => 'products_gray.svg', 'active' => 1],
        [ 'name' => 'invoiced orders', 'icon_name' => 'invoice_order_gray.svg','active' => 0],
        [ 'name' => 'open orders', 'icon_name' => 'open_orders_gray.svg','active' => 0],
        [ 'name' => 'change order', 'icon_name' => 'change_order_gray.svg','active' => 0],
        [ 'name' => 'vmi user', 'icon_name' => 'vmi_user_gray.svg','active' => 0],
        [ 'name' => 'analysis', 'icon_name' => 'analysis_menu_gray.svg','active' => 0],
        [ 'name' => 'account settings', 'icon_name' => 'account_settings_gray.svg','active' => 0],
        [ 'name' => 'help', 'icon_name' => 'help_menu_gray.svg','active' => 0],
        [ 'name' => 'logout', 'icon_name' => 'logout_menu_gray.svg','active' => 0],
    ];
@endphp

<div class="sidebar">
    <div class="logo-details">
      <img src="assets/images/logo.svg" width="180" alt="" />
    </div>
      <ul class="nav-links">
        @foreach ($menus as $menu)
            <li class="<?php echo $menu['active'] == 1 ? 'active':''; ?>">
                {{-- <a href="#" class="active"> --}}
                <a href="#" class="" >
                    <img src="assets/images/svg/{{$menu['icon_name']}}" width="" alt=""  />
                    <span class="links_name">{{$menu['name']}}</span>
                </a>
            </li>
        @endforeach
      </ul>
  </div>