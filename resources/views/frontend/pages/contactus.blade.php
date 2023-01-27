@extends('layouts.layoutfront')
@section('title', env('APP_NAME').' - Contact Us')
@section('content')
    <!-- BANNER SECTION BEGIN -->
    <section class="contactus-banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="contact-banner-sec">
                        <h2 class="contact-banner-heading">Contact us</h2>
                        <p class="contact-banner-text">Reliable customer service is really important to us! Prezziez aims to always promptly address your questions or concerns. How may we be of assistance?</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- BANNER SECTION END -->
    <!-- CONTACT DETAIL SECTION BEGIN -->
    <section class="contact-detail-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                    <h4 class="contact-detail-heading">Need help or have a question about the website?</h4>
                    <h6 class="contact-detail-text">Contact:</h6>
                    <a href="mailto:admin@prezziez.com" class="contact-detail-link">admin@prezziez.com</a>
                    <h4 class="contact-detail-heading">Have a question about an order?</h4>
                    <h6 class="contact-detail-text">Contact:</h6>
                    <a href="mailto:orders@prezziez.com" class="contact-detail-link">orders@prezziez.com</a>
                    <h4 class="contact-detail-heading">Have a question about refunds, recurring payments or other billing related topics?</h4>
                    <h6 class="contact-detail-text">Contact:</h6>
                    <a href="mailto:billing@prezziez.com" class="contact-detail-link">billing@prezziez.com</a>
                    <h4 class="contact-detail-heading">Want to make a request or complaint?</h4>
                    <h6 class="contact-detail-text">Contact:</h6>
                    <a href="mailto:lauren@prezziez.com" class="contact-detail-link">lauren@prezziez.com</a>
                </div>
            </div>
        </div>
    </section>
    <!-- CONTACT DETAIL SECTION END -->
@endsection
<!-- FOOTER SECTION END -->