@extends('layouts.layoutfront')
@section('content')
    <!-- BANNER SECTION BEGIN -->
    <section class="slider-banner">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="false">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item first-banner active">
                    <img class="d-block w-100 slide-banner-img" src="{{asset('image/web/banner1.jpg')}}" alt="banner">
                    <div class="carousel-caption d-md-block">
                        <h1 class="banner-heading wow" data-wow-duration="0.8s" data-wow-delay="0.8s">You wish it, <span class="banner-inner-heading">we make it happen!</span></h1>
                        <p class="banner-text wow" data-wow-duration="0.8s" data-wow-delay="0.8s">No waitlists, no referral codes, join now to start receiving your prezziez!</p>
                        <div class="background-id">
                            <a href="{{$auth_user_web ? route('profileUrl',$auth_user_web->username) : route('register_page')}}" class="banner-buttn wow" data-wow-duration="0.8s" data-wow-delay="0.8s">
                                Sign Up
                                <div><span>Sign Up</span></div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item second-banner">
                    <img class="d-block w-100 slide-banner-img" src="{{asset('image/web/banner2.jpg')}}" alt="banner">
                    <div class="carousel-caption d-md-block">
                        <h1 class="banner-heading wow" data-wow-duration="0.8s" data-wow-delay="0.8s">Have a special event, activity or <span class="banner-inner-heading">item you’d like some help with?</span></h1>
                        <p class="banner-text wow" data-wow-duration="0.8s" data-wow-delay="0.8s">
                            Prezziez is your go to gifting platform that makes the gift giving process seamless and easy! Our unique group gifting feature allows friends and family to contribute towards:
                        </p>
                        <div class="logo-imgs wow" data-wow-duration="0.8s" data-wow-delay="0.8s">
                            <ul class="logo-img-main">
                                <a href="{{$auth_user_web ? route('profileUrl',$auth_user_web->username)."?show_wish=1" : route('login_page')}}">
                                    <li class="logo-img-inner"><img src="{{asset('image/web/b1.png')}}"><span class="gift-activity">Gifts</span></li>
                                </a>
                                <a href="{{$auth_user_web ? route('profileUrl',$auth_user_web->username)."?show_wish=1" : route('login_page')}}">
                                    <li class="logo-img-inner"><img src="{{asset('image/web/b2.png')}}"><span class="gift-activity">Vacations</span></li>
                                </a>
                                <a href="{{$auth_user_web ? route('profileUrl',$auth_user_web->username)."?show_wish=1" : route('login_page')}}">
                                    <li class="logo-img-inner"><img src="{{asset('image/web/b3.png')}}"><span class="gift-activity">Activities</span></li>
                                </a>
                                <a href="{{$auth_user_web ? route('profileUrl',$auth_user_web->username)."?show_wish=1" : route('login_page')}}">
                                    <li class="logo-img-inner"><img src="{{asset('image/web/b4.png')}}"><span class="gift-activity">Life Events</span></li>
                                </a>
                                {{--<a href="javascriptvoid:(0)">
                                    <li class="logo-img-inner"><img src="{{asset('image/web/b5.png')}}"><span class="gift-activity">Expenses</span></li>
                                </a>--}}
                                <a href="{{$auth_user_web ? route('profileUrl',$auth_user_web->username)."?show_wish=1" : route('login_page')}}">
                                    <li class="logo-img-inner"><img src="{{asset('image/web/b6.png')}}"><span class="gift-activity"></span></li>
                                </a>
                            </ul>
                        </div>
                        <span class="special-moment-text wow" data-wow-duration="0.8s" data-wow-delay="0.8s">We help make life’s special moments attainable</span>
                    </div>
                </div>
                <div class="carousel-item third-banner">
                    <img class="d-block w-100 slide-banner-img" src="{{asset('image/web/banner3.png')}}" alt="banner">
                    <div class="carousel-caption d-md-block">
                        <h1 class="banner-heading wow" data-wow-duration="0.8s" data-wow-delay="0.8s">Not sure what you want?</h1>
                        <p class="banner-text wow" data-wow-duration="0.8s" data-wow-delay="0.8s">
                            <span class="curated-text fadeInLeft" data-wow-duration="0.8s" data-wow-delay="0.8s">
                                Our curated list of gift guides are perfect for everyone!
                            </span>
                        </p>
                        <div class="background-id">
                            <a href="{{route('giftideas')}}" class="banner-buttn wow">
                                Gift Guide
                                <div><span>Gift Guide</span></div>
                            </a>
                        </div>
                        <span class="curated-text fadeInLeft" data-wow-duration="0.8s" data-wow-delay="0.8s">Our gift guides are handpicked and tailored specifically for:</span>
                        <div class="tailored-imgs">
                            <ul class="tailored-spec">
                                <div class="button_slide slide_right wow" data-wow-duration="0.8s" data-wow-delay="0.8s">
                                    <a href="{{route('giftideas')}}?category=for-her">
                                        <li class="tailored-spec-text"><img src="{{asset('image/web/female.png')}}" alt="image" class="img-fluid"><span class="techies">For Her</span></li>
                                    </a>
                                </div>
                                <div class="button_slide slide_right wow" data-wow-duration="0.8s" data-wow-delay="0.8s">
                                    <a href="{{route('giftideas')}}?category=for-him">
                                        <li class="tailored-spec-text"><img src="{{asset('image/web/c2.png')}}" alt="image" class="img-fluid"><span class="techies">For Him</span></li>
                                    </a>
                                </div>
                                <div class="button_slide slide_right wow" data-wow-duration="0.8s" data-wow-delay="0.8s">
                                    <a href="{{route('giftideas')}}?category=for-techies">
                                        <li class="tailored-spec-text"><img src="{{asset('image/web/c3.png')}}" alt="image" class="img-fluid"><span class="techies">For Techies</span></li>
                                    </a>
                                </div>
                                <div class="button_slide slide_right wow" data-wow-duration="0.8s" data-wow-delay="0.8s">
                                    <a href="{{route('giftideas')}}?category=for-children">
                                        <li class="tailored-spec-text"><img src="{{asset('image/web/c4.png')}}" alt="image" class="img-fluid"><span class="techies">For Kids</span></li>
                                    </a>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- BANNER SECTION END -->
    <!-- PREZZEIZ SECTION BEGIN -->
    <section class="prezzeiz-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-12">
                    <img src="{{asset('image/web/sec-img.png')}}" alt="image" class="img-fluid animation-img">
                </div>
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="col-padd">
                        <h2 class="prezzeiz-heading wow fadeInRight" data-wow-duration="0.8s" data-wow-delay="0.8s"><span class="prezzeiz-inner-heading">What is Prezziez ? </span>Prezziez is…</h2>
                        <p class="prezzeiz-text wow fadeInRight" data-wow-duration="0.8s" data-wow-delay="0.8s">The all new universal Wishlist gifting service that delivers the gifts and experiences of your dreams seamlessly all while ensuring your privacy.</p>
                        <div class="background-id">
                            <a href="{{ route('about-us') }}" class="banner-buttn wow" data-wow-duration="0.8s" data-wow-delay="0.8s">
                                Learn More
                                <div><span>Learn More</span></div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- PREZZEIZ SECTION END -->
    <!-- HOW IT WORKS SECTION BEGIN -->
    <section class="how-it-works-sec">
        <!-- <canvas id="canv"></canvas> -->
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-sm-7 col-12">
                    <h2 class="how-it-work-heading fadeInLeft" data-wow-duration="0.8s" data-wow-delay="0.8s">How it works</h2>
                    <div id="thumb_img" class="cf">
              <h6 class="active wow fadeInDown" data-wow-duration="0.8s" data-wow-delay="0.8s" onclick="changeimg('{{asset("image/web/w1.png")}}',this);">Step 1<span>Sign up and create your wishlist! Create wishlists and registries by pasting the URL of the desired item. Big ticket items are eligible for friends and families to pitch in!</span></h6>
              <h6 class="wow fadeInLeft" data-wow-duration="0.8s" data-wow-delay="0.8s" onclick="changeimg('{{asset("image/web/w2.png")}}',this);">Step 2 <span>Customize your wishlists and registries to your liking and share! Those who you have shared your wishlist with are able to purchase gifts for you.</span></h6>
              <h6 class="wow fadeInUp" data-wow-duration="0.8s" data-wow-delay="0.8s" onclick="changeimg('{{asset("image/web/w3.png")}}',this);">Step 3 <span>Prezziez handles it from there! Sit back and wait for your prezziez to arrive! We’ll keep you updated on your order’s progress. Have questions? <a href="{{route('faq')}}" class="learn-more-text">Learn More </a>here.</span></h6>
                    </div>
                </div>
                <div class="col-md-5 col-sm-5 col-12">
                    <div id="featured_img">
                        <img id="img" src="{{asset("image/web/w1.png")}}" class="wow fadeInRight" data-wow-duration="0.8s" data-wow-delay="0.8s" data-animation-in="fadeInLeft" data-animation-out="bounceOut" style="display: block;">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- HOW IT WORKS SECTION END -->
@endsection
<!-- FOOTER SECTION END -->
