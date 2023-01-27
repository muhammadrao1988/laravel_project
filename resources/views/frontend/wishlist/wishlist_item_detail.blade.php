@extends('layouts.layoutfront')
@section('css')
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        .summary-box-list {
            word-break: break-all
        }
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
                            <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">ORDER STATUS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">PURCHASED BY</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    @php
                        $status = strtolower($contributions[0]['item_status']);
                    @endphp
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabs-1" role="tabpanel">
                            @php
                                $top_margin = "mt-5"
                            @endphp
                            @if($show_return_form)
                                @php
                                    $top_margin = "mt-0"
                                @endphp
                                <div class="row">
                                    <div class="col-md-12 mt-5">
                                        <form method="POST" id="offer_returned_form">
                                            @csrf
                                            <input type="hidden" name="id"
                                                   value="{{$wishlist_detail->accept_donation==0 ? \App\Helpers\Common::encrypt_decrypt($contributions[0]['id']) : \App\Helpers\Common::encrypt_decrypt($wishlist_detail->id)}}">
                                            <input type="hidden" name="is_contributed" value="{{$wishlist_detail->accept_donation==0 ? "no" : "yes"}}">
                                            <h6 class="customer-info-text mb-3 mt-0">Return Order?</h6>
                                            <p style="font-size: 14px">You have {{$return_day}} days left to return
                                                order. </p>
                                            <div class="gift-box-content d-block">
                                                <div>
                                                    <p style="font-size: 16px">Please enter brief description for
                                                        return</p>
                                                    <textarea class="form-control" id="return_description"
                                                              name="status_description"
                                                              placeholder="Please enter detail for return order"></textarea>
                                                </div>
                                                <div class="mt-3">
                                                    <button type="button"
                                                            class="d-block btn btn-sm btn-outline-success return_item">
                                                        Submit
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                            @if($contributions[0]['return_description_giftee']!="")
                                @php
                                    $top_margin = "mt-0"
                                @endphp
                                <div class="row">
                                    <div class="col-md-12 mt-5">
                                        <h6 class="customer-info-text mb-3 mt-0">Return Order</h6>
                                        <div class="gift-box-content d-block">
                                            <div>
                                                <div>Your Message:</div>
                                                <p style="font-size: 16px">{{$contributions[0]['return_description_giftee']}}</p>
                                            </div>
                                            @if($contributions[0]['return_description_admin']!="")
                                                <div>Admin Reply:</div>
                                                <p style="font-size: 16px">{{$contributions[0]['return_description_admin']}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($status=="delivered")
                                @php
                                    $top_margin = "mt-0"
                                @endphp
                                <div class="row">
                                    <div class="col-md-12 mt-5">
                                        <form method="POST" id="offer_received_form">
                                            @csrf
                                            <input type="hidden" name="id"
                                                   value="{{$wishlist_detail->accept_donation==0 ? \App\Helpers\Common::encrypt_decrypt($contributions[0]['id']) : \App\Helpers\Common::encrypt_decrypt($wishlist_detail->id)}}">
                                            <input type="hidden" name="step" value="7">
                                            <input type="hidden" name="status" value="Received">
                                            <input type="hidden" name="is_contributed" value="{{$wishlist_detail->accept_donation==0 ? "no" : "yes"}}">
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
                                                    <button type="button"
                                                            class="d-block btn btn-sm btn-outline-success change_status">
                                                        Yes, I have received this item
                                                    </button>
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
                            <h6 class="customer-info-text mb-3 {{$top_margin}}">Status Timeline</h6>
                            @if($wishlist_detail->accept_donation==0)
                                <div class="card text-left" style="min-height: auto;">
                                    <div class="card-body">
                                        Order Processing -> {{$current_status_date}}<br>
                                    </div>
                                </div>
                            @endif
                            @foreach($order_timeline as $timeline)
                                <div class="card text-left" style="min-height: auto;">
                                    <div class="card-body">
                                        {{ (strtolower($timeline->status) == "collected" ? "Total Amount Collected at" : $timeline->status)  }}
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
                                    <a href="{{route('show.wishlist.item',\App\Helpers\Common::encrypt_decrypt($wishlist_detail->giftee_wishlist_id))}}"
                                       class="return-cart"><i class="fa fa-arrow-left back-arrow"
                                                              aria-hidden="true"></i> Return to Wishlist</a>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabs-3" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-12">
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
                                                    @if($wishlist_detail->accept_donation==1)
                                                        <p>Contribution: ${{\App\Helpers\Common::numberFormat($contribution['order']['subtotal'])}}</p>
                                                    @endif
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
                                                    @if($wishlist_detail->accept_donation==1)
                                                        <p>Contribution: ${{\App\Helpers\Common::numberFormat($contribution['order']['subtotal'])}}</p>
                                                    @endif
                                                </div>
                                            @endif

                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="cart-rightside-box">
                        <h5 class="shopping-amount">Item Details</h5>
                        <div class="qty-box">
                            @if($wishlist_detail->picture=="")
                                <img width="99" height="99" src="{{ asset('image/web/upload-pre.png')}}" alt="image"
                                     class="img-fluid">
                            @else
                                <img width="99" height="99"
                                     src="{{ asset("storage/uploads/wishlist_item/".$wishlist_detail->picture)}}"
                                     alt="image" class="img-fluid">
                            @endif
                            <div class="qty-box-main" style="width: 100%">
                                <h5 class="qty-box-heading">
                                    @if($wishlist_detail->accept_donation==1)
                                        <span class="qty-box-price">${{\App\Helpers\Common::numberFormat(($wishlist_detail->price * $wishlist_detail->quantity) + $wishlist_detail->shipping_cost + $wishlist_detail->service_fee + $wishlist_detail->tax_rate)}}</span>
                                        {{$wishlist_detail->gift_name}}<br><br>
                                        Qty: {{$wishlist_detail->quantity}}<br>
                                    @else
                                        <span class="qty-box-price">${{\App\Helpers\Common::numberFormat($wishlist_detail->price)}}</span>
                                        {{$wishlist_detail->gift_name}}<br><br>
                                        Qty: {{$wishlist_detail->quantity}}<br>
                                        Shipping: ${{\App\Helpers\Common::numberFormat($wishlist_detail->shipping_cost)}}
                                        <br>
                                        Exp. Shipping:
                                        ${{\App\Helpers\Common::numberFormat($wishlist_detail->expedited_shipping_fee)}}
                                    @endif

                                </h5>

                                @if($wishlist_detail->accept_donation==1)
                                    @php
                                        $perecntage = 0;
                                        $item_amount = ($wishlist_detail->price * $wishlist_detail->quantity) + $wishlist_detail->shipping_cost + $wishlist_detail->service_fee  + $wishlist_detail->tax_rate;
                                    @endphp
                                    @if($wishlist_detail->collected_amount > 0)
                                        @php
                                            $percentage = ceil(($wishlist_detail->collected_amount/$item_amount) * 100)
                                        @endphp
                                    @endif
                                    <div class="progress checkout_section ml-0">
                                        <div
                                                class="progress-bar progress-bar-success progress-bar-striped active"
                                                data-percentage="{{$percentage}}"
                                                role="progressbar" aria-valuenow="{{$percentage}}" aria-valuemin="0"
                                                aria-valuemax="100" style="width: {{$percentage}}%">
                                            <span id="current-progress">{{$percentage}}% Completed</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <h6 class="summary-box-heading pt-3">Options</h6>
                        <ul>
                            <li class="summary-box-list">Created At:<br>
                                <strong>{{\App\Helpers\Common::CTL($wishlist_detail->created_at,true)}}</strong></li>
                            <li class="summary-box-list">Merchant:<br> <strong>{{$wishlist_detail->merchant}}</strong>
                            </li>
                            <li class="summary-box-list">Accept Donation:<br>
                                <strong>{{$wishlist_detail->accept_donation== 1? "Yes" : "No"}}</strong></li>
                            <li class="summary-box-list">Digital Purchase:<br>
                                <strong>{{$wishlist_detail->digital_purchase== 1? "Yes" : "No"}}</strong></li>
                            <li class="summary-box-list">URL:<br>
                                <a class="shortern_url" href="{{$wishlist_detail->item_url}}" target="_blank">{{$wishlist_detail->url}}</a></li>
                            <li class="summary-box-list">Details:<br>
                                <strong>{{$wishlist_detail->gift_details}}</strong></li>
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
    @if($status=="delivered")
        <script>
            $(".change_status").click(function () {
                if ($("#status_description").val() == "") {
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
                        url: "{{route('manageWishListReceivedStatus')}}",
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
                if ($("#return_description").val() == "") {
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
                        url: "{{route('manageWishListReturned')}}",
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
