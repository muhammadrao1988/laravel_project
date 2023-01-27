@php
    $item_detail = session('gift_offer_cart')[1];
     $item_amount = ($item_detail['price'] * $item_detail['quantity']) + $item_detail['shipping_cost'];
     $all_item_total = $item_amount;
@endphp
<div class="qty-box">
    <img src="{{ asset("storage/uploads/offer_gift/".$item_detail['picture'])}}" alt="image" class="img-fluid">
    <div class="qty-box-main">
        <h5 class="qty-box-heading">
            <span class="qty-box-price">Unit Price: ${{\App\Helpers\Common::numberFormat($item_detail['price'])}}</span>
            <span class="qty-box-price">Qty: {{\App\Helpers\Common::numberFormat( $item_detail['quantity'])}}</span>
            Name: {{$item_detail['gift_name']}}
            <br>
            Merchant: {{$item_detail['merchant']}}
            <br>
            Details: {{$item_detail['gift_details']}}
        </h5>
    </div>
</div>
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
            $estimated = \App\Helpers\Common::estimated_tax($item_amount,$item_detail['to_user'],$item_amount,"");
            $estimated = $estimated - $item_amount;
            $subtotal = 0;
            if($use_credit){
                $total = $all_item_total;
                $subtotal = $total;
                $credit_apply_subtotal = 0;
                $apply_credit = $auth_user_web->credit - $total;
                if($apply_credit <= 0){
                   $apply_credit = $auth_user_web->credit;
                }else{
                   $apply_credit = $subtotal;
                }
                $total = $total - $auth_user_web->credit;
                if($total<0){
                   $total = 0;
                   //preziezz fill will not calculate. Only state tax and stripe fee will calculate
                    $credit_apply_subtotal = $subtotal;
                }
                $estimated = \App\Helpers\Common::estimated_tax($total,$item_detail['to_user'],$subtotal,"",$credit_apply_subtotal);
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
                $total = $all_item_total + $estimated;
            }
        @endphp
    <li class="summary-box-list">
        Item Amount: <span
                class="pull-right summary-box-text-color">${{\App\Helpers\Common::numberFormat($item_detail['price'] * $item_detail['quantity'])}}</span>
    </li>
    <li class="summary-box-list">
        Shipping: <span
                class="pull-right summary-box-text-color">${{\App\Helpers\Common::numberFormat($item_detail['shipping_cost'])}}</span>
    </li>
    <li class="summary-box-list"><br>Estimated taxes and fee <a href="javascript:;" data-toggle="modal"
                                                                 data-target="#estimatedModal"><i
                    class="fa fa-info-circle info-icon" aria-hidden="true"></i></a> <span
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
        <label for="note" class="accept-donation">Your Message</label>
        <textarea style="width: 100%" name="gift_offer_note"  id="gift_offer_note" cols="30"
                  rows="3"></textarea>

    <input type="hidden" class="order_actual_total_amount_gpay" id="order_actual_total" value="{{\App\Helpers\Common::decimalPlace($total,2)}}">
</ul>