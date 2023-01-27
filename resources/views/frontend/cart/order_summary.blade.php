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
        $subtotal = 0;
        $apply_credit = 0;
        if($use_expedited_shipping){
            $total = $total + $expedited_shipping_fee;
        }else{
            $total = $total + $shipping_fee ;
        }
        $estimated = @\App\Helpers\Common::estimated_tax($total,$user_id_giftee,$total,"",0);
        $estimated = $estimated - $total;
        if($use_credit){
            $subtotal = $total;
            $credit_apply_subtotal = 0;
            $apply_credit = $auth_user_web->credit - $total;
            if($apply_credit <= 0){
                $apply_credit = $auth_user_web->credit;
            }else{
                $apply_credit = $subtotal;
            }

            $total = $total - $auth_user_web->credit;

            if($total <= 0){
                $total = 0;
                //preziezz fill will not calculate. Only state tax and stripe fee will calculate
                $credit_apply_subtotal = $subtotal;

            }
            $estimated = @\App\Helpers\Common::estimated_tax($total,$user_id_giftee,$subtotal,"",$credit_apply_subtotal);
             if($credit_apply_subtotal > 0){
                $total = $credit_apply_subtotal;
             }
             $estimated = $estimated - $total;
            if($estimated < 0){
                $estimated = 0;
            }
            $subtotal = $subtotal + $estimated;
            if($credit_apply_subtotal > 0){
                $total = $estimated;
            }else{
             $total = $total + $estimated;
            }
        }else{
            $total = $total + $estimated;
        }
    @endphp
    <li class="summary-box-list">{{$use_credit ? "Item" : ""}} Subtotal ({{$cart_count}} Item{{$cart_count > 1 ? "s" : "" }})
        <span class="pull-right summary-box-text"><strong>${{\App\Helpers\Common::numberFormat($sub_total)}}</strong></span>
    </li>
    <li class="summary-box-list" style="{{!empty($use_expedited_shipping) ? 'text-decoration:line-through' : ''}}">
        Shipping fee
        <span class="pull-right summary-box-text"
              style="{{!empty($use_expedited_shipping) ? 'text-decoration:line-through' : ''}}">
            <strong>${{ \App\Helpers\Common::numberFormat($shipping_fee) }}</strong>
        </span></li>
        @if($expedited_shipping_fee > 0)
            <li class="summary-box-list">
                <input type="checkbox" id="use_expedited_shipping" {{$page=="cart" ? "" : "disabled"}} {{$use_expedited_shipping ? "checked" : ""}}> Use Expedited
                Shipping:
                <span class="pull-right summary-box-text-color">
                                        ${{\App\Helpers\Common::numberFormat($expedited_shipping_fee)}}
                                    </span>
                <hr>
            </li>
        @endif
    <li class="summary-box-list">Estimated taxes and fee <a href="javascript:;" data-toggle="modal"
                                                             data-target="#estimatedModal"><i
                    class="fa fa-info-circle info-icon" aria-hidden="true"></i></a> <span
                class="pull-right summary-box-text"><strong>
                                    ${{\App\Helpers\Common::numberFormat($estimated)}}
                                </strong></span>
    </li>

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
@if($page=="cart")
    <form action="{{route('checkout')}}" method="POST">
        @csrf
        <input type="hidden" name="total_amount" value="{{ $total }}">
        <label for="note" class="accept-donation">You can add your note here.</label>
        <textarea name="note" id="id" cols="30"
                  rows="3">{{!empty(session('order_summary')['note']) ? session('order_summary')['note'] : "" }}</textarea>
        <p class="accept-donation">
            <input id="recieve-updates" name="recieve_updates" value="1" type="checkbox"
                   {{!empty(session('order_summary')['recieve_purchase_status']) ? "checked" : "" }}
                   class="accept-donation-check">Receive status of purchase.
        </p>
        <button type="submit" class="checkout-buttn">Proceed To Checkout</button>
        {{-- <a class="checkout-buttn">Proceed To Checkout</a> --}}
    </form>
@else
    <form>
        @if(!empty(session('order_summary')['note']))
            <label class="accept-donation" for="note">Your Note</label><br>
            <textarea disabled name="note" id="id" cols="30" rows="3">{{ session('order_summary')['note']}}</textarea>
        @endif
        @if(!empty(session('order_summary')['recieve_purchase_status']))
            <p class="accept-donation">
                <input id="recieve-updates" name="recieve_updates" value="1" type="checkbox" checked disabled
                       class="accept-donation-check">Receive status of purchase.
            </p>
        @else
                <p class="accept-donation">
                    <input id="recieve-updates" name="recieve_updates" value="1" type="checkbox"  disabled
                           class="accept-donation-check">Receive status of purchase.
                </p>
        @endif

    </form>
@endif