<!-- HEADER SECTION BEGIN -->
<header class="second-header" style="@yield('header_display')">
    <div class="container">
        <div class="row">
            <div class="col-md-5 col-sm-5 col-3">
                <nav class="navbar navbar-expand-md navbar-light">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent ">
                        <ul class="navbar-nav">
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
                                         <span class="header-search-loader" style="display: none">
                                            <img style="height: 20px; " src="{{asset('css/web/ajax-loader.gif')}}">
                                        </span>
                                        <a href="javascriptvoid:(0)" class="bar-color"><i class="fa fa-search"
                                                                                          aria-hidden="true"></i></a>
                                    </div>
                                    <div id="quicksearch-results" style="/* display: none; */">
                                        <div class="results" style="-webkit-overflow-scrolling: touch;">
                                            @include('frontend.search.search_list')
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="col-md-3 col-sm-3 col-7">
                <div class="logo">
                    <a href="{{url("")}}"><img src="{{asset('image/web/logo.png')}}" alt="image" class="img-fluid"></a>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-2">
                <div class="align-center-text">
                    <a href="{{url($auth_user_web->username)}}">
                        <div class="avatar-text">
                            <p class="avatar-main-text">{{$auth_user_web->displayName}}</p>
                            <div class="avatar-img">
                                @if($auth_user_web->profile_image!="")
                                    <img
                                        src="{{ asset("storage/uploads/profile_picture/".$auth_user_web->profile_image)}}"
                                        class="avatar img-circle img-thumbnail" alt="">
                                @else
                                    <img src="{{asset('image/web/placeholder.png')}}"
                                         class="avatar img-circle img-thumbnail"
                                         alt="avatar">
                                @endif
                            </div>
                        </div>
                    </a>
                    <div class="whislist-items notification-bar">
                        <ul class="whislist-items-main">
                            <li class="whislist-items-inner">
                                <div class="notification">
                                    <div class="notBtn">
                                        <a href="javascriptvoid:(0)">
                                            @if($notifCount = \App\Models\Notification::unreadUser($auth_user_web->id)->count())
                                                <div class="bell-text">
                                                    <span class="bell-text-inner">{{$notifCount}}</span>
                                                </div>
                                            @endif
                                            <i class="fa fa-bell" aria-hidden="true"></i>
                                        </a>
                                        <div class="box">
                                            <div class="display">
                                                <div class="cont">
                                                    <h4 class="notification-heading">Notifications
                                                        <a href="javascriptvoid:(0)" class="pull-right">
                                                            <label class="dropdown">
                                                                <div class="dd-button">
                                                                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                                                </div>
                                                                <input type="checkbox" class="dd-input" id="test">
                                                                <ul class="dd-menu">
                                                                    <li class="read_all_notifications"><i
                                                                            class="fa fa-check" aria-hidden="true"></i>
                                                                        Mark all as
                                                                        read
                                                                    </li>
                                                                    <li class="notification_setting"><i
                                                                            class="fa fa-cog" aria-hidden="true"></i>
                                                                        Notification
                                                                        settings
                                                                    </li>
                                                                </ul>
                                                            </label>
                                                        </a>
                                                    </h4>
                                                    <ul class="not-bar-list">
                                                        <li><a href="{{route('notifications')}}"> See all </a></li>
                                                        <li><a href="{{route('notifications')}}?show_unread=1">
                                                                Unread </a></li>
                                                    </ul>
                                                    @if($notifCount)
                                                        @foreach(\App\Models\Notification::latest()->unreadUser($auth_user_web->id)->take(10)->get() as $notif)
                                                            <div class="sec new">
                                                                <a href="javascript:;" class="notification_read"
                                                                   data-url="{{$notif->url}}" data-id="{{$notif->id}}"
                                                                   data-status="unread">
                                                                    <div class="noti-flex">
                                                                        <div class="noti-flex-img">
                                                                            @if($notif->from_user_id > 0)
                                                                                @php($profile_detail = \App\Models\Website::find($notif->from_user_id))
                                                                                @if(!empty($profile_detail) && $profile_detail->profile_image!="")
                                                                                    <img width="52" height="52"
                                                                                         src="{{ asset("storage/uploads/profile_picture/".$profile_detail->profile_image)}}"
                                                                                         class="img-fluid" alt="">
                                                                                @else
                                                                                    <img
                                                                                        src="{{asset('image/web/placeholder.png')}}"
                                                                                        width="52" height="52"
                                                                                        class="img-fluid"
                                                                                        alt="avatar">
                                                                                @endif
                                                                            @endif
                                                                        </div>
                                                                        <div class="noti-flex-content">
                                                                            <h5>{{$notif->title}}
                                                                            </h5>
                                                                            <p>{{\App\Helpers\Common::humanReadTime($notif->created_at,$auth_user_web->timezone)}}</p>
                                                                        </div>
                                                                        <div class="blue-online-dot"></div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="sec new">No new notifications</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="whislist-items-inner whislist-position">
                                <a href="{{route('cart')}}" class="whislist-items-text">
                                    <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                    <div class="bell-text">
                                        <span class="bell-text-inner">{{count((array) session('cart'))}}</span>
                                    </div>
                                </a>
                            </li>
                            <nav role='navigation'>
                                <div id="menuToggle">
                                    <input type="checkbox"/>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <ul id="menu">
                                        <div class="scrollbar" id="style-3">
                                            <div class="force-overflow">
                                                <a href="{{url($auth_user_web->username)}}">
                                                    <div class="jeff-profile avatar-img">
                                                        @if($auth_user_web->profile_image!="")
                                                            <img
                                                                src="{{ asset("storage/uploads/profile_picture/".$auth_user_web->profile_image)}}"
                                                                class="avatar img-thumbnail" alt="">
                                                        @else
                                                            <img src="{{asset('image/web/placeholder.png')}}"
                                                                 class="avatar img-thumbnail"
                                                                 alt="avatar">
                                                        @endif
                                                        <div class="jeff-profile-img">
                                                            <p class="jordan-text">{{$auth_user_web->displayName}}</p>
                                                        </div>
                                                    </div>
                                                </a>
                                                @include('layouts.headers.header_auth_menu')
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                            </nav>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- HEADER SECTION END -->
