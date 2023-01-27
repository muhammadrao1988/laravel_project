@extends('layouts.layoutfront')
@section('title', 'Cart')
@section('content')

    <!-- SHOPPING CART SECTION BEGIN -->
    <section class="shopping-cart-sec checkout_section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabs-1" id="billing-select" role="tab">BILLING INFO</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" data-toggle="tab" href="#tabs-3" role="tab" id="payment-select">PAYMENT SELECTION</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            @include('frontend.cart.billing_form')
                        </div>

                        <div class="tab-pane" id="tabs-3" role="tabpanel">
                            @include('frontend.cart.payment_form')
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="cart-rightside-box">
                        @include('frontend.gift_offer.order_summary')
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('frontend.cart.estimated_info_modal')
    <div class="loading" style="display: none">
        <div class="loader"></div>
        <div class="loaderoverlay"></div>
    </div>
    <!-- SHOPPING CART SECTION END -->
@endsection
@section('scripts')
    @include('frontend.order.google_pay_js')
    @include('frontend.order.checkout_handling_js')
    @if($auth_user_web)
        <script>
            $("#use_prezziez_credit").click(function(){
                if(!$(this).is(':checked')){
                    var use_credit = "no";
                }else{
                    var use_credit = "yes";
                }
                $.ajax({
                    type:"get",
                    url:"{{route('gift-offer-checkout')}}?use_credit="+use_credit,

                    success:function(response){
                        location.reload()
                    },
                    error:function(error){
                        location.reload();
                    }
                });

            });
        </script>
    @endif
    <script async
        src="https://pay.google.com/gp/p/js/pay.js"
        onload="onGooglePayLoaded()"></script>
@endsection
