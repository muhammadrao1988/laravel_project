@extends('adminlte::page')
@section('title', 'View Order #'.$model->id)
@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>@yield('title')</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ $breadcrumb_route }}">{{$breadcrumb_route_name}}</a></li>
                <li class="breadcrumb-item active">@yield('title')</li>
            </ol>
        </div>
    </div>
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col-sm-12">
                <section class="invoice p-4">

                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="page-header">
                                Order Detail</h2>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <form id="update_order_num_form" class="form-horizontal">
                                {{csrf_field()}}
                                <h5>Update Order Number</h5>
                                <div class="input-group input-group-sm mb-0">
                                    <input type="hidden" name="order_id" value="{{$model->id}}">
                                    <input class="form-control form-control-sm" id="order_num" name="order_num" value="{{$model->order_num}}" placeholder="Enter order number">
                                    <div class="input-group-append">
                                        <button type="button" id="update_order_num" class="btn btn-danger">Update Order Number</button>
                                    </div>
                                </div>
                            </form>
                            <hr>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Profile Setting</h3>
                                </div>
                                <div class="card-body row">
                                    <div class="col-md-12 text-center">
                                        <button type="button" class="btn btn-primary btn-block"><i
                                                    class="fa fa-bell"></i> RECEIVE GIFT OFFERS
                                        </button>
                                        <br>
                                        @if($model->toUser->offer_gift==1)
                                            <button type="button" class="btn btn-success"><i class="fa fa-check"></i>
                                                Yes
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-danger"><i
                                                        class="fa fa-window-close"></i> No
                                            </button>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Order Info</h3>
                                </div>
                                <div class="card-body row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            @if(!empty($model->toUser->id))
                                                <table class="table">
                                                    <tbody>
                                                    <tr>
                                                        <th style="width:50%">Order ID:</th>
                                                        <td>{{$model->id}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Payment Type:</th>
                                                        <td>{{$model->payment_method}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Transaction ID:</th>
                                                        <td>{{$model->payment_id}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status:</th>
                                                        <td><strong>{{$model->status}}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Note to Giftee:</th>
                                                        <td>{{$model->note}}</td>
                                                    </tr>

                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Order Actions</h3>
                                </div>
                                <div class="card-body row">
                                    <div class="col-md-12">
                                        @if($model->orderItems[0]->return_status_item==1)
                                            <form method="POST" id="offer_confirmation_form">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$model->orderItems[0]->id}}">
                                                <div>
                                                    <h5>Return Request from Giftee</h5>
                                                    <p>{{$model->orderItems[0]->return_description_giftee}}</p>
                                                </div>
                                                <h5>Your Message</h5>

                                                <textarea name="status_description" id="status_description" class="form-control status_description" placeholder="Please enter note or description"></textarea>
                                                <br>
                                                <p>Please click on submit button and confirm that return issue has been resolved.</p>
                                                <button type="button"  class="btn btn-success return_item"><i class="fa fa-check"></i>
                                                   Submit Your Response
                                                </button>
                                                <hr>
                                            </form>
                                        @elseif($model->orderItems[0]->return_description_admin!="")
                                            <div>
                                                <h5>Return Request from Giftee</h5>
                                                <p>{{$model->orderItems[0]->return_description_giftee}}</p>
                                            </div>
                                            <div>
                                                <h5>Your Message</h5>
                                                <p>{{$model->orderItems[0]->return_description_admin}}</p>
                                            </div>
                                            <hr>
                                            {{--<p>Please enter message if you need to cancel order</p>--}}

                                        @endif

                                        @if($order_status=="declined" || $order_status=="cancelled")
                                            <button type="button" class="btn btn-danger btn-block"><i
                                                        class="fa fa-bell"></i>
                                                @if(strtolower($model->payment_method)=="googlepay")
                                                    You will have to return manually. The Google pay does not support auto return.
                                                @else
                                                    No further action is required.The order is
                                                @endif
                                               {{$model->status}}.
                                            </button>
                                            @php
                                                $cancelled_reason_query = $model->orderStatusHistory->where('status','=',$model->status)->first();
                                            @endphp
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <tbody>
                                                    @if(!empty($cancelled_reason_query->status))
                                                        <tr>
                                                            <th style="width:50%">{{$model->status}} Reason:</th>
                                                            <td> {{$cancelled_reason_query->description}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>{{$model->status}} at:</th>
                                                            <td>{{\App\Helpers\Common::CTL($cancelled_reason_query->created_at)}}</td>
                                                        </tr>
                                                    @endif
                                                    @if(!empty($return_transaction))
                                                        <tr>
                                                            <th colspan="2" class="text-center">Return detail</th>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:50%">Transaction ID:</th>
                                                            <td> {{$return_transaction->previous_charge_id}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Credit Return:</th>
                                                            <td>
                                                                ${{\App\Helpers\Common::numberFormat($return_transaction->credit_apply)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:50%">{{strtolower($model->payment_method)=="googlepay" ? "Amount will return:" : "Returned Amount:"}}</th>
                                                            <td>
                                                                ${{\App\Helpers\Common::numberFormat($return_transaction->return_amount)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Date:</th>
                                                            <td>{{\App\Helpers\Common::CTL($return_transaction->created_at)}}</td>
                                                        </tr>
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                                @php($cancel_show = true)
                                            <form method="POST" id="offer_confirmation_form">
                                                @csrf
                                                <input type="hidden" name="step" value="3">
                                                <input type="hidden" name="id" value="{{$model->orderItems[0]->id}}">
                                                @if($order_status!="received")
                                                <textarea name="gifter_message" id="gifter_message" class="form-control status_description" placeholder="{{$order_status=="order placed" ? "Please enter tracking URL" : "Please enter note or description"}}"></textarea>
                                                <br>
                                                @endif
                                                @if($order_status=="accepted")
                                                    <button type="button" data-previous-action="Accepted" data-action="Order Processing" data-step="3" class="btn btn-success change_status"><i class="fa fa-check"></i>
                                                        Start Order Processing
                                                    </button>
                                                @elseif($order_status=="order processing")
                                                    <button type="button" data-previous-action="Order Processing" data-action="Order Placed" data-step="4" class="btn btn-success change_status"><i class="fa fa-check"></i>
                                                         Order Placed
                                                    </button>
                                                @elseif($order_status=="order placed")
                                                    <button type="button" data-previous-action="Order Placed" data-action="Shipped" data-step="5" class="btn btn-success change_status"><i class="fa fa-check"></i>
                                                        Mark as Shipped
                                                    </button>
                                                @elseif($order_status=="shipped")
                                                    <button type="button" data-previous-action="Shipped" data-action="Delivered" data-step="6" class="btn btn-success change_status"><i class="fa fa-check"></i>
                                                        Mark as Delivered
                                                    </button>
                                                    @php($cancel_show = false)
                                                @elseif($order_status=="delivered")
                                                    <button type="button" data-previous-action="Delivered" data-action="Received" data-step="7" class="btn btn-success change_status"><i class="fa fa-check"></i>
                                                        Mark as Received
                                                    </button>
                                                    @php($cancel_show = false)
                                                @elseif($order_status=="received")
                                                    <button type="button" class="btn btn-success btn-block"><i
                                                                class="fa fa-bell"></i> No further action is required. The order is received.
                                                    </button>
                                                    @php($cancel_show = false)
                                                @endif
                                                @if($cancel_show)
                                                    <br><br>

                                                    <div class="font-italic"><strong>OR</strong></div>
                                                    <br>
                                                    <button type="button" class="btn btn-danger mark_as" data-action="Cancelled"><i class="fa fa-window-close"></i> Cancelled Order
                                                    </button>
                                                @endif
                                            </form>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="page-header">
                                Gifter / Giftee Detail</h2>
                        </div>

                    </div>

                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            @include('admin.order.template.to_user')
                        </div>

                        <div class="col-sm-4 invoice-col">
                            @include('admin.order.template.from_user')
                        </div>

                        <div class="col-sm-4 invoice-col">
                            @include('admin.order.template.billing_detail')
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="page-header">
                                Order Items</h2>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <div class="card">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                        <th>Shipping</th>
                                        <th>Tax</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($model->orderItems as $item)
                                        <tr>
                                            <td>{{$item->item_name}}</td>
                                            <td>{{\App\Helpers\Common::numberFormat($item->item_qty,0)}}</td>
                                            <td>${{\App\Helpers\Common::numberFormat($item->item_price)}}</td>
                                            <td>
                                                ${{\App\Helpers\Common::numberFormat($item->item_qty * $item->item_price)}}</td>
                                            <td>${{\App\Helpers\Common::numberFormat($item->item_shipping_price)}}</td>
                                            <td>${{\App\Helpers\Common::numberFormat($model->taxes)}}</td>
                                        </tr>
                                        <tr class="expandable-body">
                                            <td colspan="2" class="text-center">
                                                @if($item->item_image!="")
                                                    <img width="100" height="100"
                                                         src="{{ asset("storage/uploads/offer_gift/".$item->item_image)}}"
                                                         alt="image" class="img-fluid">

                                                @else
                                                    <img width="100" height="100"
                                                         src="{{ asset('image/web/upload-pre.png')}}" alt="image"
                                                         class="img-fluid">

                                                @endif
                                            </td>
                                            <td colspan="3">
                                                <p style="word-break: break-all">

                                                    <strong>Merchant: </strong>{{$item->item_merchant}}<br>
                                                    <strong>Digital
                                                        Purchase: </strong>{!! $item->item_digital_purchase==1 ? '<span class="btn btn-success btn-sm">YES</span>' : '<span class="btn btn-danger btn-sm">NO</span>'!!}
                                                    <br>
                                                    <strong>URL: </strong><a class="shortern_url" href="{{$item->item_url}}" target="_blank">{{$item->item_url}}</a><br>
                                                    <strong>Description: </strong>{{$item->item_detail}}<br>
                                                </p>
                                            </td>
                                            <td colspan="2">
                                                <h5><strong>Giftee Specification:</strong></h5>
                                                <p>
                                                    {{$item->giftee_item_specification=="" ? "No specification from giftee" : $item->giftee_item_specification}}
                                                </p>

                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col-6">
                            <div class="timeline">
                                @foreach($model->orderStatusHistory as $history)
                                    <div class="time-label">
                                        <span class="bg-green">{{\App\Helpers\Common::CTL($history->created_at)}}</span>
                                    </div>

                                    <div>
                                        @if($history->status=="Pending")
                                            <i class="fas fa-clock bg-danger"></i>
                                            <div class="timeline-item">
                                                <h3 class="timeline-header no-border"><a
                                                            href="#">{{$history->status}}</a></h3>
                                            </div>
                                        @else
                                            <i class="fas fa-check bg-green"></i>
                                            <div class="timeline-item">
                                                <h3 class="timeline-header no-border"><a
                                                            href="#">{{$history->status}}</a></h3>
                                                <p class="col-md-12">
                                                    @if($history->status=="Shipped")
                                                        <a class="shortern_url" style="width: 250px" href="{{$history->description}}" target="_blank">{{$history->description}}</a>
                                                     @else
                                                        {{$history->description}}
                                                    @endif
                                                </p>
                                            </div>
                                        @endif

                                    </div>
                                @endforeach
                                @if($model->orderItems[0]->return_description_giftee!="")
                                    <div class="time-label">
                                        <span class="bg-green">{{\App\Helpers\Common::CTL($model->orderItems[0]->updated_at)}}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-check bg-green"></i>
                                        <div class="timeline-item">
                                            <h3 class="timeline-header no-border"><a href="#">Returned</a></h3>
                                            <p class="col-md-12">
                                                <strong>Giftee:</strong> {{$model->orderItems[0]->return_description_giftee}}
                                            </p>
                                            @if($model->orderItems[0]->return_description_admin!="")
                                                <p class="col-md-12">
                                                    <strong>Admin:</strong> {{$model->orderItems[0]->return_description_admin}}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif


                                <div>
                                    <i class="fas fa-clock bg-gray"></i>
                                </div>
                            </div>

                        </div>

                        <div class="col-6">
                            <p class="lead">Order Summary</p>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <th style="width:50%">Item Total:</th>
                                        <td>${{\App\Helpers\Common::numberFormat($model->subtotal)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Shipping Fee</th>
                                        <td>${{\App\Helpers\Common::numberFormat($model->shipping_fee)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Estimated Taxes and Fee:</th>
                                        <td>${{\App\Helpers\Common::numberFormat($model->processing_fee)}}</td>
                                    </tr>
                                    <tr>
                                        <th style="width:50%">Sub Total:</th>
                                        <td>
                                            ${{\App\Helpers\Common::numberFormat($model->subtotal + $model->shipping_fee + $model->processing_fee)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Use Prezziez Credit:</th>
                                        <td>- ${{\App\Helpers\Common::numberFormat($model->credit_apply)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Total:</th>
                                        <td>${{\App\Helpers\Common::numberFormat($model->total_amount)}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </section>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop
@section('plugins.Select2', true)
@section('js')
    <script>
        $(".mark_as").click(function () {
            var status = $(this).attr('data-action');
            if($("#gifter_message").val()==""){
                if(status=="Cancelled"){
                    alert("Please enter reason for cancellation");
                }else {
                    alert("Please enter message");
                }
                $("#gifter_message").focus();
                return false;
            }
            if(confirm("Do you really want to "+status+" this offer?")) {
                $(".mark_as").attr('disabled', true);
                $(".mark_as").text('Please Wait');

                //$("#result").text("Please wait, we are processing..");
                var data2 = $('#offer_confirmation_form').serialize();
                data2 +="&accpeted_status="+status;

                $.ajax({
                    url: "{{route('giftOfferCancelled')}}",
                    dataType: "json",
                    type: "POST",
                    data: data2
                }).then(function (data) {

                    location.reload();

                }).fail(function (error) {

                    $(".mark_as").removeAttr('disabled');
                    $(".mark_as").text('Cancelled Order');
                    //$("#result").text(error.responseJSON.message);
                    if (error.responseJSON.hasOwnProperty('errors')) {
                        var error_msg = "";
                        for (var prop in error.responseJSON.errors) {

                            $(error.responseJSON.errors[prop]).each(function (val, msg) {
                                toastr.error(msg, "Error");
                            });
                        }
                    } else {
                        toastr.error(error.responseJSON.message, "Error");
                        //$("#result").text("");
                    }

                });
            }
        })
        $(".change_status").click(function () {
            var status = $(this).attr('data-action');
            var step = $(this).attr('data-step');
            var id = "{{$model->orderItems[0]->id}}";

            var status_description = $("#gifter_message").val();

            if(status_description=="" && status=="Shipped"){
                alert("Please enter tracking info URL");
                $("#gifter_message").focus();
                return false;
            }
            if(status=="Shipped" && status_description!="" ){
                var regexp =  {{config('constants.VALID_URL')}};
                var re = new RegExp(regexp);
                if(!re.test(status_description)){
                    alert("Invalid tracking url");
                    $("#gifter_message").focus();
                    return false;
                }
            }

            var token = "{{csrf_token()}}";
            var previous_status =  $(this).attr('data-previous-action');
            if(confirm("Do you really want to change status from "+previous_status+" to "+status+"?")) {
                $(".change_status").attr('disabled', true);
                $(".change_status").text('Please Wait');

                var data2 = {
                    id:id,
                    status:status,
                    _token:token,
                    status_description: status_description,
                    step: step,
                };

                $.ajax({
                    url: "{{route('manageGiftOfferStatus')}}",
                    dataType: "json",
                    type: "POST",
                    data: data2
                }).then(function (data) {

                    location.reload();

                }).fail(function (error) {

                    $(".change_status").removeAttr('disabled');
                    $(".change_status").text(status);
                    //$("#result").text(error.responseJSON.message);
                    if (error.responseJSON.hasOwnProperty('errors')) {
                        var error_msg = "";
                        for (var prop in error.responseJSON.errors) {

                            $(error.responseJSON.errors[prop]).each(function (val, msg) {
                                toastr.error(msg, "Error");
                            });
                        }
                    } else {
                        toastr.error(error.responseJSON.message, "Error");
                        //$("#result").text("");
                    }

                });
            }
        })
        $(".return_item").click(function () {

            var id = "{{$model->orderItems[0]->id}}";
            if($("#status_description").val()==""){
                alert("Please enter message");
                $("#gifter_message").focus();
                return false;
            }
            var status_description = $("#status_description").val();
            var token = "{{csrf_token()}}";
            if(confirm("Please confirm that return issue has been resolved?")) {
                $(".return_item").attr('disabled', true);
                $(".return_item").text('Please Wait');

                var data2 = {
                    id:id,
                    _token:token,
                    status_description: status_description,
                };

                $.ajax({
                    url: "{{route('manageGiftOffersReturned')}}",
                    dataType: "json",
                    type: "POST",
                    data: data2
                }).then(function (data) {

                    location.reload();

                }).fail(function (error) {

                    $(".return_item").removeAttr('disabled');
                    $(".return_item").text("Submit Your Response");
                    //$("#result").text(error.responseJSON.message);
                    if (error.responseJSON.hasOwnProperty('errors')) {
                        var error_msg = "";
                        for (var prop in error.responseJSON.errors) {

                            $(error.responseJSON.errors[prop]).each(function (val, msg) {
                                toastr.error(msg, "Error");
                            });
                        }
                    } else {
                        toastr.error(error.responseJSON.message, "Error");
                        //$("#result").text("");
                    }

                });
            }
        })

        $("#update_order_num").click(function () {


            if($("#order_num").val()==""){
                alert("Please enter order number");
                $("#order_num").focus();
                return false;
            }
            var data = $("#update_order_num_form").serialize();
            $("#update_order_num").attr('disabled', true);
            $("#update_order_num").text('Please Wait');

            $.ajax({
                url: "{{route('giftOfferOrderNumUpdate')}}",
                dataType: "json",
                type: "POST",
                data: data
            }).then(function (data) {

                location.reload();

            }).fail(function (error) {

                $("#update_order_num").removeAttr('disabled');
                $("#update_order_num").text("Update Order Number");
                //$("#result").text(error.responseJSON.message);
                if (error.responseJSON.hasOwnProperty('errors')) {
                    var error_msg = "";
                    for (var prop in error.responseJSON.errors) {

                        $(error.responseJSON.errors[prop]).each(function (val, msg) {
                            toastr.error(msg, "Error");
                        });
                    }
                } else {
                    toastr.error(error.responseJSON.message, "Error");
                    //$("#result").text("");
                }

            });
        })
    </script>
@stop
