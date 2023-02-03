<div class="sidebar" id="sidebar">
    <div class="logo-details">
      <a href="/dashboard"><img src="/assets/images/logo.svg" alt="company logo" /></a>
    </div>
      <ul class="nav-links">
        @foreach ($menus as $key => $menu)
            @if($key === 'vmi-user')
              @if(Auth::user()->is_vmi == 1)
                <li class="<?php echo $menu['active'] == 1 ? 'active':''; ?>">
                <a href="{{$menu['link']}}" id="{{$menu['link']}}">
                    <span class="menu-icons">{!! $menu['icon_name'] !!}</span>
                    <span class="links_name">{{$menu['name']}}</span>
                </a>
                </li>
              @endif
            @elseif($key === 'logout')
              <li class="<?php echo $menu['active'] == 1 ? 'active':''; ?>">
                <a href="" id="{{$menu['link']}}">
                    <span class="menu-icons">{!! $menu['icon_name'] !!}</span>
                    <span class="links_name">{{$menu['name']}}</span>
                </a>
              </li>
            @else 
              <li class="<?php echo $menu['active'] == 1 ? 'active':''; ?>">
              <a href="{{$menu['link']}}" id="{{$menu['link']}}">
                  <span class="menu-icons">{!! $menu['icon_name'] !!}</span>
                  <span class="links_name">{{$menu['name']}}</span>
              </a>
              </li>
            @endif
        @endforeach
      </ul>
  </div>