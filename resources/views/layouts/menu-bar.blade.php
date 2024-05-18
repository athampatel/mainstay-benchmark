
<div class="sidebar sidebar-wrapper" id="sidebar">

    <div class="logo-details">
      <a href="/dashboard"><img src="/assets/images/logo.svg" alt="company logo" /></a>
    </div>
      <ul class="nav-links">
        @foreach ($menus as $key => $menu)
          @if(in_array($menu['code'],$customer_menus))
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
                  <a href="{{url('/')}}/logout" id="{{$menu['link']}}" onclick="event.preventDefault();document.getElementById('customer-logout-form').submit();">
                      <span class="menu-icons">{!! $menu['icon_name'] !!}</span>
                      <span class="links_name">{{$menu['name']}}</span>
                  </a>
                  <form id="customer-logout-form" action="{{url('/')}}/logout" method="POST" style="display: none;">
                  @csrf
                  </form>
                </li>
              @else 
                <li class="<?php echo $menu['active'] == 1 ? 'active':''; ?>">
                <a href="{{$menu['link']}}" id="{{$menu['link']}}">
                    <span class="menu-icons">{!! $menu['icon_name'] !!}</span>
                    <span class="links_name">{{$menu['name']}}</span>
                </a>
                </li>
              @endif
              @if($key === 'by_admin')
              @section('scripts')
              <script>
                  caches.keys().then((keyList) => Promise.all(keyList.map((key) => caches.delete(key))))
              </script>
              @endsection

              @endif
            @endif
        @endforeach
      </ul>
  </div>