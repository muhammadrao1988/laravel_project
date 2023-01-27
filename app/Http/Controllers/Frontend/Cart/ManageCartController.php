<?php

namespace App\Http\Controllers\Frontend\Cart;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\BillingInfo;
use App\Models\ExceptionTransaction;
use App\Models\Followers;
use App\Models\GifteeWishListItem;
use App\Models\Notification;
use App\Models\OrderStatusTimeline;
use App\Models\States;
use App\Models\UserCreditHistory;
use App\Models\Website;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\TraitLibraries\ResponseWithHttpStatus;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;

class ManageCartController extends Controller
{
    use ResponseWithHttpStatus;
    protected $mainViewFolder = 'frontend.cart.';


    public function index(Request $request)
    {
        if ($request->ajax() && $request->input('use_credit')) {
            $auth_user = auth()->guard('web')->user();
            if (!empty($auth_user) && $auth_user->credit > 0) {
                if ($request->input('use_credit') == "yes") {
                    session()->put('use_credit', 1);
                } else {
                    session()->forget('use_credit');
                }
                return response()->json(['success' => true], 200);
            } else {
                return response()->json(['success' => false], 403);
            }
        }

        if ($request->ajax() && $request->input('use_expedited_shipping')) {
            if ($request->input('use_expedited_shipping') == "yes") {
                $set_shipping = 1;
                foreach (session('cart') as $id => $details) {
                    if($details['expedited_shipping_fee'] > 0){
                        continue;
                    }
                    session()->flash('error', 'The item '.$details['name'].' does not have an expedited shipping option. All items must have expedited shipping option.');
                    $set_shipping = 0;
                    break;

                }
                if($set_shipping > 0) {
                    session()->put('use_expedited_shipping', 1);
                }else{
                    session()->forget('use_expedited_shipping');
                }
            } else {
                session()->forget('use_expedited_shipping');
            }
        }

        $cart_count = count((array)session('cart'));
        if ($cart_count == 0) {
            session()->flash('info', 'Your cart is empty');
            return redirect()->route('front');
        }
        $use_credit = false;
        if (!empty(session('use_credit'))) {
            $use_credit = true;
        }

        $use_expedited_shipping = false;
        if (!empty(session('use_expedited_shipping'))) {
            $use_expedited_shipping = true;
        }

        $page = "cart";

        return view($this->mainViewFolder . 'cart', compact('cart_count', 'use_credit', 'use_expedited_shipping', 'page'));
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function addToCart($id)
    {
        $product = GifteeWishListItem::where('id', $id)->first();
        if (empty($product)) {
            session()->flash('error', 'Invalid product selected');
            return redirect()->back();
        }

        if ($product->accept_donation == 1) {
            session()->flash('error', 'Contributed Item is not allowed to add in cart.');
            return redirect()->back();
        }

        $item_amount = $product->price * $product->quantity;
        $collected_amount = $product->collected_amount;

        if ($collected_amount >= $item_amount) {
            session()->flash('error', 'The selected item is no longer available in wishlist.');
            return redirect()->back();
        }

        $cart = session()->get('cart', []);
        $to_user = "";
        $next_user = $product->user_id;
        $i = 1;

        foreach ($cart as $id_prod=>$detail){
            if($i==1 ){
                $to_user = $detail['user_id'];
            }
            if($i > 1){
                $next_user = $detail['user_id'];
            }
            if($to_user!="" && $next_user!=$to_user){
                session()->flash('error', 'You can add only same profile wishlist items. ');
                return redirect()->back();
            }

            $to_user = $detail['user_id'];

            $i++;

        }
        $detail_cart =
            [
                "name" => $product->gift_name,
                "quantity" => $product->quantity,
                "unit_price" => $product->price,
                "amount" => $item_amount - $collected_amount,
                "picture" => $product->picture,
                "type" => $product->type,
                "product_id" => $id,
                "accept_donation" => $product->accept_donation,
                "shipping_cost" => $product->shipping_cost,
                "expedited_shipping_fee" => $product->expedited_shipping_fee,
                "merchant" => $product->merchant,
                "user_id" => $product->user_id,
                "wishlist_id" => $product->giftee_wishlist_id,
            ];
        $cart[$id] = $detail_cart;

        session()->put('cart', $cart);

        return redirect(route('cart'))->with('success', 'Product added to cart successfully!');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Cart updated successfully');
        }
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function remove(Request $request)
    {

        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Gift item removed successfully');
        }
    }

    public function checkout(Request $request)
    {

        if (count((array)session('cart')) == 0) {
            session()->flash('info', 'Your cart is empty');
            return redirect(route('cart'));
        }
        if (isset($request->total_amount)) {
            session()->put('order_summary', [
                'total_amount' => $request->total_amount,
                'note' => $request->note,
                'recieve_purchase_status' => isset($request->recieve_updates) ? 1 : 0
            ]);

        }
        $auth_user = auth()->guard('web')->user();
        $use_credit = false;
        $current_credit = 0;
        if (!empty(session('use_credit'))) {
            $use_credit = true;
            $current_credit = !empty($auth_user->id) ? $auth_user->credit : 0;
        }

        $use_expedited_shipping = false;
        if (!empty(session('use_expedited_shipping'))) {
            $use_expedited_shipping = true;
        }
        $billing_detail = new \stdClass();

        $billing_detail->first_name = '';
        $billing_detail->last_name = '';
        $billing_detail->email = '';
        $billing_detail->address = '';
        $billing_detail->country = '';
        $billing_detail->city = '';
        $billing_detail->state = '';
        $billing_detail->postal_code = '';

        if (!empty($auth_user)) {
            $name = explode(" ", $auth_user->name);
            $billing_detail->first_name = !empty($name[0]) ? $name[0] : "";
            $billing_detail->last_name = !empty($name[1]) ? $name[1] : "";
            $billing_detail->email = $auth_user->email;
            $billing_detail->address = $auth_user->address;
            $billing_detail->country = $auth_user->country;
            $billing_detail->city = $auth_user->city;
            $billing_detail->state = $auth_user->state;
            $billing_detail->postal_code = $auth_user->zip;

        }
        $total = 0;
        $shipping_fee = 0;
        $expedited_shipping_fee = 0;
        $sub_total = 0;
        $cart_count = 0;
        $user_id_giftee = 0;
        foreach (session('cart') as $id => $details) {
            $total = $total + $details['amount'];
            $sub_total = $sub_total + ($details['amount']);
            $shipping_fee = $shipping_fee + $details['shipping_cost'];
            $expedited_shipping_fee = $expedited_shipping_fee + $details['expedited_shipping_fee'];
            $user_id_giftee = $details['user_id'];
            $cart_count++;
        }

        $page = "checkout";
        $field_name_checkout = "default_method";

        return view($this->mainViewFolder . 'checkout', compact('user_id_giftee','field_name_checkout','billing_detail', 'total', 'sub_total', 'shipping_fee', 'use_credit', 'cart_count', 'use_expedited_shipping', 'page','expedited_shipping_fee','current_credit'));

    }

    public function payment(Request $request)
    {
        if (
            (count((array)session('cart')) > 0 && $request->default_method==1)
                ||
            (count((array)session('contributed_cart')) > 0 && $request->contributed_checkout==1)
                ||
            (count((array)session('gift_offer_cart')) > 0 && $request->gift_offer_checkout==1)
        ) {
            $isRead = !empty($request->isRead) ? true : false;
            $isGooglePay = !empty($request->isGooglePay) ? true : false;
            $byPassValidation = (!$isRead && $isGooglePay) ? true : false;

            if($request->contributed_checkout==1){
                $use_credit_key = 'use_credit_contributed';
                $use_expedited_shipping_key = 'use_expedited_shipping_contributed';
                $order_type = "Contribution";
                $cart_key = "contributed_cart";
                $order_summary_key = "order_summary_contributed";
                $order_status = "Order Processing";
                $order_item_status = "Order Processing";
                $order_status_description = "Contribution added for the wishlist item.";
            }else if($request->gift_offer_checkout==1){
                $use_credit_key = 'use_credit_gift_offer';
                $use_expedited_shipping_key = 'use_expedited_shipping_gift_offer';
                $order_type = "GiftOffer";
                $cart_key = "gift_offer_cart";
                $order_summary_key = "order_summary_gift_offer";
                $order_status = "Pending";
                $order_item_status = "Pending";
                $order_status_description = "Received a gift offer";
            } else if($request->default_method==1){
                $order_type = "Cart";
                $use_credit_key = 'use_credit';
                $use_expedited_shipping_key = 'use_expedited_shipping';
                $cart_key = "cart";
                $order_summary_key = "order_summary";
                $order_status = "Order Processing";
                $order_item_status = "Order Processing";
                $order_status_description = "Order has been created for the wishlist item.";
            }else{
                return new JsonResponse(['errors' => ['exception' => ["Invalid action performed, Please refresh the page and try again."]]], 422);

            }
            $direct_checkout = false;
            if (!empty($request->direct_checkout) || $isGooglePay) {
                $direct_checkout = true;
            }

            $rules['first_name'] = ['required', 'string'];
            $rules['last_name'] = ['required', 'string'];
            $rules['email'] = ['required', 'email'];
            $rules['address'] = ['required', 'string'];
            $rules['country'] = ['required', 'string'];
            $rules['city'] = ['required', 'string'];
            $rules['state'] = ['required', 'string'];
            $rules['postal_code'] = ['required', 'string'];
            if (!$direct_checkout) {
                $rules['name_on_card'] = ['required', 'string'];
                $rules['card_number'] = ['required'];
                $rules['card_expiry'] = ['required'];
                $rules['cvc'] = ['required', 'numeric'];
            }

            //validate fields
            $request->validate($rules);
            //DB::beginTransaction();
            try {
                $transaction_id = ($isGooglePay && !$isRead) ? $request->transaction_id : "";
                $payment_method = $isGooglePay ? config('constants.PAYMENT_BY_GOOGLE_PAY') : config('constants.PAYMENT_BY_CREDIT');
                $description_payment = 'Purchase wishlist item for prezziez';
                $order_note = !empty(session($order_summary_key)['note']) ? session($order_summary_key)['note'] : "" ;

                $use_credit = false;
                $current_credit = 0;


                $auth_user = auth()->guard('web')->user();
                $is_auth = !empty($auth_user->id) ? true : false;
                $user_id = null;
                $giftee_username = null;
                $credit_apply = 0;
                $expedited_fee = 0;
                $credit_apply_subtotal = 0;

                if ($is_auth) {
                    $current_credit = $auth_user->credit;
                    $user_id = $auth_user->id;
                    $giftee_username = $auth_user->username;
                }

                if ($is_auth && !empty(session($use_credit_key))) {
                    $use_credit = true;
                }

                $use_expedited_shipping = false;
                if (!empty(session($use_expedited_shipping_key))) {
                    $use_expedited_shipping = true;
                }
                $processing_total = $processing_sub_total = $processing_shipping_fee = $cart_count = $processing_expedited_shipping_fee = 0;
                $order_item_array = [];
                $cart_count = 0;
                $to_user = "";
                $wishlist_item_id = null;
                if($request->contributed_checkout==1){
                    foreach (session($cart_key) as $id => $details) {
                        $product = GifteeWishListItem::where('id', $id)->first();
                        if (empty($product) && !$byPassValidation) {
                            return new JsonResponse(['errors' => ['product_detail' => [$details['name'] . " has been deleted from wishlist."]]], 422);

                        }
                        $tax_rate =  $product->tax_rate > 0 ?  $product->tax_rate : 0;
                        $item_amount = ($product->price * $product->quantity) + $product->shipping_cost + $product->service_fee + $tax_rate;

                        $collected_amount = $product->collected_amount;
                        $remaining_amount = Common::decimalPlace($item_amount - $collected_amount,2);
                        if($remaining_amount < 0){
                            $remaining_amount = 0;
                        }

                        if ($remaining_amount <= 0 && !$byPassValidation) {
                            return new JsonResponse(['errors' => ['product_detail' => [$details['name'] . " has already been fulfilled."]]], 422);
                        }

                        if ($details['contributed_amount'] >  $remaining_amount && !$byPassValidation) {
                            return new JsonResponse(['errors' => ['product_detail' => ["The amount must be less than or equal to $".$remaining_amount]]], 422);
                        }

                        if(!$byPassValidation && ($remaining_amount - $details['contributed_amount']) > 0 && ($remaining_amount - $details['contributed_amount']) < 1){
                            return new JsonResponse(['errors' => ['product_detail' =>  ["Please enter total remaining amount"]]], 422);
                        }

                        if($details['contributed_amount'] < 1 && !$byPassValidation){
                            return new JsonResponse(['errors' => ['product_detail' =>  ["The minimum amount should be 1$ "]]], 422);
                        }

                        $processing_total = $processing_total + $details['contributed_amount'];
                        $processing_sub_total = $processing_sub_total + $details['contributed_amount'];

                        $product->expedited_shipping_fee = 0;
                        $product->collection_amount = $processing_sub_total;
                        $order_item_array[$cart_count] = $product;
                        $to_user = $product->user_id;
                        if(!$isRead) {
                            if ($remaining_amount <= $details['contributed_amount']) {
                                GifteeWishListItem::where('id', $id)->update(['status' => 'Collected']);
                                OrderStatusTimeline::create([
                                    'order_id' => $id,
                                    'status' => "Collected",
                                    'type' => "item",
                                    'description' => "Item total amount has been collected successfully. ",
                                ]);
                            }
                        }
                        $cart_count++;
                        $wishlist_item_id = $product->id;
                    }
                    $to_user_data = Website::find($to_user);

                }
                else if($request->gift_offer_checkout==1){
                    $details = session($cart_key)[1];
                    $to_user_data = Website::find($details['to_user']);
                    if(empty($to_user_data) && !$byPassValidation){
                        return new JsonResponse(['errors' => ['general' => ['Invalid profile selected.']]], 422);

                    }
                    if($to_user_data->offer_gift==0  && !$byPassValidation){
                        return new JsonResponse(['errors' => ['general' => ['You are no longer to avail this option because profile has disabled option to accept gift offer.']]], 422);
                    }
                    $item_amount = ($details['price'] * $details['quantity']);

                    $to_user = $to_user_data->id;
                    $processing_total =  $item_amount;
                    $processing_sub_total = $item_amount;
                    $processing_shipping_fee = $details['shipping_cost'];

                    $order_item_array[1] = $details;
                    $description_payment = 'Offer a gift for Giftee profile';
                    $order_note = $request->gift_offer_note;

                }
                else {
                    $next_user = "";
                    foreach (session($cart_key) as $id => $details) {
                        $product = GifteeWishListItem::where('id', $id)->first();
                        if (empty($product) && !$byPassValidation) {
                            return new JsonResponse(['errors' => ['product_detail' => [$details['name'] . " has been deleted from wishlist. To proceed please first delete from your cart items."]]], 422);

                        }
                        $next_user = $product->user_id;
                        if ($product->accept_donation == 1 && !$byPassValidation) {
                            return new JsonResponse(['errors' => ['product_detail' => [$details['name'] . " is contribution item. It is not allowed in direct checkout. To proceed please first delete from your cart items."]]], 422);

                        }

                        if($to_user !="" && $next_user!=$to_user && !$byPassValidation){
                            return new JsonResponse(['errors' => ['product_detail' => [$details['name'] . " has different profile wishlist item. You can add only same profile wishlist item"]]], 422);

                        }

                        $item_amount = ($product->price * $product->quantity);
                        $collected_amount = $product->collected_amount;

                        if ($collected_amount >= $item_amount && !$byPassValidation) {
                            return new JsonResponse(['errors' => ['product_detail' => [$details['name'] . " has already been fulfilled. To proceed please first delete from your cart items."]]], 422);
                        }

                        if ($details['amount'] != $item_amount && !$byPassValidation) {
                            return new JsonResponse(['errors' => ['product_detail' => [$details['name'] . " has price changed from $" . $details['amount'] . " to $" .$product->price . ". To proceed please first delete from your cart items and add again."]]], 422);
                        }

                        if (!$byPassValidation && $use_expedited_shipping && $details['expedited_shipping_fee'] != $product->expedited_shipping_fee) {
                            return new JsonResponse(['errors' => ['product_detail' => [$details['name'] . " has expedited shipping fee changed. To proceed please first delete from your cart items and add again."]]], 422);

                        }else if ($details['shipping_cost'] != $product->shipping_cost && !$byPassValidation) {
                            return new JsonResponse(['errors' => ['product_detail' => [$details['name'] . " has shipping fee changed. To proceed please first delete from your cart items and add again."]]], 422);
                        }

                        $processing_total = $processing_total + $item_amount;
                        $processing_sub_total = $processing_sub_total + $item_amount;
                        $processing_shipping_fee = $processing_shipping_fee + $product->shipping_cost;
                        $processing_expedited_shipping_fee = $processing_expedited_shipping_fee + $product->expedited_shipping_fee;

                        $order_item_array[$cart_count] = $product;
                        $to_user = $product->user_id;
                        $cart_count++;

                    }
                    $to_user_data = Website::find($to_user);
                }

                if ($use_expedited_shipping) {
                    $processing_total = $processing_total + $processing_expedited_shipping_fee;

                } else {
                    $processing_total = $processing_total + $processing_shipping_fee;
                }

                $order_total = $processing_total;
                $tax_percent_state = 0;
                $tax_state_name = "";
                if($request->contributed_checkout==1){
                    $processing_estimated = Common::estimated_tax_contributed($processing_total);
                }else{

                    $processing_estimated = @Common::estimated_tax($processing_total,$to_user_data->id,$processing_total,$to_user_data->state);
                    $state_rec = States::where('name','=',$to_user_data->state)->first();
                    if(!empty($state_rec->tax_rate) && $state_rec->tax_rate > 0){
                        $tax_percent_state = $state_rec->tax_rate;
                        $tax_state_name = $state_rec->name;
                    }
                }
                $processing_estimated = $processing_estimated - $processing_total;

                if ($use_credit || ($direct_checkout && !$isGooglePay)) {

                    if($auth_user->credit != $request->current_credit && !$byPassValidation){
                        return new JsonResponse(['errors' => ['product_detail' => [ "There is change in your prezziez credit, please refresh the page and try again"]]], 422);

                    }
                    $current_credit = $current_credit - $processing_total;
                    $processing_total = $processing_total - $auth_user->credit;

                    if($processing_total <= 0){
                        $processing_total = 0;
                        $credit_apply_subtotal = $processing_sub_total + ($use_expedited_shipping ? $processing_expedited_shipping_fee : $processing_shipping_fee);
                    }
                    $processing_estimated = @\App\Helpers\Common::estimated_tax($processing_total,$to_user_data->id,$processing_sub_total+($use_expedited_shipping ? $processing_expedited_shipping_fee : $processing_shipping_fee),$to_user_data->state,$credit_apply_subtotal);

                    if($credit_apply_subtotal > 0){
                        $processing_total = $credit_apply_subtotal;
                    }
                    $processing_estimated = $processing_estimated - $processing_total;
                    if($processing_estimated < 0){
                        $processing_estimated = 0;
                    }


                    if($current_credit<=0){
                        $credit_apply = $auth_user->credit;
                    }elseif ($current_credit > 0){
                        if ($use_expedited_shipping) {
                            $credit_apply = $processing_sub_total   + $processing_expedited_shipping_fee;
                        }else{
                            $credit_apply = $processing_sub_total + $processing_shipping_fee  ;

                        }

                    }


                    $credit_apply = Common::decimalPlace($credit_apply,2);
                    $order_total = $processing_sub_total+($use_expedited_shipping ? $processing_expedited_shipping_fee : $processing_shipping_fee);
                    if($credit_apply_subtotal > 0){
                        $processing_total = $processing_estimated;
                    }else{
                        $processing_total = $processing_total + $processing_estimated;
                    }

                }else{
                    $processing_total = $processing_total + $processing_estimated;
                }
                $processing_total = Common::decimalPlace($processing_total,2);
                if($direct_checkout && $processing_total > 0 && !$isGooglePay){
                    try {
                        $rules_final['name_on_card'] = ['required', 'string'];
                        $rules_final['card_number'] = ['required'];
                        $rules_final['card_expiry'] = ['required'];
                        $rules_final['cvc'] = ['required', 'numeric'];
                        $request->validate($rules_final);
                    }catch (\Exception $er){
                        return new JsonResponse(['errors' => ['exception' => ["There is change in credit, please refresh the page and try again."]]], 422);
                    }

                }
                if(!$direct_checkout && $processing_total <=0 && !$byPassValidation){
                    return new JsonResponse(['errors' => ['exception' => ["There is change in your total, please refresh the page and try again."]]], 422);

                }

              /*  echo "total".$total;
                echo "<br>";
                echo "pro total".$processing_total;
                echo "<br>";
                echo "subtotal".$sub_total;
                echo "<br>";
                echo "sub pro total".$processing_sub_total;
                echo "<br>";
                echo "estimated".$estimated;
                echo "<br>";
                echo " pro estimated".$processing_estimated;
                echo "<br>";
                echo " pro shipping".$processing_shipping_fee;
                echo "<br>";
                echo "credit apply".$credit_apply;
                echo "<br>";
                echo "current credit".$current_credit;

                die("fd");*/
                if ($processing_total > 0 && !$isGooglePay) {
                    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

                    $cardNumber = preg_replace('/\s+/', '', $request->card_number);
                    $cvc =  $request->cvc;
                    $card_expiry = preg_replace('/\s+/', '', $request->card_expiry);
                    $month_year = explode("/", $card_expiry);
                    $month = $month_year[0];

                    if(strlen($month_year[1]) == 4){
                        $year = $month_year[1];
                    }else {
                        $year = "20" . $month_year[1];
                    }

                    $token = $stripe->tokens->create([
                        'card' => [
                            'number' => $cardNumber,
                            'exp_month' => $month,
                            'exp_year' => $year,
                            'cvc' => $cvc,
                        ],
                    ]);

                    if (!isset($token['id'])) {
                        return new JsonResponse(['errors' => ['exception' => ["Unable to generate token of your payment. Please refresh the page and try again."]]], 422);

                    }
                    $charge = $stripe->charges->create([
                        'amount' => $processing_total*100,
                        'currency' => 'usd',
                        'source' => $token['id'],
                        'description' => $description_payment,
                        'receipt_email' => $request->email,
                        "metadata" => [
                            "city" => $request->city,
                            "country" => $request->country,
                            "line1" => $request->address,
                            "line2" => null,
                            "postal_code" => $request->postal_code,
                            "state" => $request->state,
                            "email" => $request->email,
                            "name" => $request->first_name." ".$request->last_name,
                        ],
                    ]);


                    if ($charge['status'] == 'succeeded') {
                       $transaction_id = $charge['id'];
                        $payment_method = config('constants.PAYMENT_BY_STRIPE');

                    } else {
                        return new JsonResponse(['errors' => ['exception' => ["Unable to complete your transaction. The status return by payment gateway is ".$charge['status']]], 422]);

                    }
                }

                if($isRead){
                    return new JsonResponse(['status' => 'success'], 200);
                }

                $billing_info = BillingInfo::create(
                    [
                        'user_id' => $user_id,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'country' => $request->country,
                        'city' => $request->city,
                        'state' => $request->state,
                        'postal_code' => $request->postal_code,
                        'address' => $request->address,
                    ]
                );


                if($request->contributed_checkout==1){
                    $sys_fee = 0;
                    $taxes = 0;
                }else {
                    $sys_fee = ($processing_total > 0 ? Common::estimated_prezziez_fee($order_total-$credit_apply) : 0);
                    $taxes = ($processing_total > 0 ? Common::estimated_tax_fee($order_total,$tax_percent_state,$tax_state_name) : 0);
                }
                $order = Order::create(
                    [
                        'order_type'=>$order_type,
                        'wishlist_item_id'=>$wishlist_item_id,
                        'billing_id'=>$billing_info->id,
                        'user_id'=>$user_id,
                        'to_user'=>$to_user,
                        'giftee_username'=>$giftee_username,
                        'subtotal'=>$processing_sub_total,
                        'expedited_shipping'=>$use_expedited_shipping,
                        'shipping_fee'=>($use_expedited_shipping ? $processing_expedited_shipping_fee : $processing_shipping_fee),
                        'processing_fee'=>$processing_estimated,
                        'use_credit'=>$use_credit,
                        'credit_apply'=>$credit_apply,
                        'total_amount'=>$processing_total,
                        'status'=>$order_status,
                        'surprise'=>0,
                        'payment_method'=>$payment_method,
                        'note'=>$order_note ,
                        'recieve_purchase_status'=>!empty(session($order_summary_key)['recieve_purchase_status']) ? 1 : 0,
                        'payment_id'=>$transaction_id,
                        'prezziez_fee'=>$sys_fee,
                        'payment_processing_fee'=>($processing_total > 0 ? Common::estimated_stripe_fee($order_total,$sys_fee,$taxes) : 0),
                        'taxes'=>$taxes,
                    ]
                );
                $wishlist_id = "";
                if($request->gift_offer_checkout==1){
                  $order_item_id =  OrderItem::create([
                        'order_id' => $order->id,
                        'item_name' => $order_item_array[1]['gift_name'],
                        'item_image' =>  $order_item_array[1]['picture'],
                        'item_price' =>  $order_item_array[1]['price'],
                        'item_expedited_shipping' => 0,
                        'item_expedited_shipping_price' =>0,
                        'item_qty' => $order_item_array[1]['quantity'],
                        'item_status' => $order_item_status,
                        'item_detail' =>  $order_item_array[1]['gift_details'],
                        'item_merchant' => $order_item_array[1]['merchant'],
                        'item_shipping_price' => $order_item_array[1]['shipping_cost'],
                        'item_digital_purchase' => $order_item_array[1]['digital_purchase'],
                        'item_url' => $order_item_array[1]['url'],

                    ]);
                    $success_cart_key = "success_cart_gift";
                    $wishlist_id = $to_user_data->username;
                }
                else {
                    foreach ($order_item_array as $order_item) {
                        $order_item_id =   OrderItem::create([
                            'order_id' => $order->id,
                            'wishlist_item_id' => $order_item->id,
                            'item_name' => $order_item->gift_name,
                            'item_image' => $order_item->picture,
                            'item_price' => $order_item->price,
                            'item_expedited_shipping' => $use_expedited_shipping,
                            'item_expedited_shipping_price' => $order_item->expedited_shipping_fee,
                            'item_qty' => $order_item->quantity,
                            'item_status' => $order_item_status,
                            'item_detail' => $order_item->gift_details,
                            'item_merchant' => $order_item->merchant,
                            'item_shipping_price' => $order_item->shipping_cost,
                            'item_digital_purchase' => $order_item->digital_purchase,
                            'item_url' => $order_item->url,
                        ]);
                        if ($request->contributed_checkout == 1) {
                            $collection_amount = $order_item->collection_amount;
                        } else {
                            $collection_amount = $order_item->price * $order_item->quantity;
                        }
                        if (empty($order_item->collected_amount)) {
                            GifteeWishListItem::where('id', $order_item->id)->update(['collected_amount' => $collection_amount]);
                        } else {
                            GifteeWishListItem::find($order_item->id)->increment('collected_amount', $collection_amount);
                        }

                        $wishlist_id = Common::encrypt_decrypt($order_item->giftee_wishlist_id, "encrypt");

                    }
                    if ($request->contributed_checkout == 1) {
                        $success_cart_key = "success_cart_contribute";
                    }else{
                        $success_cart_key = "success_cart";
                    }
                }
                if (empty($request->contributed_checkout)) {
                    OrderStatusTimeline::create([
                        'order_id' => $order->id,
                        'status' => $order_status,
                        'description' => $order_status_description,
                    ]);
                }

                if($use_credit ){
                    UserCreditHistory::create([
                       'debit' =>$credit_apply,
                       'credit' => 0,
                       'order_id' => $order->id,
                       'user_id' => $user_id,
                    ]);
                    Website::find($auth_user->id)->decrement('credit',$credit_apply);
                }


                $custom_html = "<p>Thank you for your purchase, we have received your payment. We'll let the lucky giftee know that their Prezziez are on the way!</p>";
                $custom_html.= "<p>Order ID: ".$order->id."</p>";
                $custom_html.= "<p>Total Amount(USD): ".Common::numberFormat($processing_total)."</p>";
                DB::commit();

                $body = [
                    'msg'=>"Dear ".$request->first_name.",",
                    'url'=> "",
                    'btn_text'=>"",
                    'subject' => "Congrats on your purchase",
                    'module' => "OrderItem",
                    'custom_html'=>$custom_html,
                    'send_to' => $billing_info->email,
                ];
                dispatch(new SendEmailJob($body))->onQueue('default');
                $email_error ="";

                //notification to giftee gift offer
                if($request->gift_offer_checkout==1){
                    $notification_name = ($is_auth ? $auth_user->username : "anonymous");
                    $notification_url = route('giftOfferDetail',\App\Helpers\Common::encrypt_decrypt($order_item_id->id));
                    Notification::create([
                        'model_id' => $order_item_id->id,
                        'model' => "OrderItem",
                        'user_id' => $to_user,
                        'from_user_id' => $is_auth ? $auth_user->id : 0,
                        'url' => $notification_url,
                        'title' => "Congratulations! You have received a gift offer from ".$notification_name,
                        'description' => "Congratulations! You have received a gift offer from ".$notification_name,
                    ]);
                    $msg = "Congratulations! You have received a gift offer from ".$notification_name.". Click the link below to view the details of your gift offer:";
                    $body = [
                        'msg'=>$msg,
                        'url'=> $notification_url,
                        'btn_text'=>"View Order Details",
                        'subject' => "Gift offer from ".$notification_name." !",
                        'module' => "OrderItem",
                        //'send_to' => "muhammadrao1988@gmail.com",
                        'send_to' => $to_user_data->email,
                    ];
                    dispatch(new SendEmailJob($body))->onQueue('default');
                }

                //notification to giftee cart item
                if($request->default_method == 1){
                    /*if(Followers::notificationSetting($to_user_data->id,"receive_gift")) {

                    }
                    if(Followers::emailNotificationSetting($to_user_data->id,"receive_gift")) {

                    }*/
                    $email_product = "<table>";
                    foreach ($order_item_array as $item){
                        $item_name = $item->gift_name;
                        $item_name_full = $item_name;
                        if(strlen($item_name) > 20){
                            $item_name = substr($item_name,0,19)."..";
                        }
                        Notification::create([
                            'model_id' => $item->id,
                            'model' => "GifteeWishListItem",
                            'user_id' => $to_user,
                            'from_user_id' => $is_auth ? $auth_user->id : 0,
                            'url' => route('show.wishlist.item.detail',\App\Helpers\Common::encrypt_decrypt($item->id)),
                            'title' => $item_name." payment has been received.",
                            'description' => $item_name_full," payment has been received.",
                        ]);
                        $email_product .="
                                            <tr>
                                            <th>Item Name: </th>
                                            <td><a href='".route('show.wishlist.item.detail',\App\Helpers\Common::encrypt_decrypt($item->id))."'>".$item_name_full."</a></td>
                                            </tr>";
                    }
                    $email_product .= "</table>";

                    $msg = "Get excited! A payment has been received for the following item(s):";
                    $body = [
                        'msg'=>$msg,
                        'url'=> '',
                        'btn_text'=>"",
                        'custom_html' => $email_product,
                        'subject' => "Payment received for your prezziez!",
                        'module' => "OrderItem",
                        //'send_to' => "muhammadrao1988@gmail.com",
                        'send_to' => $to_user_data->email,
                    ];
                    dispatch(new SendEmailJob($body))->onQueue('default');

                }

                //notification to gifteee contribution item
                if($request->contributed_checkout == 1){
                    $contributor_name = ($is_auth ? $auth_user->username : "anonymous");
                    $email_product = "<table>";
                    foreach ($order_item_array as $item){
                        $item_name = $item->gift_name;
                        $item_name_full = $item_name;
                        if(strlen($item_name) > 20){
                            $item_name = substr($item_name,0,19)."..";
                        }
                        Notification::create([
                            'model_id' => $item->id,
                            'model' => "GifteeWishListItem",
                            'user_id' => $to_user,
                            'from_user_id' => $is_auth ? $auth_user->id : 0,
                            'url' => route('show.wishlist.item.detail',\App\Helpers\Common::encrypt_decrypt($item->id)),
                            'title' => $contributor_name. ' has added contribution to your wishlist item '.$item_name,
                            'description' =>  $contributor_name. ' has added contribution to your wishlist item '.$item_name,
                        ]);
                        $email_product .="
                                            <tr>
                                            <th>Item Name: </th>
                                            <td><a href='".route('show.wishlist.item.detail',\App\Helpers\Common::encrypt_decrypt($item->id))."'>".$item_name_full."</a></td>
                                            </tr>
                                            <tr><th> </th><td> </td></tr>";
                    }
                    $email_product .= "</table>";

                    $msg = $contributor_name. ' has contributed $'.$collection_amount.' towards your wishlist item '.$item_name_full;
                    $body = [
                        'msg'=>$msg,
                        'url'=> '',
                        'btn_text'=>"",
                        'custom_html' => $email_product,
                        'subject' => "You've received a new contribution!",
                        'module' => "OrderItem",
                        //'send_to' => "muhammadrao1988@gmail.com",
                        'send_to' => $to_user_data->email,
                    ];
                    dispatch(new SendEmailJob($body))->onQueue('default');

                }

                session()->forget($cart_key);
                session()->forget($order_summary_key);
                session()->forget($use_credit_key);
                session()->forget($use_expedited_shipping_key);
                session()->save();
                session()->flash($success_cart_key,$wishlist_id);

                return new JsonResponse(['status' => 'success', 'msg' => "Thank you for purchasing." . $email_error,"checkout"=>$user_id], 200);

            } catch (\Exception $e) {
                if(!$isGooglePay && !$isRead && !empty($transaction_id)) {
                    $description = [
                        'order_type'=>$order_type,
                        'billing_id'=>$billing_info->id,
                        'user_id'=>$user_id,
                        'giftee_username'=>$giftee_username,
                        'subtotal'=>$processing_sub_total,
                        'expedited_shipping'=>$use_expedited_shipping,
                        'expedited_fee'=>$expedited_fee,
                        'shipping_fee'=>$processing_shipping_fee,
                        'processing_fee'=>$processing_estimated,
                        'use_credit'=>$use_credit,
                        'credit_apply'=>$credit_apply,
                        'total_amount'=>$processing_total,
                        'status'=>$order_status,
                        'surprise'=>0,
                        'payment_method'=>$payment_method,
                        'note'=>!empty(session($order_summary_key)['note']) ? session($order_summary_key)['note'] : "" ,
                        'recieve_purchase_status'=>!empty(session($order_summary_key)['recieve_purchase_status']) ? 1 : 0,
                        'payment_id'=>$transaction_id,
                    ];
                    $exception_transaction = [

                        'user_id' => $user_id,
                        'billing_id' => $billing_info->id,
                        'transaction_id' => $transaction_id,
                        'description' => json_encode($description),
                    ];
                }
                DB::rollBack();
                if(!$isGooglePay && !$isRead && !empty($transaction_id)){
                    ExceptionTransaction::create($exception_transaction);
                    $body = "<p>Your payment has been deducted but there was something went wrong to complete your order Your transaction id is ".$transaction_id." and contact to admin for further process.</p>";
                    $body.= "<p>Total Amount(USD): ".Common::numberFormat($processing_total)."</p>";
                    $email_status = Common::sendEmail([$billing_info->email],"Error in completing order", $body);
                    if ($email_status['success'] == false) {
                        $email_error = " Email send failed.";
                    } else {
                        $email_error = "";
                    }
                    return new JsonResponse(['errors' => ['exception' => ["Your payment has been deducted but there is something went wrong to complete your order. Your transaction id is ".$transaction_id." and contact to admin..".$e->getMessage().$email_error]]], 422);
                }
                return new JsonResponse(['errors' => ['exception' => [$e->getMessage()]]], 422);
                //return redirect()->route('addmoney.paymentstripe');
            }

        } else {
            return new JsonResponse(['errors' => ['product_detail' => ["  To proceed please first add item in your cart."]]], 422);

        }
    }

    public function success(Request $request)
    {
        if(empty(session('success_cart_contribute')) && empty(session('success_cart')) && empty(session('success_cart_gift'))){
            return redirect()->route('home');
        }
        $is_contribute = false;
        if(!empty(session('success_cart'))){
            $wishlist_id = session('success_cart');
            $button_text = "Return to Wishlist";
            $url = route('show.wishlist.item',$wishlist_id);
        }else if(!empty(session('success_cart_contribute'))){
            $wishlist_id = session('success_cart_contribute');
            $button_text = "Return to Wishlist";
            $url = route('show.wishlist.item',$wishlist_id);
            $is_contribute = true;
        }else{
            $wishlist_id = session('success_cart_gift');
            $button_text = "Return to profile";
            $url = route('profileUrl',$wishlist_id);
        }


        return view($this->mainViewFolder . 'success',compact('wishlist_id','url','button_text','is_contribute'));

    }

    public function removeSelectedItems(Request $request)
    {

        foreach ($request->ids as $id) {
            $cart = session()->get('cart');
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
        }
        session()->flash('success', 'Gift item/items removed successfully');
    }

}
