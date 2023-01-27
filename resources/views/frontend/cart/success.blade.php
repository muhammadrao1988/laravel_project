@extends('layouts.layoutfront')
@section('title', 'success')
@section('body_class','thanks-banner')
@section('content')
  <!-- PROFILE BANNER SECTION BEGIN -->
  <section class="profile-banner-sec no-banner">
    <div class="container">
      <div class="row">
        <div class="col-md-12 col-sm-12 col-12">
        </div>
      </div>
    </div>
  </section>
  <!-- PROFILE BANNER SECTION END -->
  <!-- GIFT PACK SECTION BEGIN -->
  <section class="gift-pack-sec">
      <div class="container">
          <div class="row">
              <div class="col-md-12 col-sm-12 col-12">
                  <div class="gift-pack-sec-inner">
                      <!-- <img src="images/gift-box.gif" alt="image" class="img-fluid"> -->
                      @if($is_contribute)
                          <h2 class="thanks-heading">Thank you for your contribution!</h2>
                          <p class="thanks-text">We’ll let the lucky giftee know that a new contribution towards their Prezzie has just been made. Your confirmation email will arrive shortly.</p>
                      @else
                        <h2 class="thanks-heading">Congrats on your purchase, time to celebrate!</h2>
                        <p class="thanks-text">We’ll let the lucky giftee know that their Prezziez are on the way! Your order Confirmation email will arrive shortly.</p>
                      @endif
                      <a href="{{$url}}" class="continue-shopping"><i class="fa fa-arrow-left back-shipping" aria-hidden="true"></i> {{$button_text}}</a>
                  </div>
              </div>
          </div>
      </div>
  </section>
@endsection
  <!-- GIFT PACK SECTION END -->
