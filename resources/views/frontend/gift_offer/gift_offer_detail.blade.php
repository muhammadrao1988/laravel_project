@extends('layouts.layoutfront')
@section('css')
    <style>
        .shopping-cart-sec { font-family: 'Poppins', sans-serif;}
    </style>
@endsection
@section('content')
    <!-- SHOPPING CART SECTION BEGIN -->
    <section class="shopping-cart-sec">
        <div class="container">
            <div class="row">
                @if (session('success_gift_offer'))
                    <div class="col-md-12 alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                            ×
                        </button>
                        <strong>Success: </strong> {!! session('success_gift_offer') !!}
                    </div>
                @endif
                <div class="col-md-6 col-sm-6 col-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">MANAGE OFFER</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    @php
                        $status = strtolower($contributions[0]['item_status'])
                    @endphp
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            @if($show_return_form)
                                <div class="row">
                                    <div class="col-md-12 mt-5">
                                        <form method="POST" id="offer_returned_form">
                                            @csrf
                                            <input type="hidden" name="id"
                                                   value="{{\App\Helpers\Common::encrypt_decrypt($gift_offer->id)}}">
                                            <h6 class="customer-info-text mb-3 mt-0">Return Order?</h6>
                                            <p style="font-size: 14px">You have {{$return_day}} days left to return order. </p>
                                            <div class="gift-box-content d-block">
                                                <div>
                                                    <p style="font-size: 16px">Please enter brief description for return</p>
                                                    <textarea class="form-control" id="return_description"
                                                              name="status_description"
                                                              placeholder="Please enter detail for return order"></textarea>
                                                </div>
                                                <div class="mt-3">
                                                    <button type="button" class="d-block btn btn-sm btn-outline-success return_item">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                            @if($gift_offer->return_description_giftee!="")
                                <div class="row">
                                    <div class="col-md-12 mt-5">
                                            <h6 class="customer-info-text mb-3 mt-0">Return Order</h6>
                                            <div class="gift-box-content d-block">
                                                <div>
                                                    <div>Your Message:</div>
                                                    <p style="font-size: 16px">{{$gift_offer->return_description_giftee}}</p>
                                                </div>
                                                @if($gift_offer->return_description_admin!="")
                                                    <div>Admin Reply:</div>
                                                    <p style="font-size: 16px">{{$gift_offer->return_description_admin}}</p>
                                                @endif
                                            </div>
                                    </div>
                                </div>
                            @endif
                            @if($status=="delivered")
                                <div class="row">
                                    <div class="col-md-12 mt-5">
                                        <form method="POST" id="offer_received_form">
                                            @csrf
                                            <input type="hidden" name="id"
                                                   value="{{\App\Helpers\Common::encrypt_decrypt($gift_offer->id)}}">
                                            <input type="hidden" name="step" value="7">
                                            <input type="hidden" name="status" value="Received">
                                            <h6 class="customer-info-text mb-3 mt-0">Action Required</h6>
                                            <p style="font-size: 16px">Please confirm you have received the gift.</p>
                                            <div class="gift-box-content d-block">
                                                <div>
                                                    <p style="font-size: 16px">Enter your message to gifter</p>
                                                    <textarea class="form-control" id="status_description"
                                                              name="status_description"
                                                              placeholder="Please enter thank you message for gifter"></textarea>
                                                </div>
                                                <div class="mt-3">
                                                    <button type="button" class="d-block btn btn-sm btn-outline-success change_status">Yes, I have received this item</button>
                                                    <br>
                                                    <a href="{{route('contact-us')}}" target="_blank"
                                                            class="btn btn-sm btn-outline-danger"
                                                            data-action="Declined">No, I have not received this item.
                                                        Contact admin
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif

                                <div class="row">
                                <div class="col-md-12 col-sm-12 col-12">
                                    <h6 class="customer-info-text mb-3">Offer By</h6>
                                    @foreach($contributions as $contribution)
                                        <div class="gift-box-content">
                                            @if(!empty($contribution['order']['from_user']))
                                                @if(!empty($contribution['order']['from_user']['profile_image']))
                                                    <img width="99" height="99"
                                                         src="{{ asset("storage/uploads/profile_picture/".$contribution['order']['from_user']['profile_image'])}}"
                                                         class="img-fluid" alt="">
                                                @else
                                                    <img width="99" height="99"
                                                         src="{{asset('image/web/placeholder.png')}}" class="img-fluid"
                                                         alt="avatar">
                                                @endif
                                                <div class="gift-box-inner-content">
                                                    <h6 class="gift-box-inner-content-heading">{{$contribution['order']['from_user']['displayName']}}</h6>
                                                    <a class="font-italic" target="_blank"
                                                       href="{{route('profileUrl',$contribution['order']['from_user']['username'])}}">View
                                                        Profile</a>
                                                </div>
                                            @else
                                                <img width="99" height="99" src="{{asset('image/web/placeholder.png')}}"
                                                     class="img-fluid"
                                                     alt="avatar">
                                                <div class="gift-box-inner-content">
                                                    <h6 class="gift-box-inner-content-heading">
                                                        {{$contribution['order']['billing_info']['first_name']}} {{$contribution['order']['billing_info']['last_name']}}
                                                    </h6>
                                                </div>
                                            @endif

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @if(!empty($gift_offer->order->note))
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="customer-info-text mb-3 mt-0"> Message from Gifter:</h6>
                                        <div class="gift-box-content">

                                            <p class=" btn-outline-info">{{$gift_offer->order->note}}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(!empty($gift_offer->giftee_item_specification))
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="customer-info-text mb-3 mt-0"> Your Specification:</h6>
                                        <div class="gift-box-content">

                                            <p class=" btn-outline-info">{{$gift_offer->giftee_item_specification}}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($status=="pending")
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="POST" id="offer_confirmation_form">
                                            @csrf
                                            <input type="hidden" name="id"
                                                   value="{{\App\Helpers\Common::encrypt_decrypt($gift_offer->id)}}">
                                            <h6 class="customer-info-text mb-3 mt-0">Accept/Reject Offer</h6>
                                            <div class="gift-box-content d-block">
                                                <div>
                                                    Enter your message to gifter (optional)
                                                    <textarea class="form-control" id="gifter_message"
                                                              name="gifter_message"
                                                              placeholder="Please enter thank you message for acceptance or reason for rejection"></textarea>
                                                </div>
                                                <div class="mt-3">
                                                    Enter details if you have any changes or variation in gift
                                                    (optional)
                                                    <textarea class="form-control" id="details_specification"
                                                              name="details_specification"
                                                              placeholder="Please enter your message"></textarea>
                                                </div>
                                                <div class="mt-3">
                                                    <button type="button" class="btn btn-sm btn-outline-success mark_as"
                                                            data-action="Accepted">Accept Offer
                                                    </button>
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-danger ml-3 mark_as"
                                                            data-action="Declined">Reject Offer
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            @elseif($status=="declined")
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="gift-box-content">
                                            <button class="btn btn-lg btn-outline-danger">You have rejected gift
                                                offer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <h6 class="customer-info-text mb-3 mt-0">Offer Status Timeline</h6>
                            @foreach($order_timeline as $timeline)

                                <div class="card text-left" style="min-height: auto;">
                                    <div class="card-body">
                                        {{$timeline->status}}
                                        -> {{\App\Helpers\Common::CTL($timeline->created_at,true)}}<br>
                                        @if($timeline->status=="Shipped")
                                            <a class="shortern_url text-info" href="{{$timeline->description}}" target="_blank">{{$timeline->description}}</a>
                                        @else
                                            {{$timeline->description}}
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <div class="row shopping-row">
                                <div class="col-md-6 col-sm-6 col-12">
                                    <a href="{{route('profileUrl',$auth_user_web->username)}}" class="return-cart"><i
                                                class="fa fa-arrow-left back-arrow" aria-hidden="true"></i> Return to
                                        Gifted Items</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-12">
                    @php

                            @endphp
                    <div class="cart-rightside-box">
                        <h5 class="shopping-amount">Offer Detail # {{$gift_offer->order->id}}</h5>
                        <div class="qty-box">
                            @if($contributions[0]['item_image']=="")
                                <img width="99" height="99" src="{{ asset('image/web/upload-pre.png')}}" alt="image"
                                     class="img-fluid">
                            @else
                                <img width="99" height="99"
                                     src="{{ asset("storage/uploads/offer_gift/".$contributions[0]['item_image'])}}"
                                     alt="image" class="img-fluid">
                            @endif
                            <div class="qty-box-main" style="width: 100%">
                                <h5 class="qty-box-heading">
                                    <span class="qty-box-price">${{\App\Helpers\Common::numberFormat($contributions[0]['item_price'])}}</span>
                                    {{$contributions[0]['item_name']}}<br><br>
                                    Qty: {{$contributions[0]['item_qty']}}<br>
                                    Shipping:
                                    ${{\App\Helpers\Common::numberFormat($contributions[0]['item_shipping_price'])}}<br>
                                </h5>
                            </div>
                        </div>
                        <h6 class="summary-box-heading pt-3">Options</h6>
                        <ul>
                            <li class="summary-box-list">Created At:<br>
                                <strong>{{\App\Helpers\Common::CTL($contributions[0]['created_at'],true)}}</strong></li>
                            <li class="summary-box-list">Merchant:<br>
                                <strong>{{$contributions[0]['item_merchant']}}</strong></li>
                            <li class="summary-box-list">Digital Purchase:<br>
                                <strong>{{$contributions[0]['item_digital_purchase']== 1? "Yes" : "No"}}</strong></li>
                            <li class="summary-box-list">URL:<br>
                                <a class="shortern_url" href="{{$contributions[0]['item_url']}}" target="_blank">{{$contributions[0]['item_url']}}</a></li>
                            </li>
                            <li class="summary-box-list">Details:<br>
                                <strong>{{$contributions[0]['item_detail']}}</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="loading" style="display: none">
        <div class="loader"></div>
        <div class="loaderoverlay"></div>
    </div>
    <!-- SHOPPING CART SECTION END -->
@endsection
@section('scripts')
    <script src="{{ asset('js/helper.js')}}"></script>
    @if($status=="pending")
        <script>
            $(".mark_as").click(function () {
                var status = $(this).attr('data-action');
                if (confirm("Do you really want to " + status + " this offer?")) {
                    $(".mark_as").attr('disabled', true);

                    //$("#result").text("Please wait, we are processing..");
                    var data2 = $('#offer_confirmation_form').serialize();
                    data2 += "&accpeted_status=" + status;
                    $(".loading").show();
                    $.ajax({
                        url: "{{route('giftOfferConfirmation')}}",
                        dataType: "json",
                        type: "POST",
                        data: data2
                    }).then(function (data) {
                        $(".loading").hide();
                        location.reload();

                    }).fail(function (error) {
                        $(".loading").hide();
                        $(".mark_as").removeAttr('disabled');
                        //$("#result").text(error.responseJSON.message);
                        if (error.responseJSON.hasOwnProperty('errors')) {
                            var error_msg = "";

                            for (var prop in error.responseJSON.errors) {

                                $(error.responseJSON.errors[prop]).each(function (val, msg) {
                                    toastr.error(msg, "Error");
                                });
                            }
                            //$("#result").text("");

                        } else {
                            toastr.error(error.responseJSON.message, "Error");
                            //$("#result").text("");
                        }

                    });
                }
            })
        </script>
    @endif
    @if($status=="delivered")
        <script>
            $(".change_status").click(function () {
                if($("#status_description").val()==""){
                    alert("Please enter message");
                    $("#status_description").focus();
                    return false;
                }
                var status = $(this).attr('data-action');
                if (confirm("Are you sure you have received the gift?")) {
                    $(".change_status").attr('disabled', true);

                    //$("#result").text("Please wait, we are processing..");
                    var data2 = $('#offer_received_form').serialize();
                    $(".loading").show();
                    $.ajax({
                        url: "{{route('manageGiftOfferReceivedStatus')}}",
                        dataType: "json",
                        type: "POST",
                        data: data2
                    }).then(function (data) {
                        $(".loading").hide();
                        location.reload();

                    }).fail(function (error) {
                        $(".loading").hide();
                        $(".change_status").removeAttr('disabled');
                        //$("#result").text(error.responseJSON.message);
                        if (error.responseJSON.hasOwnProperty('errors')) {
                            var error_msg = "";

                            for (var prop in error.responseJSON.errors) {

                                $(error.responseJSON.errors[prop]).each(function (val, msg) {
                                    toastr.error(msg, "Error");
                                });
                            }
                            //$("#result").text("");

                        } else {
                            toastr.error(error.responseJSON.message, "Error");
                            //$("#result").text("");
                        }

                    });
                }
            })
        </script>
    @endif
    @if($show_return_form)
        <script>
            $(".return_item").click(function () {
                if($("#return_description").val()==""){
                    alert("Please enter message");
                    $("#return_description").focus();
                    return false;
                }
                if (confirm("Do you really want to return order?")) {
                    $(".return_item").attr('disabled', true);

                    //$("#result").text("Please wait, we are processing..");
                    var data2 = $('#offer_returned_form').serialize();
                    $(".loading").show();
                    $.ajax({
                        url: "{{route('manageGiftOfferReturned')}}",
                        dataType: "json",
                        type: "POST",
                        data: data2
                    }).then(function (data) {
                        $(".loading").hide();
                        location.reload();

                    }).fail(function (error) {
                        $(".loading").hide();
                        $(".return_item").removeAttr('disabled');
                        //$("#result").text(error.responseJSON.message);
                        if (error.responseJSON.hasOwnProperty('errors')) {
                            var error_msg = "";

                            for (var prop in error.responseJSON.errors) {

                                $(error.responseJSON.errors[prop]).each(function (val, msg) {
                                    toastr.error(msg, "Error");
                                });
                            }
                            //$("#result").text("");

                        } else {
                            toastr.error(error.responseJSON.message, "Error");
                            //$("#result").text("");
                        }

                    });
                }
            })
        </script>
    @endif
@endsection
<!-- FOOTER SECTION END -->
