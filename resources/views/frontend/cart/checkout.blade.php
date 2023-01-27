@extends('layouts.layoutfront')
@section('title', 'Cart')
@section('content')

    <!-- SHOPPING CART SECTION BEGIN -->
    <section class="shopping-cart-sec checkout_section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-12 col-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabs-1" id="billing-select" role="tab">BILLING INFO</a>
                        </li>
                        <!-- <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">SHIPPING INFO</a>
                          </li> -->
                        <li class="nav-item">
                            <a class="nav-link disabled" data-toggle="tab" href="#tabs-3" role="tab" id="payment-select">PAYMENT SELECTION</a>
                        </li>
                        @if(empty($contributed_checkout))
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tabs-4" role="tab" id="cart-select">CART ITEMS</a>
                            </li>
                        @endif
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            @include('frontend.cart.billing_form')
                        </div>

                        <div class="tab-pane" id="tabs-3" role="tabpanel">
                            @include('frontend.cart.payment_form')
                        </div>
                        @if(empty($contributed_checkout))
                        <div class="tab-pane" id="tabs-4" role="tabpanel">
                            @foreach (session('cart') as $id => $details)
                                <div class="qty-box">

                                    @if($details['picture']=="")
                                        <img src="{{ asset('image/web/upload-pre.png')}}"  alt="image" class="img-fluid">
                                    @else
                                        <img src="{{ asset("storage/uploads/wishlist_item/".$details['picture'])}}"  alt="image" class="img-fluid">
                                    @endif
                                    <div class="qty-box-main">
                                        <h5 class="qty-box-heading">
                                            <span class="qty-box-price">
                                                ${{ \App\Helpers\Common::numberFormat($details['unit_price'])  }}
                                            </span>{{$details['name']}}</h5>
                                        <p class="radio-img-heading"><b>Qty: {{$details['quantity']}}</b></p>
                                    </div>
                                </div>
                             @endforeach
                                <div class="row shopping-row">
                                    <div class="col-md-6 col-sm-6 col-12">
                                        <a href="{{route('cart')}}" class="continue-shopping"><i class="fa fa-arrow-left back-arrow" aria-hidden="true"></i> Edit Cart</a>
                                    </div>
                                </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 col-12">
                    <div class="cart-rightside-box">
                        @if(empty($contributed_checkout))
                            @include('frontend.cart.order_summary')
                        @else
                            @include('frontend.contributed.order_summary')
                        @endif
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
    @if($auth_user_web && !empty($contributed_checkout))
        <script>
            $("#use_prezziez_credit").click(function(){
                if(!$(this).is(':checked')){
                    var use_credit = "no";
                }else{
                    var use_credit = "yes";
                }
                $.ajax({
                    type:"get",
                    url:"{{route('contributed-checkout')}}?use_credit="+use_credit,

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
    @if(!empty($contributed_checkout))
        <script>
            $(".contribute_update").click(function () {
                var amount = $(".contributed_amount_update").val();
                if(amount==""){
                    toastr.error("Please add amount", "Error");
                    return false;
                }
                var wishlist_id = $(".checkout_section .wishlist_id").val();
                var data2= "wishlist_item_id="+wishlist_id+"&amount="+amount;
                $(".contribute_update").attr('disabled',true);
                $(".loading").show();
                $.ajax({
                    url: "{{route('contributed-cart-add')}}",
                    dataType: "json",
                    type: "GET",
                    data: data2
                }).then(function (data) {
                    location.reload();

                }).fail(function (error) {
                    $(".loading").hide();
                    $(".contribute_update").removeAttr('disabled');
                    //$("#result").text(error.responseJSON.message);
                    if (error.responseJSON.hasOwnProperty('errors')) {
                        var error_msg = "";

                        for (var prop in error.responseJSON.errors) {

                            $(error.responseJSON.errors[prop]).each(function (val, msg) {

                                toastr.error(msg, "Error");
                            });
                        }
                        $(".contributed_amount_update").val(amount);

                    } else {
                        toastr.error(error.responseJSON.message, "Error");
                    }

                });
            })
        </script>
    @endif
<script async
        src="https://pay.google.com/gp/p/js/pay.js"
        onload="onGooglePayLoaded()"></script>
@endsection
