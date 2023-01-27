@extends('layouts.layoutfront')
@section('title', env('APP_NAME').' - About Us')
@section('content')
    <!-- BANNER SECTION BEGIN -->
    <section class="aboutus-banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="contact-banner-sec">
                        <h2 class="contact-banner-heading">About Us</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- BANNER SECTION END -->
    <!-- ABOUT DETAIL SECTION BEGIN -->
    <section class="about-detail-sec">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="balloon-girl-img">
                    <img src="{{asset("image/web/balloon-girl.png")}}" alt="image" class="img-fluid balloon-girl">
                </div>
                </div>
                <div class="col-md-6 col-sm-6 col-12">
                    <h2 class="about-detail-heading"><span class="about-detail-content">About Us</span>Prezziez is...</h2>
                    <p class="about-detail-text">a new universal wishlist gifting service! We are a US based company that aims to eliminate the boundaries set by traditional online wishlists. Prezziez offers a one-of-a-kind gifting and donating platform that makes gift giving easy and most importantly attainable! Our goal is to make every day gifting and specialty gifting hassle free, achievable, and limitless. We do this all without ever revealing your personal information. Ensuring your privacy is paramount to us. In this digital world it is the safe way for anyone to show their appreciation with the perfect gift. You wish it, we make it happen!</p>
                </div>
            </div>
        </div>
    </section>
    <!-- ABOUT DETAIL SECTION END -->
@endsection
<!-- FOOTER SECTION END -->