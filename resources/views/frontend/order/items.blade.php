@extends('layouts.layoutfront')
@section('title', 'My-Orders')
@section('content')
    <!-- DETAIL & SUMMARY SECTION BEGIN -->
    <section class="detail-summary-sec" style="padding-top: 70px">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12 mb-3">
                    <a class="text-right" href="{{route('my-orders')}}">
                        <h6><i class="fa fa-backward"></i> Back to Orders</h6>
                    </a>
                </div>
                <div class="col-md-6 col-sm-12 col-12">
                    <div class="order-summary-content">
                        <h5 class="order-sum-heading">Order Summary</h5>
                        <div class="order-summary-box">
                            <ul>
                                @if(strtolower($model->order_type)=="cart")
                                    <li>Expedited Shipping <span
                                                class="pull-right amount-blue-col">
                                             @if($model->expedited_shipping==1)
                                                <i class="fa fa-check" aria-hidden="true"></i> YES</span>
                                        @else
                                            <i class="fa fa-close" aria-hidden="true"></i> NO</span>
                                            @endif
                                            </span>
                                    </li>
                                @endif
                                <li>Item Subtotal <span
                                            class="pull-right">${{\App\Helpers\Common::numberFormat($model->subtotal)}}</span>
                                </li>
                                @if(strtolower($model->order_type)!="contribution")
                                    <li>Shipping Fee <span
                                                class="pull-right">${{\App\Helpers\Common::numberFormat($model->shipping_fee)}}</span>
                                    </li>
                                @endif
                                <li>{{strtolower($model->order_type)!="contribution" ?  "Estimated Taxes and Fee:" : "Estimated Fee:"}} <span
                                            class="pull-right">${{\App\Helpers\Common::numberFormat($model->processing_fee)}}</span>
                                </li>
                                <li>Subtotal: <span
                                            class="pull-right">${{\App\Helpers\Common::numberFormat($model->subtotal + $model->shipping_fee + $model->processing_fee)}}</span>
                                </li>
                                <li>Use Prezziez Credit: <span
                                            class="pull-right">- ${{\App\Helpers\Common::numberFormat($model->credit_apply)}}</span>
                                </li>
                                <li>Total: <span
                                            class="pull-right amount-pink-col">${{\App\Helpers\Common::numberFormat($model->total_amount)}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 col-12">
                    <div class="order-detail-content">
                        <h5 class="order-det-heading">Order Detail -> <span
                                    class="amount-blue-col">{{$model->status}}</span></h5>
                        <div class="order-detail-box">
                            <h6>Transaction Detail</h6>
                            <ul>
                                <li>Order ID: <span class="pull-right">{{$model->id}}</span></li>
                                <li>Giftee Name: <a href="{{route('profileUrl',$model->toUser->username)}}" target="_blank" class="pull-right amount-blue-col">{{$model->toUser->username}}</a></li>
                                <li>Created At: <span
                                            class="pull-right">{{\App\Helpers\Common::CTL($model->created_at)}}</span>
                                </li>
                                <li>Payment Type: <span class="pull-right">{{$model->payment_method}}</span></li>
                                <li>Transaction ID: <span class="pull-right">{{$model->payment_id}}</span></li>
                                <li>Status: <span class="pull-right amount-blue-col">{{$model->status}}</span></li>
                                <li>Your Note: <span class="pull-right">{{$model->note}}</span></li>
                                @if(strtolower($model->order_type)=="cart")
                                    <li>Receive Status of Purchase:
                                        <span class="pull-right amount-blue-col">
                                        @if($model->recieve_purchase_status==1)
                                                <i class="fa fa-check" aria-hidden="true"></i> YES</span>
                                        @else
                                            <i class="fa fa-close" aria-hidden="true"></i> NO</span>
                                        @endif
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- DETAIL & SUMMARY SECTION END -->
    <!-- GIFTEE DETAIL SECTION BEGIN -->
    <section class="giftee-detail-sec">
        <div class="container">

            <div class="row">
                <div class="col-md-6 col-sm-12 col-12">
                    <div class="order-detail-content">
                        <div class="order-detail-box">
                            <h6>Billing Info</h6>
                            <ul>
                                <li>First Name: <span class="pull-right">{{$model->billingInfo->first_name}}</span></li>
                                <li>Last Name: <span class="pull-right">{{$model->billingInfo->last_name}}</span></li>
                                <li>Email Address: <span class="pull-right">{{$model->billingInfo->email}}</span></li>
                                <li>Billing Address: <span class="pull-right">{{$model->billingInfo->address}}</span>
                                </li>
                                <li>Country: <span class="pull-right">{{$model->billingInfo->country}}</span></li>
                                <li>City: <span class="pull-right">{{$model->billingInfo->city}}</span></li>
                                <li>State: <span class="pull-right">{{$model->billingInfo->state}}</span></li>
                                <li>Postal Code: <span class="pull-right">{{$model->billingInfo->postal_code}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 col-12">
                    @if(!empty($return_transaction) && $model->order_type=="GiftOffer")
                        <div class="order-detail-content mt-3">
                            <div class="order-detail-box">
                                <h6>Return Detail</h6>
                                @if(strtolower($model->payment_method)=="googlepay")
                                    <p class="text-danger text-bold">Please contact prezziez admin for return amount. The amount will be ${{\App\Helpers\Common::numberFormat($return_transaction->return_amount)}}</p>
                                    <ul>
                                        @if($return_transaction->credit_apply > 0)
                                            <li>Credit Return: <span
                                                        class="pull-right">${{\App\Helpers\Common::numberFormat($return_transaction->credit_apply)}}</span>
                                            </li>
                                        @endif
                                            <li>Date: <span
                                                        class="pull-right">{{\App\Helpers\Common::CTL($return_transaction->created_at,true)}}</span>
                                            </li>
                                    </ul>
                                @else
                                <ul>
                                    <li>Transaction ID: <span
                                                class="pull-right">{{$return_transaction->previous_charge_id}}</span>
                                    </li>
                                    <li>Credit Return: <span
                                                class="pull-right">${{\App\Helpers\Common::numberFormat($return_transaction->credit_apply)}}</span>
                                    </li>
                                    <li>Return Amount: <span
                                                class="pull-right">${{\App\Helpers\Common::numberFormat($return_transaction->return_amount)}}</span>
                                    </li>
                                    <li>Return Date: <span
                                                class="pull-right">{{\App\Helpers\Common::CTL($return_transaction->created_at,true)}}</span>
                                    </li>
                                </ul>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- GIFTEE DETAIL SECTION END -->
    <!-- ORDER ITEM SECTION BEGIN -->
    <section class="order-item-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="order-item-table">
                        <h5 class="order-item-content">Order Items</h5>
                        <div class="table-responsive border-shade">
                            @if(strtolower($model->order_type)=="contribution")
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Name</th>
                                        <th scope="col">{{strtolower($model->order_type)!="contribution" ?  "Estimated Taxes and Fee:" : "Estimated Fee:"}}</th>
                                        <th scope="col">Subtotal</th>
                                        <th scope="col">Credit Apply</th>
                                        <th scope="col">Contributed</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($model->orderItems as $item)
                                        <tr>
                                            <td class="border-top-zero">
                                                <a target="_blank"
                                                   href="{{route('show.wishlist.item',$item->wishlistItems->giftee_wishlist_id)}}">
                                                    @if($item->item_image!="")
                                                        <img width="120" height="120"
                                                             src="{{ asset("storage/uploads/wishlist_item/".$item->item_image)}}"
                                                             alt="image" class="img-fluid">

                                                    @else
                                                        <img width="120" height="120"
                                                             src="{{ asset('image/web/upload-pre.png')}}" alt="image"
                                                             class="img-fluid">

                                                    @endif
                                                    @php
                                                        $perecntage = 0;
                                                        $item_amount = ($item->wishlistItems->price * $item->wishlistItems->quantity) + $item->wishlistItems->shipping_cost + $item->wishlistItems->service_fee + $item->wishlistItems->tax_rate;
                                                    @endphp
                                                    @if($item->wishlistItems->collected_amount > 0)
                                                        @php
                                                            $percentage = ceil(($item->wishlistItems->collected_amount/$item_amount) * 100)
                                                        @endphp
                                                    @endif
                                                    <div class="checkout_section">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-success progress-bar-striped active"
                                                                 data-percentage="{{$percentage}}" role="progressbar"
                                                                 aria-valuenow="{{$percentage}}" aria-valuemin="0"
                                                                 aria-valuemax="100" style="width: {{$percentage}}%;">{{$percentage}}
                                                                % Completed
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>
                                            <td class="border-top-zero">
                                                <a target="_blank" class="amount-pink-col"
                                                   style="text-decoration: underline"
                                                   href="{{route('show.wishlist.item',\App\Helpers\Common::encrypt_decrypt($item->wishlistItems->giftee_wishlist_id))}}">
                                                    {{$item->item_name}}
                                                </a>
                                            </td>
                                            <td class="border-top-zero">
                                                ${{\App\Helpers\Common::numberFormat($model->processing_fee)}}</td>
                                            <td class="border-top-zero">
                                                ${{\App\Helpers\Common::numberFormat($model->subtotal)}}</td>
                                            <td class="border-top-zero"> -
                                                ${{\App\Helpers\Common::numberFormat($model->credit_apply)}}</td>
                                            <td class="border-top-zero">
                                                ${{\App\Helpers\Common::numberFormat($model->total_amount)}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @elseif(strtolower($model->order_type)=="giftoffer")
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Qty</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Shipping</th>
                                        <th scope="col">Subtotal</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($model->orderItems as $item)
                                        <tr>
                                            <td class="border-top-zero">

                                                @if($item->item_image!="")
                                                    <img width="120" height="120"
                                                         src="{{ asset("storage/uploads/offer_gift/".$item->item_image)}}"
                                                         alt="image" class="img-fluid">

                                                @else
                                                    <img width="120" height="120"
                                                         src="{{ asset('image/web/upload-pre.png')}}" alt="image"
                                                         class="img-fluid">

                                                @endif

                                            </td>
                                            <td class="border-top-zero">
                                                {{$item->item_name}}
                                            </td>
                                            <td class="border-top-zero">
                                                {{$item->item_qty}}</td>
                                            <td class="border-top-zero">
                                                ${{\App\Helpers\Common::numberFormat($item->item_price)}}</td>
                                            <td class="border-top-zero">
                                                ${{\App\Helpers\Common::numberFormat($item->item_shipping_price)}}</td>
                                            <td class="border-top-zero">
                                                ${{\App\Helpers\Common::numberFormat(($item->item_qty * $item->item_price) + $item->item_shipping_price)}}</td>
                                            <td class="border-top-zero">
                                                {{$model->status}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else

                                @foreach($model->orderItems as $item)
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Shipping</th>
                                            <th scope="col">Subtotal</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $shipping_fee = $item->item_expedited_shipping ==1 ? $item->item_expedited_shipping_price :$item->item_shipping_price
                                        @endphp
                                        <tr>
                                            <td class="border-top-zero">

                                                <a target="_blank"
                                                   href="{{route('show.wishlist.item',$item->wishlistItems->giftee_wishlist_id)}}">
                                                    @if($item->item_image!="")
                                                        <img width="120" height="120"
                                                             src="{{ asset("storage/uploads/wishlist_item/".$item->item_image)}}"
                                                             alt="image" class="img-fluid">

                                                    @else
                                                        <img width="120" height="120"
                                                             src="{{ asset('image/web/upload-pre.png')}}" alt="image"
                                                             class="img-fluid">

                                                    @endif
                                                </a>

                                            </td>
                                            <td class="border-top-zero">
                                                <a target="_blank" class="amount-pink-col"
                                                   style="text-decoration: underline"
                                                   href="{{route('show.wishlist.item',\App\Helpers\Common::encrypt_decrypt($item->wishlistItems->giftee_wishlist_id))}}">
                                                    {{$item->item_name}}
                                                </a>
                                            </td>
                                            <td class="border-top-zero">
                                                {{$item->item_qty}}</td>
                                            <td class="border-top-zero">
                                                ${{\App\Helpers\Common::numberFormat($item->item_price)}}</td>
                                            <td class="border-top-zero">
                                                ${{\App\Helpers\Common::numberFormat($shipping_fee)}}</td>
                                            <td class="border-top-zero">
                                                ${{\App\Helpers\Common::numberFormat(($item->item_qty * $item->item_price) + $shipping_fee)}}</td>
                                            <td class="border-top-zero">
                                                {{$item->item_status}}</td>
                                        </tr>
                                        </tbody>
                                    </table>

                                    <div class="design-process-content">
                                        <h5 class="order-item-content ml-3 mt-0 mb-3" >Order Timeline</h5>
                                        <ul>
                                            @foreach($item->orderItemStatusHistory as $statusHistory)
                                                <li class="text-left pl-3">{{\App\Helpers\Common::CTL($statusHistory->created_at, false)}}
                                                    <span>
                                                        @if(strtolower($statusHistory->status)=="shipped")
                                                            <strong>{{$statusHistory->status}}</strong>
                                                         @else
                                                        <strong>{{$statusHistory->status}} : </strong>
                                                        {{$statusHistory->description}}
                                                        @endif
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ORDER ITEM SECTION END -->
    @if(strtolower($model->order_type)!="cart")
        <!-- ORDER PROCESSING SECTION BEGIN -->
        <section class="design-process-section" id="process-tab">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-12">

                        @if(!empty($item->orderItemStatusHistory))
                        <h5 class="order-item-content">Order Timeline</h5>
                        <div class="design-process-content">
                            <ul>
                                @if(!empty($orderStatusHistory))
                                    @foreach($orderStatusHistory as $statusHistory)
                                        <li class="text-left pl-3">{{\App\Helpers\Common::CTL($statusHistory->created_at, false)}}
                                            <span>
                                            <strong>{{$statusHistory->status}} : </strong>
                                            @if(strtolower($statusHistory->status)!="pending" && strtolower($statusHistory->status)!="shipped")
                                                    {{$statusHistory->description}}
                                                @endif
                                        </span>
                                        </li>
                                    @endforeach
                                @else
                                    @foreach($model->orderStatusHistory as $statusHistory)
                                        <li class="text-left pl-3">{{\App\Helpers\Common::CTL($statusHistory->created_at, false)}}
                                            <span>
                                                <strong>{{$statusHistory->status}} : </strong>
                                                @if(strtolower($statusHistory->status)!="pending" && strtolower($statusHistory->status)!="shipped")
                                                    {{$statusHistory->description}}
                                                @endif
                                            </span>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
        </section>
        <!-- ORDER PROCESSING SECTION END -->
    @endif
@endsection
