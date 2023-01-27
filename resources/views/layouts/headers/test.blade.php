<header class="header-pos" style="@yield('header_display')">
    <div class="container">
        <div class="row">
            <div class="col-md-5 col-sm-5 col-3">
                <nav class="navbar navbar-expand-md navbar-light">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse collapse-new" id="navbarSupportedContent">
                        <ul class="navbar-nav">
                            <li class="nav-item gift-spacing">
                                <a class="nav-link" href="javascriptvoid:(0)">Gift Ideas</a>
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
                            <li class="mobile-cls">
                                <div class="login-signup-buttns">
                                    <div class="background-id">
                                        <a href="{{route('register')}}" class="join-now">
                                            Join Now
                                            <div class="color-change"><span>Join Now</span></div>
                                        </a>
                                    </div>
                                    <div class="background-id">
                                        <a href="{{route('login')}}" class="login">
                                            Login
                                            <div><span>Login</span></div>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="col-md-3 col-sm-3 col-9">
                <div class="logo">
                    <a href="{{url('')}}"><img src="{{asset('image/web/logo.png')}}" alt="image" class="img-fluid"></a>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-12">
                <div class="login-signup-buttns login-inner-buttons">
                    <div class="background-id">
                        <a href="{{route('register')}}" class="join-now">
                            Join Now
                            <div class="color-change"><span>Join Now</span></div>
                        </a>
                    </div>
                    <div class="background-id">
                        <a href="{{route('login')}}" class="login">
                            Login
                            <div><span>Login</span></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse new-bg-color">
            <ul class="navbar-nav">
                <li class="nav-item gift-spacing">
                    <a class="nav-link" href="javascriptvoid:(0)">Gift Ideas</a>
                </li>
                <li class="nav-item">
                    {{--<a class="nav-link" href="javascriptvoid:(0)">Help</a>--}}
                    <a class="nav-link" href="{{route('howitworks')}}">How it works</a>
                </li>
                <li>
                    <div class="search-box">
                        <input type="text" class="searchbox-field">
                        <div class="search-box-icon">
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
                <li class="mobile-cls">
                    <div class="login-signup-buttns">
                        <div>
                            <a href="{{route('register')}}" class="join-now">
                                Join Now
                                <div class="color-change"><span>Join Now</span></div>
                            </a>
                        </div>
                        <div class="background-id">
                            <a href="{{route('login')}}" class="login">
                                Login
                                <div><span>Login</span></div>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    </div>
    </div>
</header>
