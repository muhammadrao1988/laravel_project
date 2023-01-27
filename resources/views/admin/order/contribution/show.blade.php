@extends('adminlte::page')
@section('adminlte_css')
    <style>
        .table-hover tbody tr:hover{
            background: inherit !important;
        }
    </style>
@stop
@section('title', 'View Orders of Wishlist #'.$model->id)
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
                                Wishlist Item Detail</h2>
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
                        <div class="col-md-12 table-responsive">
                            <div class="card">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Wishlist Name</th>
                                        <th>Name</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Prezziez Fee</th>
                                        <th>Shipping</th>
                                        <th>Taxes</th>
                                        <th>Total</th>
                                        <th>Current Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $wishlistDetail = $model->wishList;
                                        $order_status = strtolower($model->status);
                                        $taxes = $model->tax_rate
                                    @endphp
                                    <tr>
                                        <td><a target="_blank"
                                               href="{{route('show.wishlist.item',\App\Helpers\Common::encrypt_decrypt($model->giftee_wishlist_id,'encrypt'))}}">
                                                {{$wishlistDetail->title}}
                                            </a>
                                        </td>
                                        <td>{{$model->gift_name}}</td>
                                        <td>{{\App\Helpers\Common::numberFormat($model->quantity,0)}}</td>
                                        <td>${{\App\Helpers\Common::numberFormat($model->price)}}</td>
                                        <td>${{\App\Helpers\Common::numberFormat($model->service_fee)}}</td>
                                        <td>${{\App\Helpers\Common::numberFormat($model->shipping_cost)}}</td>
                                        <td>${{\App\Helpers\Common::numberFormat($taxes)}}</td>
                                        <td>
                                            ${{\App\Helpers\Common::numberFormat(($model->quantity * $model->price) + $model->shipping_cost+ $model->service_fee + $taxes)}}</td>
                                        <td>{{strtolower($model->status)=="created" ? "Collecting" : $model->status}}</td>

                                    </tr>
                                    <tr class="expandable-body">
                                        <td colspan="2" class="text-center">
                                            @if($model->picture!="")
                                                <img width="100" height="100"
                                                     src="{{ asset("storage/uploads/wishlist_item/".$model->picture)}}"
                                                     alt="image" class="img-fluid">

                                            @else
                                                <img width="100" height="100"
                                                     src="{{ asset('image/web/upload-pre.png')}}" alt="image"
                                                     class="img-fluid">

                                            @endif
                                        </td>
                                        <td colspan="3">
                                            <p>
                                                <strong>Wishlist Type: </strong>{{ucfirst($wishlistDetail->type)}}
                                                <br>
                                                <strong>Merchant: </strong>{{$model->merchant}}<br>
                                                <strong>Digital
                                                    Purchase: </strong>{!! $model->digital_purchase==1 ? '<span class="btn btn-success btn-sm">YES</span>' : '<span class="btn btn-danger btn-sm">NO</span>'!!}
                                                <br>
                                                <strong>URL: </strong><a class="shortern_url" href="{{$model->url}}" target="_blank">{{$model->url}}</a><br>
                                                <strong>Description: </strong>{{$model->gift_details}}<br>
                                            </p>
                                        </td>
                                        <td colspan="4">
                                            <div class="card-header">
                                                <h3 class="card-title">Order Actions</h3>
                                            </div>
                                            <div class="card-body row">
                                                @if(strtolower($model->status)=="created")
                                                    Item contribution is in progress
                                                @else
                                                    <div class="col-md-12">
                                                    @if($model->orders[0]->orderItems[0]->return_status_item==1)
                                                        <form method="POST" id="offer_confirmation_form">
                                                            @csrf
                                                            <input type="hidden" name="id" class="return_id" value="{{$model->id}}">
                                                            <div>
                                                                <h5>Return Request from Giftee</h5>
                                                                <p>{{$model->orders[0]->orderItems[0]->return_description_giftee}}</p>
                                                            </div>
                                                            <h5>Your Message</h5>

                                                            <textarea name="status_description" id="status_description" class="form-control status_description" placeholder="Please enter note or description"></textarea>
                                                            <br>
                                                            <p>Please click on submit button and confirm that return issue has been resolved.</p>
                                                            <button type="button"  class="btn btn-success return_item"><i class="fa fa-check"></i>
                                                                Submit Your Response
                                                            </button>
                                                            <hr>
                                                            {{--<p>Please enter message if you need to cancel order</p>--}}
                                                        </form>
                                                    @elseif($model->orders[0]->orderItems[0]->return_description_admin!="")
                                                        <div>
                                                            <h5>Return Request from Giftee</h5>
                                                            <p>{{$model->orders[0]->orderItems[0]->return_description_giftee}}</p>
                                                        </div>
                                                        <div>
                                                            <h5>Your Message</h5>
                                                            <p>{{$model->orders[0]->orderItems[0]->return_description_admin}}</p>
                                                        </div>
                                                        <hr>
                                                    @endif
                                                    @if( $order_status=="cancelled")
                                                        <button type="button" class="btn btn-danger btn-block"><i
                                                                    class="fa fa-bell"></i> No further action is required. The order is Cancelled.
                                                        </button>
                                                        @php
                                                            $cancelled_reason_query = $model->itemStatusHistory->where('status','=','Cancelled')->first();
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
                                                                @php
                                                                    $return_transaction = \App\Models\ReturnTransaction::where('wishlist_item_id','=',$model->id)->first();

                                                                @endphp
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
                                                                        <th style="width:50%">Return Amount::</th>
                                                                        <td>
                                                                            ${{\App\Helpers\Common::numberFormat($return_transaction->return_amount)}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Return Date:</th>
                                                                        <td>{{\App\Helpers\Common::CTL($return_transaction->created_at)}}</td>
                                                                    </tr>
                                                                @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @else
                                                        <form method="POST" >
                                                            @csrf
                                                            <input type="hidden" name="step" value="3">
                                                            <input type="hidden" class="order_item_selected" name="id" value="{{$model->id}}">
                                                            @if($order_status!="received")
                                                            <textarea name="gifter_message"  class="form-control gifter_message" placeholder="{{$order_status=="order placed" ? "Please enter tracking URL" : "Please enter note or description"}}"></textarea>
                                                            <br>
                                                            @endif
                                                            @if($order_status=="collected")
                                                                <button type="button" data-previous-action="Collected" data-action="Order Placed" data-step="3" class="btn btn-success change_status"><i class="fa fa-check"></i>
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
                                                            @elseif($order_status=="delivered")
                                                                <button type="button" data-previous-action="Delivered" data-action="Received" data-step="7" class="btn btn-success change_status"><i class="fa fa-check"></i>
                                                                    Mark as Received
                                                                </button>
                                                            @elseif($order_status=="received")
                                                                <button type="button" class="btn btn-success btn-block"><i
                                                                            class="fa fa-bell"></i> No further action is required. The order is received.
                                                                </button>
                                                            @endif
                                                            @if($order_status!="received" && $order_status!="delivered" && $order_status!="shipped")
                                                                <br><br>
                                                                <div class="font-italic"><strong>OR</strong></div>
                                                                <br>
                                                                <button type="button" class="btn btn-danger mark_as" data-action="Cancelled"><i class="fa fa-window-close"></i> Cancelled Order
                                                                </button>
                                                            @endif
                                                        </form>
                                                    @endif
                                                </div>
                                                @endif
                                            </div>

                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="page-header">
                                Giftee Detail</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            @php
                                $model->toUser = $model->user
                            @endphp
                            @include('admin.order.template.to_user')
                        </div>
                        <div class="col-md-6">
                            <h3>Item History</h3>
                            <div class="timeline">
                                <div class="time-label">
                                    <span class="bg-green">{{\App\Helpers\Common::CTL($model->created_at)}}</span>
                                </div>

                                <div>
                                    <i class="fas fa-check bg-green"></i>
                                    <div class="timeline-item">
                                        <h3 class="timeline-header no-border"><a href="#">Created At</a></h3>
                                    </div>
                                </div>
                                @foreach($model->itemStatusHistory as $history)
                                    <div class="time-label">
                                        <span class="bg-green">{{\App\Helpers\Common::CTL($history->created_at)}}</span>
                                    </div>

                                    <div>
                                        <i class="fas fa-check bg-green"></i>
                                        <div class="timeline-item">
                                            <h3 class="timeline-header no-border"><a href="#">{{$history->status}}</a></h3>
                                            <p class="col-md-12">
                                                @if($history->status=="Shipped")
                                                    <a class="shortern_url" style="width: 250px" href="{{$history->description}}" target="_blank">{{$history->description}}</a>
                                                @else
                                                    {{$history->description}}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                                @if($model->orders[0]->orderItems[0]->return_description_giftee!="")
                                    <div class="time-label">
                                        <span class="bg-green">{{\App\Helpers\Common::CTL($model->orders[0]->orderItems[0]->updated_at)}}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-check bg-green"></i>
                                        <div class="timeline-item">
                                            <h3 class="timeline-header no-border"><a href="#">Returned</a></h3>
                                            <p class="col-md-12">
                                                <strong>Giftee:</strong> {{$model->orders[0]->orderItems[0]->return_description_giftee}}
                                            </p>
                                            @if($model->orders[0]->orderItems[0]->return_description_admin!="")
                                                <p class="col-md-12">
                                                    <strong>Admin:</strong> {{$model->orders[0]->orderItems[0]->return_description_admin}}
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
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="page-header">Contributors:</h2>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 table-responsive">
                            <div class="card">

                                @foreach($model->orders as $order)
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Contributed Amount</th>
                                            <th>Processing Fee</th>
                                            <th>Credit Apply</th>
                                            <th>Total</th>
                                            <th>Payment Type</th>
                                            <th>Transaction ID</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{{$order->id}}</td>
                                            <td>${{\App\Helpers\Common::numberFormat($order->subtotal)}}</td>
                                            <td>${{\App\Helpers\Common::numberFormat($order->processing_fee)}}</td>
                                            <td>- ${{\App\Helpers\Common::numberFormat($order->credit_apply)}}</td>
                                            <td>${{\App\Helpers\Common::numberFormat($order->total_amount)}}</td>
                                            <td>{{$order->payment_method}}</td>
                                            <td>{{$order->payment_id}}</td>
                                        </tr>
                                        <tr class="expandable-body">
                                            <td colspan="7">
                                                <div class="row invoice-info">

                                                    <div class="col-md-6 invoice-col">
                                                        <div class="card-body row">
                                                            <div class="col-md-12">
                                                                <h3>Profile Detail</h3>
                                                                <div class="table-responsive">
                                                                    @if(!empty($order->fromUser->id))
                                                                        <table class="table">
                                                                            <tbody>
                                                                            <tr>
                                                                                <th style="width:50%">Profile URL:</th>
                                                                                <td><a href="{{route('profileUrl',$order->fromUser->username)}}"
                                                                                       target="_blank">{{$order->fromUser->displayName}}</a></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Address:</th>
                                                                                <td>{{$order->fromUser->address}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>City:</th>
                                                                                <td>{{$order->fromUser->city}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>State:</th>
                                                                                <td>{{$order->fromUser->state}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Country:</th>
                                                                                <td>{{$order->fromUser->country}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Zip:</th>
                                                                                <td>{{$order->fromUser->zip}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Phone:</th>
                                                                                <td>{{$order->fromUser->contactNumber}}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Email Address:</th>
                                                                                <td>{{$order->fromUser->email}}</td>
                                                                            </tr>

                                                                            </tbody>
                                                                        </table>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 invoice-col">
                                                        <div class="card-body row">
                                                            <div class="col-md-12">
                                                                <h3>Billing Info</h3>
                                                                <div class="table-responsive">
                                                                    <table class="table">
                                                                        <tbody>
                                                                        <tr>
                                                                            <th style="width:50%">Name:</th>
                                                                            <td>{{$order->billingInfo->first_name}} {{$order->billingInfo->last_name}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Email Address:</th>
                                                                            <td>{{$order->billingInfo->email}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Billing Address:</th>
                                                                            <td>{{$order->billingInfo->address}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Country:</th>
                                                                            <td>{{$order->billingInfo->country}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>City:</th>
                                                                            <td>{{$order->billingInfo->city}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>State:</th>
                                                                            <td>{{$order->billingInfo->state}}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Postal Code:</th>
                                                                            <td>{{$order->billingInfo->postal_code}}</td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>

                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>


                                @endforeach

                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-6"></div>

                        <div class="col-6">
                            <p class="lead">Collection Summary</p>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                    @php
                                        $collected_amount =$model->orders->sum('subtotal');
                                        $taxes = $model->orders->sum('processing_fee');
                                        $apply_credit = $model->orders->sum('credit_apply');
                                        $total_amount = $model->orders->sum('total_amount')
                                    @endphp
                                    <tr>
                                        <th style="width:50%">Collected Amount:</th>
                                        <td>${{\App\Helpers\Common::numberFormat($collected_amount)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Estimated Taxes and Fee:</th>
                                        <td>${{\App\Helpers\Common::numberFormat($taxes)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Prezziez Credit:</th>
                                        <td>- ${{\App\Helpers\Common::numberFormat($apply_credit)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Total:</th>
                                        <td>${{\App\Helpers\Common::numberFormat($total_amount)}}</td>
                                    </tr>
                                    </tbody></table>
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
            var that = $(this);
            var closest = that.closest('form');

            if($(closest).find(".gifter_message").val()==""){
                alert("Please enter message");
                $(closest).find(".gifter_message").focus();
                return false;
            }
            if(confirm("Do you really want to "+status+"?")) {
                $(that).attr('disabled', true);
                $(that).text('Please Wait');

                //$("#result").text("Please wait, we are processing..");
                var data2 = $(closest).serialize();
                data2 +="&accpeted_status="+status;

                $.ajax({
                    url: "{{route('contributedCancelled')}}",
                    dataType: "json",
                    type: "POST",
                    data: data2
                }).then(function (data) {

                    location.reload();

                }).fail(function (error) {

                    $(that).removeAttr('disabled');
                    $(that).text('Cancelled Order');
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
            var that = $(this);
            var closest = that.closest('form');
            var status = $(this).attr('data-action');
            var step = $(this).attr('data-step');
            var id = closest.find(".order_item_selected").val();

            var status_description = $(closest).find(".gifter_message").val();
            if(status_description=="" && status=="Shipped"){
                alert("Please enter tracking info URL");
                $(closest).find(".gifter_message").focus();
                return false;
            }
            if(status=="Shipped" && status_description!="" ){
                var regexp =  {{config('constants.VALID_URL')}};
                var re = new RegExp(regexp);
                if(!re.test(status_description)){
                    alert("Invalid tracking url");
                    $(closest).find(".gifter_message").focus();
                    return false;
                }
            }

            var token = "{{csrf_token()}}";
            var previous_status =  $(this).attr('data-previous-action');
            if(confirm("Do you really want to change status from "+previous_status+" to "+status+"?")) {
                $(that).attr('disabled', true);
                $(that).text('Please Wait');

                var data2 = {
                    id:id,
                    status:status,
                    _token:token,
                    status_description: status_description,
                    step: step,
                };

                $.ajax({
                    url: "{{route('manageContributedItemStatus')}}",
                    dataType: "json",
                    type: "POST",
                    data: data2
                }).then(function (data) {

                    location.reload();

                }).fail(function (error) {

                    $(that).removeAttr('disabled');
                    $(that).text(status);
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

            var that = $(this);
            var closest = that.closest('form');
            var id = closest.find(".return_id").val();

            if($(closest).find(".status_description").val()==""){
                alert("Please enter message");
                $(closest).find(".status_description").focus();
                return false;
            }

            var status_description = $(closest).find(".status_description").val();
            var token = "{{csrf_token()}}";
            if(confirm("Please confirm that return issue has been resolved?")) {
                $(that).attr('disabled', true);
                $(that).text('Please Wait');

                var data2 = {
                    id:id,
                    _token:token,
                    status_description: status_description,
                };

                $.ajax({
                    url: "{{route('manageContributedReturned')}}",
                    dataType: "json",
                    type: "POST",
                    data: data2
                }).then(function (data) {

                    location.reload();

                }).fail(function (error) {

                    $(that).removeAttr('disabled');
                    $(that).text("Submit Your Response");
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
                url: "{{route('contributedOrderNumUpdate')}}",
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
