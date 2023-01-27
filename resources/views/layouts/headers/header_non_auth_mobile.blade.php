<!-- MOBILE HEADER BEGIN -->
<header class="mobile-view" style="">
    <div class="container">
        <div class="row-wrap">
            <div class="logo-wrap">
                <a href="{{url('')}}"><img src="{{asset('image/web/logo.png')}}" alt="image" class="img-fluid"></a>
            </div>
            <div class="nav-wrap">
                <ul class="nav-list">
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
                    <li>
                        <div class="login-signup-buttns login-inner-buttons">
                            <div id="background">
                                <a href="{{route('register')}}" class="join-now">
                                    Join Now
                                    <div class="color-change"><span>Join Now</span></div>
                                </a>
                            </div>
                            <div id="background">
                                <a href="{{route('login')}}" class="login">
                                    Login
                                    <div><span>Login</span></div>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
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
