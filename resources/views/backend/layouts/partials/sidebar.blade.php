 <!-- sidebar menu area start -->
 @php
     $usr = Auth::guard('admin')->user();
 @endphp

 <div class="sidebar sidebar-wrapper sidebar-menu" id="sidebar">
    <div class="logo-details">
        <a href="{{ route('admin.dashboard') }}"><img src="/assets/images/logo.svg" alt="company logo" /></a>
    </div>
    <div class="main-menu">
        <div class="menu-inner"> 
            <nav>
                <ul class="metismenu nav-links" id="menu">
                    @if ($usr->can('dashboard.view'))
                        <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}">
                            <span class="menu-icons">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24.352" height="22" viewBox="0 0 24.352 22">
                                    <g id="Group_941" data-name="Group 941" transform="translate(-1692.856 -180)">
                                        <path id="Path_728" data-name="Path 728" d="M227.755,119.063v5.384c-.442-.235-.888-.471-1.334-.706l-3.358-1.773c-.162-.086-.312-.174-.483-.258v-5.381c.049.029.115.059.172.088l.007,0,.5.262.164.088.167.088.334.177c1.119.591,2.239,1.177,3.358,1.769,0,0,.007,0,.012,0a.187.187,0,0,0,.081.032c0,.025.01.015.012.017.064.034.125.071.189.105.037.02.069.042.105.061a.741.741,0,0,1,.076.039Z" transform="translate(1476.657 67.361)" fill="#666762"></path>
                                        <path id="Path_729" data-name="Path 729" d="M367.206,116.54v5.377h0c-.079.037-.137.071-.206.108l-3.2,1.69c-.02.01-.037.022-.059.032-.569.3-1.143.594-1.707.9v-5.377a1.863,1.863,0,0,1,.2-.108q1.6-.846,3.2-1.69c.591-.312,1.185-.616,1.773-.927Z" transform="translate(1343.616 67.16)" fill="#666762"></path>
                                        <path id="Path_730" data-name="Path 730" d="M246.407,39.229l-1.248.66L241.8,41.662l-.5.265-1.847-.976L236.2,39.229l1.249-.66L240.8,36.8l.5-.265c.616.326,1.231.65,1.849.976l3.257,1.722Z" transform="translate(1463.729 143.469)" fill="#666762"></path>
                                        <path id="Path_731" data-name="Path 731" d="M88.9,339.515v5.377c-.466-.231-.881-.464-1.317-.694q-1.678-.887-3.353-1.771c-.169-.088-.356-.179-.5-.267v-5.381a2.622,2.622,0,0,0,.289.152l.039.022c.167.088.334.181.5.27s.334.172.5.27c1.111.589,2.222,1.17,3.333,1.756H88.4s0,0,0,0l.012,0,.093.049c.128.069.253.137.383.206,0,0,0,0,.007,0Z" transform="translate(1609.132 -142.894)" fill="#666762"></path>
                                        <path id="Path_732" data-name="Path 732" d="M227.755,336.88v5.377c-.613.326-1.234.652-1.852.979-1.109.586-2.2,1.17-3.324,1.756V339.61s.017-.007.025-.012l.182-.1c.466-.248.935-.493,1.4-.741.6-.316,1.2-.635,1.8-.952.221-.118.442-.233.665-.348.054-.027.105-.056.159-.083.253-.132.5-.262.755-.4a1.873,1.873,0,0,0,.189-.1Z" transform="translate(1476.658 -142.992)" fill="#666762"></path>
                                        <path id="Path_733" data-name="Path 733" d="M107.265,259.718c-.392.221-.824.441-1.241.66q-1.678.887-3.353,1.776l-.248.132-.25.132-1.845-.976-3.255-1.722,1.248-.66,3.355-1.771c.167-.088.334-.189.5-.262.155.074.307.159.461.24l1.378.731q1.626.861,3.248,1.719Z" transform="translate(1596.381 -66.883)" fill="#666762"></path>
                                        <path id="Path_734" data-name="Path 734" d="M367.2,339.41v5.377c-.466-.231-.881-.464-1.32-.694q-1.678-.887-3.353-1.771c-.169-.088-.331-.179-.5-.267v-5.386l1.334.709q1.678.887,3.358,1.776l.093.049c.13.069.243.14.39.208Z" transform="translate(1343.617 -142.789)" fill="#666762"></path>
                                        <path id="Path_735" data-name="Path 735" d="M506.113,336.923c-.5.265-1,.525-1.506.79l-.007,0-.108.056c-.037.02-.071.037-.105.056a.291.291,0,0,1-.039.02c.007,0,.012-.007.02-.01s-.007,0-.01,0-.007,0-.01,0q-1.6.846-3.2,1.69c-.007,0-.017.007-.025.012-.056.032-.11.061-.179.093l0,0h-.007v5.386c1.128-.584,2.215-1.17,3.324-1.756.618-.326,1.239-.652,1.852-.979l0-5.372h0Zm-5.171,2.725c.059-.032.118-.064.179-.093A2.017,2.017,0,0,1,500.943,339.648Z" transform="translate(1211.095 -143.032)" fill="#666762"></path>
                                        <path id="Path_736" data-name="Path 736" d="M385.633,259.57l-1.248.66L381.029,262l-.186.1a.009.009,0,0,0,0,0c-.066.034-.132.071-.2.108a.9.9,0,0,0-.105.059c-.616-.343-1.231-.655-1.849-.981l-1.629-.863c-.272-.145-.542-.287-.814-.429l-.2-.108-.608-.324,1.246-.657,3.358-1.773.5-.265q.924.489,1.847.976,1.626.865,3.255,1.724Z" transform="translate(1330.974 -66.732)" fill="#666762"></path>
                                    </g>
                                    </svg>
                                </span>  
                                <span class="links_name">Dashboard</span>
                            </a>
                    </li>
                    @endif
                @if ($usr->can('role.create') || $usr->can('role.view') ||  $usr->can('role.edit') ||  $usr->can('role.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true">
                            <span class="menu-icons">
                                <svg xmlns="http://www.w3.org/2000/svg" width="23.999" height="22" viewBox="0 0 23.999 22">
                                <g id="Group_1020" data-name="Group 1020" transform="translate(-109.13 -59.196)">
                                    <path id="Path_816" data-name="Path 816" d="M125.566,70.046a6.333,6.333,0,1,0-8.871,0A12,12,0,0,0,109.13,81.2h24a12,12,0,0,0-7.563-11.15Z" transform="translate(0 0)" fill="#666762"></path>
                                </g>
                                </svg>
                            </span>
                            <span class="links_name">Roles & Permissions</span>
                        </a>
                        <ul class="collapse {{ Route::is('admin.roles.create') || Route::is('admin.roles.index') || Route::is('admin.roles.edit') || Route::is('admin.roles.show') ? 'in' : '' }}">
                            @if ($usr->can('role.view'))
                                <li class="{{ Route::is('admin.roles.index')  || Route::is('admin.roles.edit') ? 'active' : '' }}">
                                    <a href="{{ route('admin.roles.index') }}">
                                        <span class="menu-icons"><i class="fa fa-tasks"></i></span>
                                        <span class="links_name">All Roles</span>
                                    </a>
                                </li>
                            @endif
                            @if ($usr->can('role.create'))
                                <li class="{{ Route::is('admin.roles.create')  ? 'active' : '' }}">
                                    <a href="{{ route('admin.roles.create') }}">
                                        <span class="menu-icons"><i class="fa fa-plus"></i></span>
                                        <span class="links_name">Create Role</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    @endif 

                    
                    @if ($usr->can('admin.create') || $usr->can('admin.view') ||  $usr->can('admin.edit') ||  $usr->can('admin.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true">
                        <span class="menu-icons"><svg xmlns="http://www.w3.org/2000/svg" width="45.294" height="46.873" viewBox="0 0 45.294 46.873">
                            <g id="Group_1196" data-name="Group 1196" transform="translate(-3695.338 1378.649)">
                                <path id="Path_1050" data-name="Path 1050" d="M179.351,86.153c.057,0,.11.008.166.009a7.785,7.785,0,0,0-2.721-1.7,31.224,31.224,0,0,1-6.754-2.911c0,.044,0,.088,0,.134-.012.6-.08,1.329-.172,2.085-.272,2.254-.779,4.755-.779,4.755a9.992,9.992,0,0,0-1.812-2.307,2.534,2.534,0,0,0-1.632-.756.929.929,0,0,0-.151.009,1.475,1.475,0,0,0-.949.494,23.338,23.338,0,0,0,4.074-6.922,6.033,6.033,0,0,1-.009-.717s-.015-.283-.048-.286c0,0,0,0-.006,0a11.48,11.48,0,0,0,1.981-3.236,6.739,6.739,0,0,0,1.173-2.658c.641-1.806.243-2.508-.06-2.768.466-1.512,1.48-5.777-.933-8.491,0,0-.924-2.045-4.642-2.787a3.943,3.943,0,0,1-2.007-2.085s-1.279.165-1.279,1.28l-.619-.247-.574.4-.664-.4a.512.512,0,0,0-.14-.014,2.028,2.028,0,0,0-.665.139s-4.836,1.859-6.076,5.7c0,0-.686,1.63.151,6.378-.2.081-1.1.576-.27,2.9a7.227,7.227,0,0,0,1.059,2.537,12.958,12.958,0,0,0,1.734,3.441s-.007-.006-.009-.009a3.646,3.646,0,0,1,.009,1.093,23.677,23.677,0,0,0,4,6.83,1.46,1.46,0,0,0-.792-.415,1.536,1.536,0,0,0-.25-.023,2.387,2.387,0,0,0-1.475.628,9.292,9.292,0,0,0-1.965,2.44s-.606-2.988-.853-5.374c-.059-.571-.1-1.112-.1-1.564,0-.09,0-.176,0-.258-2.126,1.547-6.861,2.987-6.861,2.987a7.083,7.083,0,0,0-3.057,2.065,24.058,24.058,0,0,0-3.5,10.156c0,2.195,9.286,3.972,20.739,3.972,3.407,0,6.619-.158,9.456-.438a8.915,8.915,0,0,1,7.287-14.071Z" transform="translate(3553.469 -1434.67)" fill="#9fcc47"/>
                                <path id="Path_1051" data-name="Path 1051" d="M447.414,369.6a7.812,7.812,0,1,0,7.812,7.812A7.82,7.82,0,0,0,447.414,369.6Zm1.08,12.835h-2.174v-7h2.174Zm-1.1-7.856a1.1,1.1,0,1,1,.015-2.188,1.1,1.1,0,1,1-.015,2.188Z" transform="translate(3285.405 -1717.001)" fill="#9fcc47"/>
                            </g>
                            </svg>  
                            </span>  
                            <span class="links_name">Benchmark Users</span>
                        </a>
                        <ul class="collapse {{ Route::is('admin.admins.create') || Route::is('admin.admins.index') || Route::is('admin.admins.edit') || Route::is('admin.admins.show') ? 'in' : '' }}">
                            
                            @if ($usr->can('admin.view'))
                                <li class="{{ Route::is('admin.admins.index')  || Route::is('admin.admins.edit') ? 'active' : '' }}">
                                    
                                    <a href="{{ route('admin.admins.index') }}">
                                        <span class="menu-icons"><i class="fa fa-tasks"></i></span>
                                        <span class="links_name">All Users</span>
                                    </a>
                                </li>
                            @endif
                            @if ($usr->can('admin.create'))
                                <li class="{{ Route::is('admin.admins.create')  ? 'active' : '' }}">
                                    <a href="{{ route('admin.admins.create') }}">
                                        <span class="menu-icons"><i class="fa fa-plus"></i></span>
                                        <span class="links_name"> Create User</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    <li class="{{ Route::is('admin.admins.manager')  ? 'active' : '' }}">
                        <a href="{{ route('admin.admins.manager') }}">
                            <span class="menu-icons">
                                <svg xmlns="http://www.w3.org/2000/svg" width="23.999" height="22" viewBox="0 0 23.999 22">
                                <g id="Group_1020" data-name="Group 1020" transform="translate(-109.13 -59.196)">
                                    <path id="Path_816" data-name="Path 816" d="M125.566,70.046a6.333,6.333,0,1,0-8.871,0A12,12,0,0,0,109.13,81.2h24a12,12,0,0,0-7.563-11.15Z" transform="translate(0 0)" fill="#666762"></path>
                                </g>
                                </svg>
                            </span>
                            <span class="links_name">Region Mangers</span>
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true">
                            <span class="menu-icons"><svg xmlns="http://www.w3.org/2000/svg" width="45.742" height="46.948" viewBox="0 0 45.742 46.948"><g class="customer" transform="translate(-2944.398 2203)"><path class="Path_1047" data-name="Path 1047" d="M180.966,274.262c.318-.088.643-.163.974-.219a9.649,9.649,0,0,1,1.043-.094,20.61,20.61,0,0,0-12.342-9.437,10.892,10.892,0,0,1-1.511.606h-.006a11.862,11.862,0,0,1-7.332,0,10.7,10.7,0,0,1-1.518-.606A21.1,21.1,0,0,0,144.929,285v.937h30.81a7.774,7.774,0,0,1-.387-.937,8,8,0,0,1-.262-.937,8.232,8.232,0,0,1-.187-1.768,8.369,8.369,0,0,1,6.065-8.032Z" transform="translate(2799.469 -2445.766)" fill="#9fcc47"></path><path class="Path_1048" data-name="Path 1048" d="M241.381,91.523a10.987,10.987,0,1,0-3.61-.606,10.928,10.928,0,0,0,3.61.606Z" transform="translate(2723.546 -2272.525)" fill="#9fcc47"></path><path class="Path_1049" data-name="Path 1049" d="M429.71,357.481c-.075-.006-.144-.006-.219-.006a6.854,6.854,0,0,0-.8.044,8.242,8.242,0,0,0-.981.169,7.424,7.424,0,0,0-5.427,8.968c.019.081.044.162.062.244q.065.244.15.468a7.417,7.417,0,1,0,7.214-9.886Zm.931,11.592h-1.6v-1.6h1.6Zm.063-6.4-.406,4.241h-.912l-.412-4.241V360.71h1.73Z" transform="translate(2553.235 -2528.361)" fill="#9fcc47"></path></g></svg>
                            </span>
                            <span class="links_name">Customers</span>
                        </a>
                        <ul class="collapse {{ Route::is('admin.users.create') || Route::is('admin.users.index') || Route::is('admin.users.edit') || Route::is('admin.users.show') ? 'in' : '' }}">
                            <li class="{{ Route::is('admin.users.index')  || Route::is('admin.users.edit') ? 'active' : '' }}">
                                <a href="{{ route('admin.users.index') }}"> 
                                    <span class="menu-icons"><i class="fa fa-tasks"></i></span>                        
                                    <span class="links_name">All Customers</span>
                                </a>
                            </li>
                            <li class="{{ Route::is('admin.users.create')  ? 'active' : '' }}">
                                <a href="{{ route('admin.users.create') }}">
                                    <span class="menu-icons"><i class="fa fa-plus"></i></span>                        
                                    <span class="links_name">Create Customers</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>                       
                        <a class="logout" href="{{ route('admin.logout.submit') }}"
                        onclick="event.preventDefault();document.getElementById('admin-logout-form').submit();">
                        <span class="menu-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20.833" height="22" viewBox="0 0 20.833 22">
                                <g id="Group_947" data-name="Group 947" transform="translate(-1200.784 -591)">
                                    <path id="Path_743" data-name="Path 743" d="M115.343,22.266l-4.13,3.422a.254.254,0,0,1-.272.036.243.243,0,0,1-.148-.225V23.9h-7.671a.5.5,0,0,1-.509-.5V20.746a.5.5,0,0,1,.509-.5h7.671V18.655a.243.243,0,0,1,.148-.225.255.255,0,0,1,.272.036l4.13,3.422a.245.245,0,0,1,0,.379ZM113.75,26.81a8.6,8.6,0,0,1-3.1,2.709,8.794,8.794,0,0,1-10.113-1.493,8.271,8.271,0,0,1,0-11.9,8.794,8.794,0,0,1,10.113-1.493,8.6,8.6,0,0,1,3.1,2.709,1.355,1.355,0,0,0,1.851.339,1.27,1.27,0,0,0,.348-1.791,11.25,11.25,0,0,0-4.058-3.541A11.5,11.5,0,0,0,98.648,14.3a10.808,10.808,0,0,0,0,15.552,11.5,11.5,0,0,0,13.243,1.95,11.247,11.247,0,0,0,4.058-3.541,1.27,1.27,0,0,0-.348-1.791,1.354,1.354,0,0,0-1.851.34Z" transform="translate(1105.438 579.924)" fill="#666762" fill-rule="evenodd"></path>
                                </g>
                            </svg></span>
                            <span class="links_name">logout</span>
                        </a>
                        <form id="admin-logout-form" action="{{ route('admin.logout.submit') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- sidebar menu area end -->