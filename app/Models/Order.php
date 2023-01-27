<?php

namespace App\Models;
use App\Helpers\Common;
use App\Jobs\SendEmailJob;
use App\Models\Category;
use App\Models\User;
use App\Models\GifteeWishlistItem;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $table = 'orders';

    public static $module = "Order";
    protected $fillable = [
        'id',
        'order_num',
        'order_type',
        'wishlist_item_id',
        'user_id',
        'to_user',
        'billing_id',
        'subtotal',
        'expedited_shipping',
        'shipping_fee',
        'processing_fee',
        'use_credit',
        'credit_apply',
        'total_amount',
        'giftee_username',
        'order_step',
        'status',
        'surprise',
        'recieve_purchase_status',
        'payment_method',
        'payment_id',
        'prezziez_fee',
        'payment_processing_fee',
        'taxes',
        'active',
        'return_status',
        'note'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }

    public function orderStatusHistory(){
        return $this->hasMany(OrderStatusTimeline::class);
    }

    public function fromUser(){
        return $this->belongsTo(Website::class, 'user_id', 'id');
    }

    public function toUser(){
        return $this->belongsTo(Website::class, 'to_user', 'id');
    }

    public function billingInfo(){
        return $this->belongsTo(BillingInfo::class, 'billing_id', 'id');
    }

    public static function getListCartOrders()
    {
        $return = self::with(['fromUser','toUser'])->where('order_type','Cart');
        if (request()->input('orderStatus')!=""){
            $return = $return->where('status','=',request()->input('orderStatus'));
        }
        if (request()->input('orderId')!=""){
            $return = $return->where('id','=',request()->input('orderId'));
        }
        if (request()->input('returnRequest')!=""){
            $return = $return->where('return_status','=',(request()->input('returnRequest')=="no" ? 0 : 1));
        }
        if (request()->input('orderNum')!=""){
            $return = $return->where('order_num','LIKE','%'.request()->input('orderNum').'%');
        }

        return $return;
    }

    public static function getListGiftOfferOrders()
    {
        $return = self::with(['fromUser','toUser'])->where('order_type','GiftOffer');
        if (request()->input('orderStatus')!=""){
            $return = $return->where('status','=',request()->input('orderStatus'));
        }
        if (request()->input('orderId')!=""){
            $return = $return->where('id','=',request()->input('orderId'));
        }
        if (request()->input('returnRequest')!=""){
            $return = $return->where('return_status','=',(request()->input('returnRequest')=="no" ? 0 : 1));
        }
        if (request()->input('orderNum')!=""){
            $return = $return->where('order_num','LIKE','%'.request()->input('orderNum').'%');
        }
        return $return;
    }

    public static function getListContributionOrders()
    {
        $return = \App\Models\GifteeWishListItem::with(['user','orders'])->where('accept_donation','=',1)->where('collected_amount','>',0);

        if (request()->input('orderStatus')!=""){
            $return = $return->where('giftee_wishlist_items.status','=',request()->input('orderStatus'));
        }
        if (request()->input('orderId')!=""){
            $return = $return->where('giftee_wishlist_items.id','=',request()->input('orderId'));
        }
        if (request()->input('returnRequest')!=""){
            $return = $return->join('orders','orders.wishlist_item_id','=','giftee_wishlist_items.id')->where('return_status','=',(request()->input('returnRequest')=="no" ? 0 : 1))->select("giftee_wishlist_items.*")->groupBy('giftee_wishlist_items.id');
        }
        if (request()->input('orderNum')!=""){
            $return = $return->where('giftee_wishlist_items.order_num','LIKE','%'.request()->input('orderNum').'%');
        }
        return $return;
    }

    public static function actionButtonsCart($data){
        $return = '<div class="btn-group" role="group">';

        if(\Common::canUpdate("CartOrders")) {
          $return .= '<a class="btn" href="'.route('cart_orders.show', $data->id).'" title="Manage Order">
                            <i class="fas fa-eye"></i>
                        </a>';
        }

        $return .= '</div>';
        return $return;
    }
    public static function actionButtonsGiftOffer($data){
        $return = '<div class="btn-group" role="group">';
        if(\Common::canUpdate("GiftOfferOrders")) {
            $return .= '<a class="btn" href="'.route('gift_offer_orders.show', $data->id).'" title="Manage Order">
                            <i class="fas fa-eye"></i>
                        </a>';
        }
        $return .= '</div>';
        return $return;
    }
    public static function actionButtonsContribution($data){
        $return = '<div class="btn-group" role="group">';
        if(\Common::canUpdate("ContributionOrders")) {
            $return .= '<a class="btn" href="'.route('contribution_orders.show', $data->id).'" title="Manage Order">
                            <i class="fas fa-eye"></i>
                        </a>';
        }
        $return .= '</div>';
        return $return;
    }

    public static function giftOfferAcceptedRejectedCancelled($request,$auth_user,$gift_offer,$step,$is_auto=false){
        try{
            DB::beginTransaction();
            $accepted_status = $request->accpeted_status;
            $lower_case_status = strtolower($accepted_status);

            $order_detail = $gift_offer->order;
            $gift_offer_num = $order_detail->id;
            $return_amount = 0;
            if($lower_case_status=="declined" || $lower_case_status=="cancelled") {
                $success_msg = "No worries! We will let the gifter know that their gift offer has been ".$accepted_status." and they will receive a refund";

                $payment_method = $order_detail->payment_method;
                if(strtolower($payment_method)=="stripe"){
                    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                    $return_amount = $order_detail->subtotal + $order_detail->shipping_fee + $order_detail->taxes - $order_detail->credit_apply;
                    $return_amount = Common::decimalPlace($return_amount,2);

                    $charge = $stripe->refunds->create([
                        'charge' => $order_detail->payment_id,
                        'amount' => $return_amount * 100
                    ]);
                    if ($charge['status'] == 'succeeded') {

                        if($order_detail->credit_apply > 0){
                            UserCreditHistory::create([
                                'debit' => 0,
                                'credit' => $order_detail->credit_apply,
                                'order_id' => $order_detail->id,
                                'order_item_id' => $gift_offer->id,
                                'user_id' => $order_detail->fromUser->id,
                            ]);
                            Website::find($order_detail->fromUser->id)->increment('credit',$order_detail->credit_apply);
                        }
                        ReturnTransaction::create ([
                            'order_id' => $order_detail->id,
                            'order_item_id' => $gift_offer->id,
                            'previous_charge_id' => $order_detail->payment_id,
                            'return_id' => $charge['id'],
                            'balance_transaction' => $charge['balance_transaction'],
                            'payment_intent' => $charge['payment_intent'],
                            'subtotal' => $order_detail->subtotal,
                            'shipping_fee' => $order_detail->shipping_fee,
                            'taxes' => $order_detail->taxes,
                            'credit_apply' => $order_detail->credit_apply,
                            'return_amount' => $return_amount,
                        ]);

                    } else {
                        DB::rollBack();
                        return ['status'=>false,'code'=>422,'errors'=> ['exception' => ["Unable to declined offer. The status return by payment gateway is ".$charge['status']]]];
                    }
                }
                else if(strtolower($payment_method)=="googlepay"){
                    if($order_detail->credit_apply > 0){
                        UserCreditHistory::create([
                            'debit' => 0,
                            'credit' => $order_detail->credit_apply,
                            'order_id' => $order_detail->id,
                            'order_item_id' => $gift_offer->id,
                            'user_id' => $order_detail->fromUser->id,
                        ]);
                        Website::find($order_detail->fromUser->id)->increment('credit',$order_detail->credit_apply);
                    }
                    $return_amount = $order_detail->subtotal + $order_detail->shipping_fee + $order_detail->taxes - $order_detail->credit_apply;
                    $return_amount = Common::decimalPlace($return_amount,2);

                    ReturnTransaction::create ([
                        'order_id' => $order_detail->id,
                        'order_item_id' => $gift_offer->id,
                        'previous_charge_id' => $order_detail->payment_id,
                        'return_id' => '',
                        'balance_transaction' => '',
                        'payment_intent' =>'',
                        'subtotal' => $order_detail->subtotal,
                        'shipping_fee' => $order_detail->shipping_fee,
                        'taxes' => $order_detail->taxes,
                        'credit_apply' => $order_detail->credit_apply,
                        'return_amount' => $return_amount,
                    ]);
                }else{
                    ReturnTransaction::create ([
                        'order_id' => $order_detail->id,
                        'order_item_id' => $gift_offer->id,
                        'previous_charge_id' => $order_detail->payment_id,
                        'return_id' => '',
                        'balance_transaction' => '',
                        'payment_intent' => '',
                        'subtotal' => 0,
                        'taxes' => 0,
                        'shipping_fee' => 0,
                        'credit_apply' => $order_detail->credit_apply,
                        'return_amount' => 0,
                    ]);
                    UserCreditHistory::create([
                        'credit' =>$order_detail->credit_apply,
                        'debit' => 0,
                        'order_id' => $order_detail->id,
                        'order_item_id' => $gift_offer->id,
                        'user_id' => $order_detail->fromUser->id,
                    ]);
                    Website::find($order_detail->fromUser->id)->increment('credit',$order_detail->credit_apply);
                }
            }
            else{
                $success_msg = "Congratulations! Your prezziez are on the way. We will let the gifter know that their gift offer has been accepted. You will receive a confirmation email shortly";

            }
            Order::where('id',$order_detail->id)->update(
                [
                    'status'=>$accepted_status,
                    'order_step'=>$step
                ]);
            OrderItem::where('id',$gift_offer->id)->update(
                [
                    'item_status'=>$accepted_status,
                    'giftee_item_specification'=>$lower_case_status=="accepted" ? $request->details_specification : $gift_offer->giftee_item_specification,
                    'rejected_returned_reason'=> $lower_case_status == "declined" || $lower_case_status=="cancelled" ? $request->gifter_message : null,

                ]);
            OrderStatusTimeline::create([
                'order_id' => $order_detail->id,
                'status' => $accepted_status,
                'description' => !empty($request->gifter_message) ? $request->gifter_message : "Offer has been ".$accepted_status,
            ]);
            DB::commit();
            //notification to giftee
            $is_auth =  empty($order_detail->fromUser->id) ? false : true;
            $fromUser = $is_auth ? $order_detail->fromUser : $order_detail->billingInfo;
            $billing_info = $order_detail->billingInfo;
            //$subject = "Gift offer # ".$order_detail->id." has been ".$accepted_status;
            $subject = $auth_user->username." has ".$accepted_status." your gift offer: ".$gift_offer->item_name;
            $notification_url = route('my-orders.show',$order_detail->id);
            if($is_auth){


                Notification::create([
                    'model_id' => $order_detail->id,
                    'model' => "OrderItem",
                    'user_id' => $fromUser->id,
                    'from_user_id' =>  $auth_user->id,
                    'url' => $notification_url,
                    'title' => $subject,
                    'description' => $subject,
                ]);
                if($lower_case_status=="cancelled" && !$is_auto){
                    $msg = "Your gift offer: ".$gift_offer->item_name." has  " . $accepted_status . " by Prezziez Admin. Click the link below for more details";
                }elseif($lower_case_status=="cancelled" && $is_auto){
                    $msg = "Your gift offer: ".$gift_offer->item_name." has  " . $accepted_status . " automatically, because we did not get any response from ".$fromUser->username." in last 30 days. Click the link below for more details";
                }else {
                    $msg = $subject . ". Click the link below for more details.";
                }
                $body = [
                    'msg'=>$msg,
                    'url'=> $notification_url,
                    'btn_text'=>"View Order Details",
                    'subject' => $subject,
                    'module' => "OrderItem",
                    //'send_to' => "muhammadrao1988@gmail.com",
                    'send_to' => $billing_info->email,
                ];
                dispatch(new SendEmailJob($body))->onQueue('default');
            }
            else{
                $msg = "Dear ".$billing_info->first_name.",";
                $custom_html = "<p>".$subject."</p>";
                if($lower_case_status!="accepted"){
                    if(strtolower($payment_method)=="googlepay"){
                        $custom_html .= "<p>Please contact prezziez admin for return amount. The amount will be $".Common::numberFormat($return_amount)."</p>";
                    }else {
                        $custom_html .= "<p>Your payment has been returned to your account.</p>";
                    }
                }
                $dec = ($lower_case_status=='accepted' ? 'Giftee Message: ' :ucfirst($accepted_status)." Reason: ");
                 $custom_html.= "<table>
                                        <tr>
                                            <th>Giftee Name:</th>
                                            <td><a href='".route('profileUrl',$auth_user->username)."' target='_blank' >".$auth_user->username."</a></td>
                                        </tr>
                                         <tr>
                                            <th>Order ID:</th>
                                            <td>".$gift_offer->order_id."</td>
                                        </tr>
                                        <tr>
                                            <th>Item Name:</th>
                                            <td>".$gift_offer->item_name."</td>
                                        </tr>
                                         <tr>
                                            <th>Item URL:</th>
                                            <td>".$gift_offer->item_url."</td>
                                        </tr>
                                        <tr>
                                            <th>Subtotal:</th>
                                            <td>$".$order_detail->subtotal."</td>
                                        </tr>
                                        <tr>
                                            <th>Shipping Fee:</th>
                                            <td>$".$order_detail->shipping_fee."</td>
                                        </tr>
                                        <tr>
                                            <th>Estimated Taxes and Fee:</th>
                                            <td>$".$order_detail->processing_fee."</td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td>$".$order_detail->total_amount."</td>
                                        </tr>
                                        <tr>
                                            <th>".$dec."</th>
                                            <td>".$request->gifter_message."</td>
                                        </tr>";
                if($lower_case_status!="accepted" && strtolower($payment_method)!="googlepay"){
                    $custom_html.=  "<tr>
                                    <th>Returned Amount:</th>
                                    <td>$".$return_amount."</td>
                                    </tr>
                                    <tr>
                                        <th>Transaction ID:</th>
                                        <td>".$charge['id']."</td>
                                    </tr>";
                }
                $custom_html.="</table><p>&nbsp;</p>";
                $body = [
                    'msg'=>$msg,
                    'custom_html'=>$custom_html,
                    'url'=> "",
                    'btn_text'=>"",
                    'subject' =>$subject,
                    'module' => "OrderItem",
                    'send_to' => $billing_info->email,
                ];
                dispatch(new SendEmailJob($body))->onQueue('default');
            }
            if($lower_case_status=="cancelled"){
                //notify to giftee
                $notification_name = ($is_auto ? " auto, due to 30 days inactivity." : " by Prezziez Admin");
                $notification_url = route('giftOfferDetail',\App\Helpers\Common::encrypt_decrypt($gift_offer->id));
                Notification::create([
                    'model_id' => $gift_offer->id,
                    'model' => "OrderItem",
                    'user_id' => $auth_user->id,
                    'from_user_id' =>  0,
                    'url' => $notification_url,
                    'title' => "Your gift offer: ".$gift_offer->item_name." has  cancelled ".$notification_name,
                    'description' =>"Your gift offer: ".$gift_offer->item_name." has   cancelled ".$notification_name,
                ]);
                $msg = "Your accepted gift offer: ".$gift_offer->item_name." has  cancelled ".$notification_name.". Please click below for more details.";
                $body = [
                    'msg'=>$msg,
                    'url'=> $notification_url,
                    'btn_text'=>"View Order Details",
                    'subject' => "Your gift offer: ".$gift_offer->item_name." has  cancelled",
                    'module' => "OrderItem",
                    //'send_to' => "muhammadrao1988@gmail.com",
                    'send_to' => $auth_user->email,
                ];
                dispatch(new SendEmailJob($body))->onQueue('default');

            }
            return ['status'=>true,'code'=>200,'msg'=> $success_msg];

        }catch (\Exception $e) {
            DB::rollBack();
            return ['status'=>false,'code'=>422,'errors'=> ['exception' => [$e->getMessage()]]];
        }
    }

    public static function giftOfferOtherStatus($request,$from_admin=true,$auth_user=false){

        try {
            if (empty($request->id)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Gift offer order id is missing"]]];
            }
            if (empty($request->status)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Gift offer action status is required"]]];
            }
            if (empty($request->status_description) && $request->status=="Cancelled") {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Please enter cancellation reason"]]];

            }
            if (empty($request->status_description) && $request->status=="Shipped") {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Please enter tracking info URL"]]];

            }
            DB::beginTransaction();
            $id = $request->id;
            $step = $request->step;
            $status = $request->status;
            $description = $request->status_description;
            $lower_case_status = strtolower($status);
            if ($from_admin) {
                $order_status_array = ["order processing", "order placed", "shipped", "delivered","received"];
            } else {
                $order_status_array = ["received"];
            }
            if (!in_array($lower_case_status, $order_status_array)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Invalid order status provided"]]];
            }

            $gift_offer = OrderItem::with(['order', 'order.fromUser', 'order.toUser', 'order.billingInfo'])
                ->where('id', '=', $id)->first();
            if (empty($gift_offer)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Gift offer not found"]]];

            }

            if ($auth_user) {
                if ($gift_offer->order->to_user != $auth_user->id) {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Record not found."]]];

                }
            }
            $order_current_status = strtolower($gift_offer->item_status);

            if ($lower_case_status == "order processing") {
                if ($order_current_status != "accepted") {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Only accepted offers can be process."]]];
                }
                $notify_to_giftee = true;
                $notify_to_gifter = false;
            } else if ($lower_case_status == "order placed") {
                if ($order_current_status != "order processing") {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Only process offers can be placed."]]];

                }
                $notify_to_giftee = true;
                $notify_to_gifter = false;

            } else if ($lower_case_status == "shipped") {
                if ($order_current_status != "order placed") {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Only order placed offers can be shipped."]]];
                }
                $notify_to_giftee = true;
                $notify_to_gifter = false;

            } else if ($lower_case_status == "delivered") {
                if ($order_current_status != "shipped") {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Only shipped offers can be delivered."]]];

                }
                $notify_to_giftee = true;
                $notify_to_gifter = false;

            } else if ($lower_case_status == "received") {
                if ($order_current_status != "delivered") {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Only delivered offers can be received."]]];
                }
                $notify_to_giftee = false;
                $notify_to_gifter = true;
            }else{
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Invalid status provided"]]];

            }
            $order_detail = $gift_offer->order;
            Order::where('id',$order_detail->id)->update(
                [
                    'status'=>$status,
                    'order_step'=>$step
                ]);
            OrderItem::where('id',$gift_offer->id)->update(
                [
                    'item_status'=>$status,
                ]);
            OrderStatusTimeline::create([
                'order_id' => $order_detail->id,
                'status' => $status,
                'description' => $description,
            ]);
            $gift_offer_num = $order_detail->id;
            $gift_offer_status = $order_detail->status;
            $fromUser = $gift_offer->order->fromUser;
            $toUser = $gift_offer->order->toUser;
            $billing_info = $gift_offer->order->billingInfo;
            $is_auth = empty($fromUser) ? false : true;
            if($notify_to_gifter){
                $notification_url = route('my-orders.show',$order_detail->id);

                $subject = "Your gift offer order:".$gift_offer_num." has been ".$status." .";
                if($is_auth){

                    Notification::create([
                        'model_id' => $order_detail->id,
                        'model' => "OrderItem",
                        'user_id' => $fromUser->id,
                        'from_user_id' =>  $toUser->id,
                        'url' => $notification_url,
                        'title' => $subject,
                        'description' => $subject,
                    ]);
                    $msg = $subject . ". Please click on below button for more details.";

                    $body = [
                        'msg'=>$msg,
                        'url'=> $notification_url,
                        'btn_text'=>"View Order Details",
                        'subject' => $subject,
                        'module' => "OrderItem",
                        //'send_to' => "muhammadrao1988@gmail.com",
                        'send_to' => $billing_info->email,
                    ];
                    dispatch(new SendEmailJob($body))->onQueue('default');
                }
                else{
                    $msg = "Dear ".$billing_info->first_name.",";
                    $custom_html = "<p>".$subject."</p>";

                    $custom_html.= "<table>
                                        <tr>
                                            <th>Giftee Name:</th>
                                            <td><a href='".route('profileUrl',$toUser->username)."' target='_blank' >".$toUser->username."</a></td>
                                        </tr>
                                         <tr>
                                            <th>Order ID:</th>
                                            <td>".$gift_offer->order_id."</td>
                                        </tr>
                                        <tr>
                                            <th>Item Name:</th>
                                            <td>".$gift_offer->item_name."</td>
                                        </tr>
                                         <tr>
                                            <th>Item URL:</th>
                                            <td>".$gift_offer->item_url."</td>
                                        </tr>";

                    $custom_html.="</table><p>&nbsp;</p>";
                    $body = [
                        'msg'=>$msg,
                        'custom_html'=>$custom_html,
                        'url'=> "",
                        'btn_text'=>"",
                        'subject' =>$subject,
                        'module' => "OrderItem",
                        'send_to' => $billing_info->email,
                    ];
                    dispatch(new SendEmailJob($body))->onQueue('default');

                }

            }
            if($notify_to_giftee){
                //notify to giftee
                if($lower_case_status=="delivered"){
                    $notification_title = "Your gift offer:" . $gift_offer_num . " has delivered. Please confirm you have received.";
                    $subject = "Your gift offer:".$gift_offer_num." has Delivered!";
                }else {
                    $notification_title = "The status of your gift offer: " . $gift_offer_num . " has changed from " . $gift_offer_status . " to " . $status;
                    $subject = "The status of your gift offer: ".$gift_offer_num." has changed";


                }
                $notification_url = route('giftOfferDetail',\App\Helpers\Common::encrypt_decrypt($gift_offer->id));
                Notification::create([
                    'model_id' => $gift_offer->id,
                    'model' => "OrderItem",
                    'user_id' => $toUser->id,
                    'from_user_id' =>  0,
                    'url' => $notification_url,
                    'title' =>$notification_title,
                    'description' =>$notification_title,
                ]);
                $msg = $notification_title;
                $body = [
                    'msg'=>$msg,
                    'url'=> $notification_url,
                    'btn_text'=>"View Order Details",
                    'subject' => $subject,
                    'module' => "OrderItem",
                    //'send_to' => "muhammadrao1988@gmail.com",
                    'send_to' => $toUser->email,
                ];
                dispatch(new SendEmailJob($body))->onQueue('default');
            }
            DB::commit();
            return ['status'=>true,'code'=>200,'msg'=> "order status has been updated successfully."];

        }catch (\Exception $e) {
            DB::rollBack();
            return ['status'=>false,'code'=>422,'errors'=> ['exception' => [$e->getMessage()]]];
        }

    }

    public static function giftOfferReturnManage($request,$from_admin=true,$auth_user=false){

        try {
            if (empty($request->id)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Gift offer order id is missing"]]];
            }
            if (empty($request->status_description)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Message is required"]]];

            }
            DB::beginTransaction();
            $id = $request->id;

            $description = $request->status_description;


            $gift_offer = OrderItem::with(['order', 'order.toUser'])
                ->where('id', '=', $id)->first();
            if (empty($gift_offer)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Gift offer not found"]]];

            }

            if ($auth_user) {
                if ($gift_offer->order->to_user != $auth_user->id) {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Record not found."]]];

                }
                if($gift_offer->return_description_gifte!=""){
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["You have already requested for return"]]];
                }
            }

            $order_detail = $gift_offer->order;
            Order::where('id',$order_detail->id)->update(
                [
                    'return_status'=>$from_admin==true ? 1 : 1,
                ]);
            OrderItem::where('id',$gift_offer->id)->update(
                [
                    'return_status_item'=>$from_admin==true ? 0 : 1,
                    $from_admin==true ? "return_description_admin" : "return_description_giftee" => $request->status_description
                ]);

            if($from_admin){
                //notify to giftee
                $notification_title = "You have received a message from Prezziez Admin in regards to your return of gift offer # ".$gift_offer->order->id;
                $subject = "Details about your return gift offer.";

                $notification_url = route('giftOfferDetail',\App\Helpers\Common::encrypt_decrypt($gift_offer->id));
                $toUser = $gift_offer->order->toUser;
                Notification::create([
                    'model_id' => $gift_offer->id,
                    'model' => "OrderItem",
                    'user_id' => $toUser->id,
                    'from_user_id' =>  0,
                    'url' => $notification_url,
                    'title' =>$notification_title,
                    'description' =>$notification_title,
                ]);
                $msg = $notification_title;
                $custom_html = "<p><strong>Message:</strong></p>
                                <p>".$request->status_description."</p>";
                $body = [
                    'msg'=>$msg,
                    'url'=> $notification_url,
                    'custom_html'=>$custom_html,
                    'btn_text'=>"View Order Details",
                    'subject' => $subject,
                    'module' => "OrderItem",
                    //'send_to' => "muhammadrao1988@gmail.com",
                    'send_to' => $toUser->email,
                ];
                dispatch(new SendEmailJob($body))->onQueue('default');
            }
            DB::commit();
            return ['status'=>true,'code'=>200,'msg'=> "record updated successfully."];

        }catch (\Exception $e) {
            DB::rollBack();
            return ['status'=>false,'code'=>422,'errors'=> ['exception' => [$e->getMessage()]]];
        }

    }

    public static function cartItemCancelled($request,$auth_user,$gift_offer,$step,$is_auto=false){
        try{

            $accepted_status = $request->accpeted_status;
            $lower_case_status = strtolower($accepted_status);
            $order_detail = $gift_offer->order;
            if($lower_case_status=="cancelled" || $lower_case_status=="refunded") {
                DB::beginTransaction();
                if($order_detail->expedited_shipping==1){
                    $return_amount = ($gift_offer->item_price * $gift_offer->item_qty) + $gift_offer->item_expedited_shipping_price;
                }else{
                    $return_amount = ($gift_offer->item_price * $gift_offer->item_qty) + $gift_offer->item_shipping_price;
                }

                $taxes = 0;
                // return tax if all order item cancelled.
                if($order_detail->taxes > 0) {
                    $count_order_item = OrderItem::where('order_id','=',$order_detail->id)->count();
                    if($count_order_item==1) {
                        $apply_tax = true;

                    }else{
                        $cancelled_count_order_item = OrderItem::where('order_id','=',$order_detail->id)->where('item_status','=','Cancelled')->count();
                        $apply_tax = false;
                        if(($cancelled_count_order_item + 1) == $count_order_item){
                            $apply_tax = true;
                        }

                    }
                    if($apply_tax){

                        $taxes =  $order_detail->taxes;
                    }
                }
                $return_amount = $return_amount + $taxes;
                $return_amount = Common::decimalPlace($return_amount,2);
                ReturnTransaction::create ([
                    'order_id' => $order_detail->id,
                    'order_item_id' => $gift_offer->id,
                    'previous_charge_id' => $order_detail->payment_id,
                    'return_id' => '',
                    'balance_transaction' => '',
                    'payment_intent' => '',
                    'subtotal' => 0,
                    'shipping_fee' => 0,
                    'credit_apply' => $return_amount,
                    'return_amount' => 0,
                    'taxes' => $taxes,
                ]);
                UserCreditHistory::create([
                    'credit' => $return_amount,
                    'debit' => 0,
                    'order_id' => $order_detail->id,
                    'order_item_id' => $gift_offer->id,
                    'user_id' => $auth_user->id,
                ]);
                Website::find($auth_user->id)->increment('credit',$return_amount);
                OrderItem::where('id',$gift_offer->id)->update(
                    [
                        'item_status'=>$accepted_status,
                        'rejected_returned_reason'=> $lower_case_status == "refunded" || $lower_case_status=="cancelled" ? $request->gifter_message : null,

                    ]);
                OrderItemStatusTimeline::create([
                    'order_id' => $order_detail->id,
                    'order_item_id' => $gift_offer->id,
                    'wishlist_item_id' => $gift_offer->wishlist_item_id,
                    'status' => $accepted_status,
                    'description' => !empty($request->gifter_message) ? $request->gifter_message : "Wishlist item has been ".$accepted_status." by Prezziez Admin.",
                ]);
                $order_status = self::updateCartOrder($order_detail->id);
                Order::where('id',$order_detail->id)->update(
                    [
                        'status'=>$order_status,
                        'order_step'=>$step
                    ]);
                DB::commit();
                $gift_item_name = $gift_offer->item_name;
                if(strlen($gift_item_name) > 20){
                    $gift_item_name = substr($gift_item_name,0,19)."..";
                }
                //notify to giftee
                $notification_name = " by Prezziez Admin";
                $notification_url = route('show.wishlist.item.detail',\App\Helpers\Common::encrypt_decrypt($gift_offer->wishlist_item_id));
                $msg = "Your wishlist item: ".$gift_offer->item_name.", has been cancelled ".$notification_name.". Please click the link below for details of your cancelled order:";
                $msg_notification = "Your wishlist item: ".$gift_item_name.", has been cancelled ".$notification_name.".";

                Notification::create([
                    'model_id' => $gift_offer->id,
                    'model' => "OrderItem",
                    'user_id' => $auth_user->id,
                    'from_user_id' =>  0,
                    'url' => $notification_url,
                    'title' => $msg_notification,
                    'description' =>$msg_notification,
                ]);


                $body = [
                    'msg'=>$msg,
                    'url'=> $notification_url,
                    'btn_text'=>"View Item",
                    'subject' => "Your Prezziez order has been cancelled",
                    'module' => "OrderItem",
                    //'send_to' => "muhammadrao1988@gmail.com",
                    'send_to' => $auth_user->email,
                ];
                dispatch(new SendEmailJob($body))->onQueue('default');
            }


            return ['status'=>true,'code'=>200,'msg'=> "Cancelled successfully."];

        }catch (\Exception $e) {
            DB::rollBack();
            return ['status'=>false,'code'=>422,'errors'=> ['exception' => [$e->getMessage()]]];
        }
    }

    public static function cartItemOtherStatus($request,$from_admin=true,$auth_user=false){

        try {
            if (empty($request->id)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["order item id is missing"]]];
            }
            if (empty($request->status)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["status is required"]]];
            }
            if (empty($request->status_description) && $request->status=="Cancelled") {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Please enter cancellation reason"]]];

            }
            if (empty($request->status_description) && $request->status=="Shipped") {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Please enter tracking info URL"]]];

            }
            DB::beginTransaction();
            $id = $request->id;
            $step = $request->step;
            $status = $request->status;
            $description = $request->status_description;
            $lower_case_status = strtolower($status);
            if ($from_admin) {
                $order_status_array = [ "order placed", "shipped", "delivered","received"];
            } else {
                $order_status_array = ["received"];
            }
            if (!in_array($lower_case_status, $order_status_array)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Invalid order status provided"]]];
            }

            $gift_offer = OrderItem::with(['order', 'order.fromUser', 'order.toUser', 'order.billingInfo'])
                ->where('id', '=', $id)->first();
            if (empty($gift_offer)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Order item not found"]]];

            }

            if ($auth_user) {
                if ($gift_offer->order->to_user != $auth_user->id) {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Record not found."]]];

                }
            }
            $order_current_status = strtolower($gift_offer->item_status);

            if ($lower_case_status == "order placed") {
                if ($order_current_status != "order processing") {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Only order processing status can be placed."]]];

                }
                $notify_to_giftee = true;
                $notify_to_gifter = false;

            } else if ($lower_case_status == "shipped") {
                if ($order_current_status != "order placed") {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Only order placed status can be shipped."]]];
                }
                $notify_to_giftee = true;
                $notify_to_gifter = false;

            } else if ($lower_case_status == "delivered") {
                if ($order_current_status != "shipped") {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Only shipped status can be delivered."]]];

                }
                $notify_to_giftee = true;
                $notify_to_gifter = false;

            } else if ($lower_case_status == "received") {
                if ($order_current_status != "delivered") {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Only delivered status can be received."]]];
                }
                $notify_to_giftee = false;
                $notify_to_gifter = true;
            }else{
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Invalid status provided"]]];

            }
            $order_detail = $gift_offer->order;
           /* Order::where('id',$order_detail->id)->update(
                [
                    'status'=>$status,
                    'order_step'=>$step
                ]);*/
            OrderItem::where('id',$gift_offer->id)->update(
                [
                    'item_status'=>$status,
                ]);
            OrderItemStatusTimeline::create([
                'order_id' => $order_detail->id,
                'order_item_id' => $gift_offer->id,
                'wishlist_item_id' => $gift_offer->wishlist_item_id,
                'status' => $status,
                'description' => $description,
            ]);
            $order_status = self::updateCartOrder($order_detail->id);
            Order::where('id',$order_detail->id)->update(
                [
                    'status'=>$order_status,
                    'order_step'=>$step
                ]);
            $gift_offer_num = $order_detail->id;
            $gift_offer_status = $order_detail->status;
            $fromUser = $gift_offer->order->fromUser;
            $toUser = $gift_offer->order->toUser;
            $billing_info = $gift_offer->order->billingInfo;
            $is_auth = empty($fromUser) ? false : true;
            if($notify_to_gifter){
                $notification_url = route('my-orders.show',$order_detail->id);
                $gift_item_name = $gift_offer->item_name;
                if(strlen($gift_item_name) > 20){
                    $gift_item_name = substr($gift_item_name,0,19)."..";
                }

                $subject_notification = "Your Prezziez order:".$gift_item_name." has been ".$status.".";
                $subject = "Your Prezziez order has been ".$status;
                $notification_subject = "";
                if($is_auth){

                    Notification::create([
                        'model_id' => $order_detail->id,
                        'model' => "OrderItem",
                        'user_id' => $fromUser->id,
                        'from_user_id' =>  $toUser->id,
                        'url' => $notification_url,
                        'title' => $subject_notification,
                        'description' => $subject_notification,
                    ]);
                    $msg =  "Your Prezziez order:".$gift_offer->item_name." has been ".$status.".  Click the link below for more details:";

                    $body = [
                        'msg'=>$msg,
                        'url'=> $notification_url,
                        'btn_text'=>"View Details",
                        'subject' => $subject,
                        'module' => "OrderItem",
                        //'send_to' => "muhammadrao1988@gmail.com",
                        'send_to' => $billing_info->email,
                    ];
                    dispatch(new SendEmailJob($body))->onQueue('default');
                }
                else{
                    $msg = "Dear ".$billing_info->first_name.",";
                    $custom_html = "<p>".$toUser->username." has ".$status." your item order ".$gift_offer->item_name.".</p>";

                    $custom_html.= "<table>
                                        <tr>
                                            <th>Giftee Name:</th>
                                            <td><a href='".route('profileUrl',$toUser->username)."' target='_blank' >".$toUser->username."</a></td>
                                        </tr>
                                          <tr>
                                            <th>Order ID:</th>
                                            <td>".$gift_offer->order_id."</td>
                                        </tr>
                                         <tr>
                                            <th>Order Item ID:</th>
                                            <td>".$gift_offer->id."</td>
                                        </tr>
                                        <tr>
                                            <th>Item Name:</th>
                                            <td>".$gift_offer->item_name."</td>
                                        </tr>
                                         ";

                    $custom_html.="</table><p>&nbsp;</p>";
                    $body = [
                        'msg'=>$msg,
                        'custom_html'=>$custom_html,
                        'url'=> "",
                        'btn_text'=>"",
                        'subject' =>$subject,
                        'module' => "OrderItem",
                        'send_to' => $billing_info->email,
                    ];
                    dispatch(new SendEmailJob($body))->onQueue('default');

                }

            }
            if($notify_to_giftee){
                //notify to giftee
                $gift_item_name = $gift_offer->item_name;
                if(strlen($gift_item_name) > 20){
                    $gift_item_name = substr($gift_item_name,0,19)."..";
                }
                if($lower_case_status=="delivered"){
                    $notification_title = "Your wishlist item " . $gift_item_name . " has delivered. Please confirm you have received.";
                    $subject = "Your wishlist order: ".$gift_offer->item_name." has delivered!";
                }else {
                    $notification_title = "The status of your wishlist order: " . $gift_item_name . " has changed from " . $gift_offer_status . " to " . $status;
                    $subject = "The status of your wishlist order: ".$gift_offer->item_name." has changed";
                }
                $notification_url = route('show.wishlist.item.detail',\App\Helpers\Common::encrypt_decrypt($gift_offer->wishlist_item_id));
                Notification::create([
                    'model_id' => $gift_offer->id,
                    'model' => "OrderItem",
                    'user_id' => $toUser->id,
                    'from_user_id' =>  0,
                    'url' => $notification_url,
                    'title' =>$notification_title,
                    'description' =>$notification_title,
                ]);
                $msg = $notification_title;
                $body = [
                    'msg'=>$msg,
                    'url'=> $notification_url,
                    'btn_text'=>"View Item",
                    'subject' => $subject,
                    'module' => "OrderItem",
                    //'send_to' => "muhammadrao1988@gmail.com",
                    'send_to' => $toUser->email,
                ];
                dispatch(new SendEmailJob($body))->onQueue('default');
            }
            DB::commit();
            return ['status'=>true,'code'=>200,'msg'=> "order status has been updated successfully."];

        }catch (\Exception $e) {
            DB::rollBack();
            return ['status'=>false,'code'=>422,'errors'=> ['exception' => [$e->getMessage()]]];
        }

    }

    public static function cartItemReturnManage($request,$from_admin=true,$auth_user=false){

        try {
            if (empty($request->id)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["wishlist item id is missing"]]];
            }
            if (empty($request->status_description)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Message is required"]]];

            }
            DB::beginTransaction();
            $id = $request->id;

            $description = $request->status_description;


            $gift_offer = OrderItem::with(['order', 'order.toUser'])
                ->where('id', '=', $id)->first();
            if (empty($gift_offer)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["wishlist item not found"]]];

            }
            if(strtolower($gift_offer->order->order_type)!="cart"){
                return new JsonResponse(['errors' => ['general' => ['Order not found']]], 422);
            }

            if ($auth_user) {
                if ($gift_offer->order->to_user != $auth_user->id) {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Record not found."]]];

                }
                if($gift_offer->return_description_gifte!=""){
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["You have already requested for return"]]];
                }
            }

            $order_detail = $gift_offer->order;
            OrderItem::where('id',$gift_offer->id)->update(
                [
                    'return_status_item'=>$from_admin==true ? 0 : 1,
                    $from_admin==true ? "return_description_admin" : "return_description_giftee" => $request->status_description
                ]);
            if($auth_user){
                Order::where('id', $order_detail->id)->update(
                    [
                        'return_status' =>  1,
                    ]);
            }else {
                $order_item_returned_pending = OrderItem::where('order_id','=',$order_detail->id)->where('return_status_item','=',1)->count();
                Order::where('id', $order_detail->id)->update(
                    [
                        'return_status' => $order_item_returned_pending > 0 ? 1 : 1,
                    ]);
            }


            if($from_admin){
                //notify to giftee
                $gift_item_name = $gift_offer->item_name;
                if(strlen($gift_item_name) > 20){
                    $gift_item_name = substr($gift_item_name,0,19)."..";
                }
                $notification_title = "You have received a message from Prezziez Admin in regards to your return of wishlist item " . $gift_item_name . ".";
                $subject = "Details about your return";

                $notification_url = route('show.wishlist.item.detail',\App\Helpers\Common::encrypt_decrypt($gift_offer->wishlist_item_id));
                $toUser = $gift_offer->order->toUser;
                Notification::create([
                    'model_id' => $gift_offer->id,
                    'model' => "OrderItem",
                    'user_id' => $toUser->id,
                    'from_user_id' =>  0,
                    'url' => $notification_url,
                    'title' =>$notification_title,
                    'description' =>$notification_title,
                ]);
                $msg = "You have received a message from Prezziez Admin in regards to your return of wishlist item ". $gift_offer->item_name . ".";
                $custom_html = "<p><strong>Message:</strong></p>
                                <p>".$request->status_description."</p>";
                $body = [
                    'msg'=>$msg,
                    'url'=> $notification_url,
                    'custom_html'=>$custom_html,
                    'btn_text'=>"View Order Details",
                    'subject' => $subject,
                    'module' => "OrderItem",
                    //'send_to' => "muhammadrao1988@gmail.com",
                    'send_to' => $toUser->email,
                ];
                dispatch(new SendEmailJob($body))->onQueue('default');
            }
            DB::commit();
            return ['status'=>true,'code'=>200,'msg'=> "record updated successfully."];

        }catch (\Exception $e) {
            DB::rollBack();
            return ['status'=>false,'code'=>422,'errors'=> ['exception' => [$e->getMessage()]]];
        }

    }

    public static function updateCartOrder($order_id){
        $orders = OrderItem::where('order_id','=',$order_id)->get();
        $order_item_count = $orders->count();
        if($order_item_count > 0) {
            $received_item = $orders->where('item_status', '=', 'Received')->count();
            if ($received_item == $order_item_count) {
                return "Received";
            } else if ($received_item > 0) {
                return "Partially Received";
            }

            $delivered_item = $orders->where('item_status', '=', 'Delivered')->count();
            if ($delivered_item == $order_item_count) {
                return "Delivered";
            } else if ($delivered_item > 0) {
                return "Partially Delivered";
            }

            $shipped_item = $orders->where('item_status', '=', 'Shipped')->count();
            if ($shipped_item == $order_item_count) {
                return "Shipped";
            } else if ($shipped_item > 0) {
                return "Partially Shipped";
            }

            $order_placed_item = $orders->where('item_status', '=', 'Order Placed')->count();
            if ($order_placed_item == $order_item_count) {
                return "Order Placed";
            } else if ($order_placed_item > 0) {
                return "Partially Order Placed";
            }

            $cancelled_item = $orders->where('item_status', '=', 'Cancelled')->count();
            if ($cancelled_item == $order_item_count) {
                return "Cancelled";
            } else if ($cancelled_item > 0) {
                return "Partially Cancelled";
            }else{
                return "Order Processing";
            }
        }else{
            return "Order Processing";
        }


    }

    public static function contributedCancelled($request,$auth_user,$gift_offer,$step,$is_auto=false){
        try{

            $accepted_status = $request->accpeted_status;
            $lower_case_status = strtolower($accepted_status);
            $order_detail = $gift_offer->order;
            if($lower_case_status=="cancelled" || $lower_case_status=="refunded") {
                DB::beginTransaction();
                $return_amount = ($gift_offer->price * $gift_offer->quantity) + $gift_offer->shipping_cost + $gift_offer->shipping_tax_rate;
                //$taxes = Order::where('wishlist_item_id','=',$gift_offer->id)->sum('taxes');
                $taxes = $gift_offer->tax_rate;
                $return_amount = $return_amount + $taxes;
                $return_amount = Common::decimalPlace($return_amount,2);
                dd($return_amount);
                ReturnTransaction::create ([
                    'wishlist_item_id' => $gift_offer->id,
                    'previous_charge_id' => '',
                    'return_id' => '',
                    'balance_transaction' => '',
                    'payment_intent' => '',
                    'subtotal' => 0,
                    'shipping_fee' => 0,
                    'credit_apply' => $return_amount,
                    'return_amount' => 0,
                ]);

                UserCreditHistory::create([
                    'credit' => $return_amount,
                    'debit' => 0,
                    'order_id' => $gift_offer->id,
                    'order_item_id' => $gift_offer->id,
                    'user_id' => $auth_user->id,
                    'type' => "contribution",
                ]);
                Website::find($auth_user->id)->increment('credit',$return_amount);

                OrderItem::where('wishlist_item_id',$gift_offer->id)->update(
                    [
                        'item_status'=>$accepted_status,
                        'rejected_returned_reason'=> $lower_case_status == "refunded" || $lower_case_status=="cancelled" ? $request->gifter_message : null,

                    ]);
                Order::where('wishlist_item_id',$gift_offer->id)->update(
                    [
                        'status'=>$accepted_status,
                        'order_step'=>$step
                    ]);
                OrderStatusTimeline::create([
                    'type' => "item",
                    'order_id' => $gift_offer->id,
                    'status' => $accepted_status,
                    'description' => !empty($request->gifter_message) ? $request->gifter_message : "wishlist item has been ".$accepted_status." by Prezziez Admin.",
                ]);
                \App\Models\GifteeWishListItem::where('id',$gift_offer->id)->update(['status'=>$accepted_status]);


                DB::commit();
                $gift_item_name = $gift_offer->gift_name;
                if(strlen($gift_item_name) > 20){
                    $gift_item_name = substr($gift_item_name,0,19)."..";
                }
                //notify to giftee
                $notification_name = " by Prezziez Admin";
                $notification_url = route('show.wishlist.item.detail',\App\Helpers\Common::encrypt_decrypt($gift_offer->id));
                $msg = "Your wishlist item:  ".$gift_offer->gift_name.", has been cancelled by ".$notification_name.". Please click the link below for details of your cancelled order:";
                $msg_notification = "Your wishlist item:  ".$gift_item_name.", has been cancelled by ".$notification_name;

                Notification::create([
                    'model_id' => $gift_offer->id,
                    'model' => "GifteeWishListItem",
                    'user_id' => $auth_user->id,
                    'from_user_id' =>  0,
                    'url' => $notification_url,
                    'title' => $msg_notification,
                    'description' =>$msg_notification,
                ]);

                $body = [
                    'msg'=>$msg,
                    'url'=> $notification_url,
                    'btn_text'=>"View Item",
                    'subject' => "Your Prezziez order has been cancelled",
                    'module' => "OrderItem",
                    //'send_to' => "muhammadrao1988@gmail.com",
                    'send_to' => $auth_user->email,
                ];
                dispatch(new SendEmailJob($body))->onQueue('default');
            }


            return ['status'=>true,'code'=>200,'msg'=> "Cancelled successfully."];

        }catch (\Exception $e) {
            DB::rollBack();
            return ['status'=>false,'code'=>422,'errors'=> ['exception' => [$e->getMessage()]]];
        }
    }

    public static function contributedOtherStatus($request,$from_admin=true,$auth_user=false){

        try {
            if (empty($request->id)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["wishlist item id is missing"]]];
            }
            if (empty($request->status)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["status is required"]]];
            }
            if (empty($request->status_description) && $request->status=="Cancelled") {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Please enter cancellation reason"]]];

            }
            if (empty($request->status_description) && $request->status=="Shipped") {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Please enter tracking info URL"]]];

            }
            DB::beginTransaction();
            $id = $request->id;
            $step = $request->step;
            $status = $request->status;
            $description = $request->status_description;
            $lower_case_status = strtolower($status);
            if ($from_admin) {
                $order_status_array = [ "order placed", "shipped", "delivered","received"];
            } else {
                $order_status_array = ["received"];
            }
            if (!in_array($lower_case_status, $order_status_array)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Invalid order status provided"]]];
            }

            $gift_offer = \App\Models\GifteeWishListItem::with(['user'])->where('accept_donation','=',1)->where('status','!=','Created')
                ->where('id','=',$id)->first();

            if (empty($gift_offer)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Order item not found"]]];

            }

            if ($auth_user) {
                if ($gift_offer->user_id != $auth_user->id) {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Record not found."]]];

                }
            }
            $order_current_status = strtolower($gift_offer->status);

            if ($lower_case_status == "order placed") {
                if ($order_current_status != "collected") {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Total amount has not been collected yet."]]];

                }
                $notify_to_giftee = true;
                $notify_to_gifter = false;

            } else if ($lower_case_status == "shipped") {
                if ($order_current_status != "order placed") {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Only order placed status can be shipped."]]];
                }
                $notify_to_giftee = true;
                $notify_to_gifter = false;

            } else if ($lower_case_status == "delivered") {
                if ($order_current_status != "shipped") {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Only shipped status can be delivered."]]];

                }
                $notify_to_giftee = true;
                $notify_to_gifter = false;

            } else if ($lower_case_status == "received") {
                if ($order_current_status != "delivered") {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Only delivered status can be received."]]];
                }
                $notify_to_giftee = false;
                $notify_to_gifter = false;
            }else{
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Invalid status provided"]]];

            }
            $gift_offer_status = $gift_offer->status;


            OrderItem::where('wishlist_item_id',$gift_offer->id)->update(
                [
                    'item_status'=>$status,
                ]);
            Order::where('wishlist_item_id',$gift_offer->id)->update(
                [
                    'status'=>$status,
                    'order_step'=>$step
                ]);
            OrderStatusTimeline::create([
                'type' => "item",
                'order_id' => $gift_offer->id,
                'status' => $status,
                'description' => $description,
            ]);
            $gift_offer->update(['status'=>$status]);
            $toUser = $gift_offer->user;

            if($notify_to_giftee){
                //notify to giftee
                $gift_item_name = $gift_offer->gift_name;
                if(strlen($gift_item_name) > 20){
                    $gift_item_name = substr($gift_item_name,0,19)."..";
                }
                if($lower_case_status=="delivered"){
                    $notification_title = "Your wishlist item: ".$gift_item_name.", has been delivered. Please confirm that you have received this item.";
                    $subject = "Your prezzie has been delivered!";
                    $msg = "Your wishlist item: ".$gift_offer->gift_name.", has been delivered. Please confirm that you have received this item.";
                }else {
                    $notification_title = "The status of your wishlist order: " . $gift_item_name . " has changed from " . $gift_offer_status . " to " . $status;
                    $subject = "The status of your wishlist order: ".$gift_item_name." has changed";
                    $msg = "The status of your wishlist order: " . $gift_offer->gift_name . " has changed from " . $gift_offer_status . " to " . $status;

                }
                $notification_url = route('show.wishlist.item.detail',\App\Helpers\Common::encrypt_decrypt($gift_offer->id));
                Notification::create([
                    'model_id' => $gift_offer->id,
                    'model' => "GifteeWishListItem",
                    'user_id' => $toUser->id,
                    'from_user_id' =>  0,
                    'url' => $notification_url,
                    'title' =>$notification_title,
                    'description' =>$notification_title,
                ]);

                $body = [
                    'msg'=>$msg,
                    'url'=> $notification_url,
                    'btn_text'=>"View Item",
                    'subject' => $subject,
                    'module' => "OrderItem",
                    //'send_to' => "muhammadrao1988@gmail.com",
                    'send_to' => $toUser->email,
                ];
                dispatch(new SendEmailJob($body))->onQueue('default');
            }
            DB::commit();
            return ['status'=>true,'code'=>200,'msg'=> "order status has been updated successfully."];

        }catch (\Exception $e) {
            DB::rollBack();
            return ['status'=>false,'code'=>422,'errors'=> ['exception' => [$e->getMessage()]]];
        }

    }

    public static function contributedReturnManage($request,$from_admin=true,$auth_user=false){

        try {
            if (empty($request->id)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["wishlist item id is missing"]]];
            }
            if (empty($request->status_description)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Message is required"]]];

            }
            DB::beginTransaction();
            $id = $request->id;

            $description = $request->status_description;

            $gift_offer = \App\Models\GifteeWishListItem::with(['user'])->where('accept_donation','=',1)
                ->where('id','=',$id)->first();
            if (empty($gift_offer)) {
                return ['status'=>false,'code'=>422,'errors'=> ['general' => ["wishlist item not found"]]];

            }
            if ($auth_user) {
                $order_item = OrderItem::where('wishlist_item_id','=',$gift_offer->id)->first();
                if ($gift_offer->user_id!= $auth_user->id) {
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["Record not found."]]];

                }
                if($order_item->return_description_gifte!=""){
                    return ['status'=>false,'code'=>422,'errors'=> ['general' => ["You have already requested for return"]]];
                }
            }

            OrderItem::where('wishlist_item_id',$gift_offer->id)->update(
                [
                    'return_status_item'=>$from_admin==true ? 0 : 1,
                    $from_admin==true ? "return_description_admin" : "return_description_giftee" => $request->status_description
                ]);
            Order::where('wishlist_item_id', $gift_offer->id)->update(
                [
                    'return_status' => $from_admin==true ? 1 : 1,
                ]);

            if($from_admin){
                //notify to giftee
                $gift_item_name = $gift_offer->gift_name;
                if(strlen($gift_item_name) > 20){
                    $gift_item_name = substr($gift_item_name,0,19)."..";
                }
                $notification_title = "You have received a message from Prezziez Admin in regards to your return of wishlist item ". $gift_item_name . ".";
                $subject = "Details about your return";

                $notification_url = route('show.wishlist.item.detail',\App\Helpers\Common::encrypt_decrypt($gift_offer->id));
                $toUser = $gift_offer->user;
                Notification::create([
                    'model_id' => $gift_offer->id,
                    'model' => "GifteeWishListItem",
                    'user_id' => $toUser->id,
                    'from_user_id' =>  0,
                    'url' => $notification_url,
                    'title' =>$notification_title,
                    'description' =>$notification_title,
                ]);
                $msg = "You have received a message from Prezziez Admin in regards to your return of wishlist item ". $gift_offer->gift_name . ".";
                $custom_html = "<p><strong>Message:</strong></p>
                                <p>".$request->status_description."</p>";
                $body = [
                    'msg'=>$msg,
                    'url'=> $notification_url,
                    'custom_html'=>$custom_html,
                    'btn_text'=>"View Order Details",
                    'subject' => $subject,
                    'module' => "OrderItem",
                    //'send_to' => "muhammadrao1988@gmail.com",
                    'send_to' => $toUser->email,
                ];
                dispatch(new SendEmailJob($body))->onQueue('default');
            }
            DB::commit();
            return ['status'=>true,'code'=>200,'msg'=> "record updated successfully."];

        }catch (\Exception $e) {
            DB::rollBack();
            return ['status'=>false,'code'=>422,'errors'=> ['exception' => [$e->getMessage()]]];
        }

    }

}
