{{-- <div class="nav-bar" style="height:100vh;max-width:300px;min-width:300px">
    <div class="text-center" style="margin-top:60px; margin-bottom:60px;">
        <img src="assets/images/logo.svg" width="180" alt="" />
    </div>
    <div class="">
        <ul>
            <li>
                <a href="" class="d-flex" style="gap:20px">
                    <div>
                        <img src="assets/images/svg/icon1.svg" width="180" alt="" height="50" />
                    </div>
                    <div>
                        products & inventory
                    </div>
                </a>
            </li>
            <li>
                <a href="">                                      
                    invoiced orders</a>
            </li>
            <li>
                <a href="">
                    open orders
                </a>
            </li>
            <li>
                <a href="">
                    change order
                </a>
            </li>
            <li>
                <a href="">
                    vmi user
                </a>
            </li>
            <li>
                <a href="">
                    analysis
                </a>
            </li>
            <li>
                <a href="">
                    account settings
                </a>
            </li>
            <li>
                <a href="">
                    help
                </a>
            </li>
            <li>
                <a href="">logout</a>
            </li>
        </ul>
    </div>
</div> --}}


@php 

$menus = [
    [ 'name' => 'products & inventory', 'icon_name' => 'products.svg','active' => 1],
    [ 'name' => 'invoiced orders', 'icon_name' => 'invoice_order.svg','active' => 0],
    [ 'name' => 'open orders', 'icon_name' => 'open_orders.svg','active' => 0],
    [ 'name' => 'change order', 'icon_name' => 'change_order.svg','active' => 0],
    [ 'name' => 'vmi user', 'icon_name' => 'vmi_user.svg','active' => 0],
    [ 'name' => 'analysis', 'icon_name' => 'analysis_menu.svg','active' => 0],
    [ 'name' => 'account settings', 'icon_name' => 'account_settings.svg','active' => 0],
    [ 'name' => 'help', 'icon_name' => 'help_menu.svg','active' => 0],
    [ 'name' => 'logout', 'icon_name' => 'logout_menu.svg','active' => 0],
];

@endphp


<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="margin-top:30px; margin-bottom:70px">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon">
            <img src="assets/images/logo.svg" width="180" alt="" />
        </div>
    </a>

    @foreach ($menus as $menu)    
        <li class="nav-item">
            <a class="nav-link font-semi-bold font-open-sans font-14 text-capitalize" href="index.html" style="padding-top:24px; padding-bottom:24px;padding-left:25px;padding-right:0px;">
                <img src="assets/images/svg/{{$menu['icon_name']}}" width="" alt="" height="30px" />
                <span class="<?php echo $menu['active'] ? 'primary-green' : 'color-primary-gray'; ?>  font-semi-bold" style="margin-left: 10px;">{{$menu['name']}}</span></a>
        </li>    
    @endforeach
</ul>