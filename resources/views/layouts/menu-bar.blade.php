@php 
    $menus = [
        [ 'name' => 'products & inventory', 'icon_name' => 'products_gray.svg'],
        [ 'name' => 'invoiced orders', 'icon_name' => 'invoice_order_gray.svg'],
        [ 'name' => 'open orders', 'icon_name' => 'open_orders_gray.svg'],
        [ 'name' => 'change order', 'icon_name' => 'change_order_gray.svg'],
        [ 'name' => 'vmi user', 'icon_name' => 'vmi_user_gray.svg'],
        [ 'name' => 'analysis', 'icon_name' => 'analysis_menu_gray.svg'],
        [ 'name' => 'account settings', 'icon_name' => 'account_settings_gray.svg'],
        [ 'name' => 'help', 'icon_name' => 'help_menu_gray.svg'],
        [ 'name' => 'logout', 'icon_name' => 'logout_menu_gray.svg'],
    ];
@endphp

<div class="sidebar">
    <div class="logo-details">
      <img src="assets/images/logo.svg" width="180" alt="" />
    </div>
      <ul class="nav-links">
        @foreach ($menus as $menu)
            <li>
                {{-- <a href="#" class="active"> --}}
                <a href="#" class="" >
                    <img src="assets/images/svg/{{$menu['icon_name']}}" width="" alt="" height="30px" />
                    <span class="links_name">{{$menu['name']}}</span>
                </a>
            </li>
        @endforeach
      </ul>
  </div>