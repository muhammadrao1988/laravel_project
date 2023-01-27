
    @foreach (session('contributed_cart') as $id => $details)
        @php
            $item_detail = \App\Models\GifteeWishListItem::find($id);
            if(empty($item_detail)){
                echo "Unable to proceed";
                return false;
            }


            $previous_collection = $previous_collection + $item_detail->collected_amount;
            $item_amount = ($item_detail->price * $item_detail->quantity) + $item_detail->shipping_cost + $item_detail->service_fee + $item_detail->tax_rate;
            $contributed_amount = $contributed_amount + $details['contributed_amount'];
            $all_item_total = $all_item_total + $item_amount;

            $percentage = 0
        @endphp
    <div class="qty-box">
        <a href="{{route('show.wishlist.item',\App\Helpers\Common::encrypt_decrypt($item_detail->giftee_wishlist_id,"encrypt"))}}">
        @if($item_detail->picture=="")
            <img src="{{ asset('image/web/upload-pre.png')}}"  alt="image" class="img-fluid">
        @else
            <img src="{{ asset("storage/uploads/wishlist_item/".$item_detail->picture)}}"  alt="image" class="img-fluid">
        @endif
        </a>
        <div class="qty-box-main">
            <a href="{{route('show.wishlist.item',\App\Helpers\Common::encrypt_decrypt($item_detail->giftee_wishlist_id,"encrypt"))}}">
            <h5 class="qty-box-heading"><span class="qty-box-price">${{\App\Helpers\Common::numberFormat($item_amount)}}</span>
               {{$item_detail->gift_name}}
            </h5>
            @if($item_detail->collected_amount > 0)
                @php
                    $percentage = ceil(($item_detail->collected_amount/$item_amount) * 100)
                @endphp
            @endif

            <div class="progress ">
                <div
                        class="progress-bar progress-bar-success progress-bar-striped active" data-percentage="{{$percentage}}"
                        role="progressbar" aria-valuenow="{{$percentage}}" aria-valuemin="0"
                        aria-valuemax="100" style="width: {{$percentage}}%">
                    <span id="current-progress">{{$percentage}}% Completed</span>
                </div>
            </div>
            </a>
        </div>
        <input type="hidden" class="wishlist_id" value="{{$item_detail->id}}">
    </div>
    @endforeach
    <span class="divider-line"></span>


<h6 class="summary-box-heading">Order Summary</h6>
<ul>
    @if($auth_user_web && $auth_user_web->credit > 0)
        <li class="summary-box-list">
            <input type="checkbox" id="use_prezziez_credit" {{$use_credit ? "checked" : ""}}> Use Prezziez Credit:
            <span class="pull-right summary-box-text-color">
                                ${{\App\Helpers\Common::numberFormat($auth_user_web->credit)}}
                            </span>
            <hr>
        </li>
    @endif
        @php
            $estimated = \App\Helpers\Common::estimated_tax_contributed($contributed_amount);
            $estimated = $estimated - $contributed_amount;
            $subtotal = 0;

            if($use_credit){
                    $total =$contributed_amount;
                   $subtotal = $total;
                   $apply_credit = $auth_user_web->credit - $total;
                   if($apply_credit <= 0){
                       $apply_credit = $auth_user_web->credit;
                   }else{
                       $apply_credit = $subtotal;
                   }
                   $total = $total - $auth_user_web->credit;
                   if($total<0){
                       $total = 0;
                   }
                   $estimated = \App\Helpers\Common::estimated_tax_contributed($total);
                    $estimated = $estimated - $total;
                    if($estimated < 0){
                        $estimated = 0;
                    }
                    $subtotal = $subtotal + $estimated;
                    $total = $total + $estimated;
            }else{
                $total =$contributed_amount + $estimated;
            }
        @endphp
    <li  class="summary-box-list">
        Remaining Amount: <span class="pull-right summary-box-text-color">${{\App\Helpers\Common::numberFormat($all_item_total - $previous_collection)}}</span>
    </li >
        <li  class="summary-box-list">
           Contributed Amount: <span class="pull-right summary-box-text-color">$
                <input class="contributed_amount_update" type="number" value="{{$contributed_amount}}">
                <br>
                <a href="javascript:;" class="contribute_update" style="color: #DBAEAC;font-size: 12px">Click here to update</a>
            </span>
        </li >


    <li class="summary-box-list">Estimated fee{{-- <a href="javascript:;" data-toggle="modal"
                                                             data-target="#estimatedModal"><i
                    class="fa fa-info-circle info-icon" aria-hidden="true"></i></a>--}} <span
                class="pull-right summary-box-text"><strong>
                                    ${{\App\Helpers\Common::numberFormat($estimated)}}
                                </strong></span>
    </li>
    @php


    @endphp

    @if($use_credit && $apply_credit > 0)
        <hr>
            <li class="summary-box-list">Subtotal: <span class="pull-right summary-box-text-color">
                      ${{\App\Helpers\Common::numberFormat($subtotal)}}</span></li>
            <li class="summary-box-list">Credit Apply: <span class="pull-right summary-box-text-color">
                    -  ${{\App\Helpers\Common::numberFormat($apply_credit)}}</span></li>
            <li class="summary-box-list">Total: <span class="pull-right summary-box-text-color">
                      ${{\App\Helpers\Common::numberFormat($total)}}</span></li>

    @else
            <li class="summary-box-list">Total: <span class="pull-right summary-box-text-color">
                      ${{\App\Helpers\Common::numberFormat($total)}}</span></li>
    @endif

    <input type="hidden" class="order_actual_total_amount_gpay" id="order_actual_total" value="{{\App\Helpers\Common::decimalPlace($total,2)}}">


</ul>