<div class="sidebar">
    <div class="logo-details">
      <a href="/dashboard"><img src="/assets/images/logo.svg" alt="company logo" /></a>
    </div>
      <ul class="nav-links">
        @foreach ($menus as $menu)
            <li class="<?php echo $menu['active'] == 1 ? 'active':''; ?>">
            
            <a href="{{$menu['link']}}" id="{{$menu['link']}}">
                <span class="menu-icons">{!! $menu['icon_name'] !!}</span>
                <span class="links_name">{{$menu['name']}}</span>
            </a>
            </li>
        @endforeach
      </ul>
  </div>