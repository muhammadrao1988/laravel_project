<!-- MOBILE HEADER BEGIN -->
<header class="mobile-view-one" style="@yield('header_display')">
    <div class="container">
        <div class="row-wrap">
            <div class="logo-wrap">
                <a href="{{url("")}}"><img src="{{asset('image/web/logo.png')}}" alt="image" class="img-fluid"></a>
            </div>
            <div class="whislist-items notification-bar">
                <ul class="whislist-items-main">
                    <li class="whislist-items-inner d-block float-left" >
                        <div class="notification">
                            <div class="notBtn">
                                <a href="{{route('notifications')}}?show_unread=1">
                                    @if($notifCount = \App\Models\Notification::unreadUser($auth_user_web->id)->count())
                                        <div class="bell-text">
                                            <span class="bell-text-inner">{{$notifCount}}</span>
                                        </div>
                                    @endif
                                    <i class="fa fa-bell" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="whislist-items-inner whislist-position d-block float-left">
                        <a href="{{route('cart')}}" class="whislist-items-text">
                            <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                            <div class="bell-text"><span class="bell-text-inner">{{count((array) session('cart'))}}</span></div>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="nav-wrap">
                <div class="scrollbar" id="style-3">
                    <div class="force-overflow">
                        <ul class="nav-list">
                            <a href="{{url($auth_user_web->username)}}">
                                <div class="jeff-profile avatar-img">
                                    @if($auth_user_web->profile_image!="")
                                        <img src="{{ asset("storage/uploads/profile_picture/".$auth_user_web->profile_image)}}"
                                             class="avatar img-thumbnail" alt="">
                                    @else
                                        <img src="{{asset('image/web/placeholder.png')}}"
                                             class="avatar  img-thumbnail"
                                             alt="avatar">
                                    @endif
                                    <div class="jeff-profile-img">
                                        <p class="jordan-text">{{$auth_user_web->displayName}}</p>
                                    </div>
                                </div>
                            </a>
                            <li class="nav-item gift-spacing">
                                <a class="nav-link" href="{{route('giftideas')}}">Gift Guide</a>
                            </li>
                            <li class="nav-item">
                                {{--<a class="nav-link" href="javascriptvoid:(0)">Help</a>--}}
                                <a class="nav-link" href="{{route('howitworks')}}">How it works</a>
                            </li>
                            <li>
                                <div class="search-box">
                                    <input type="text" class="searchbox-field">
                                    <div class="search-box-icon">
                                        <span class="header-search-loader" style=" display: none"><img style="height: 20px; " src="{{asset('css/web/ajax-loader.gif')}}"></span>
                                        <a href="javascriptvoid:(0)" class="bar-color"><i class="fa fa-search"
                                                                                          aria-hidden="true"></i></a>
                                    </div>
                                    <div id="quicksearch-results" >
                                        <div class="results" style="-webkit-overflow-scrolling: touch;">
                                            @include('frontend.search.search_list')
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul id="menu">
                            @include('layouts.headers.header_auth_menu')
                        </ul>
                    </div>
                </div>
            </div>
            <div class="hamburger">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </div>
        </div>
    </div>
</header>
<!-- MOBILE HEADER END -->
