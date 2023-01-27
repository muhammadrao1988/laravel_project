<?php

namespace App\Http\Controllers\Frontend\GiftOffer;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\BillingInfo;
use App\Models\ExceptionTransaction;
use App\Models\GifteeWishListItem;

use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemStatusTimeline;
use App\Models\OrderStatusTimeline;
use App\Models\ReturnTransaction;
use App\Models\Website;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Common;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;

class ManageGiftOfferController extends Controller
{
    public $module = "GiftOffer";

    protected $_user;

    public function index(Request $request)
    {
        if ($request->ajax() && $request->input('use_credit')) {
            $auth_user = auth()->guard('web')->user();
            if (!empty($auth_user) && $auth_user->credit > 0) {
                if ($request->input('use_credit') == "yes") {
                    session()->put('use_credit_gift_offer', 1);
                } else {
                    session()->forget('use_credit_gift_offer');
                }
                return response()->json(['success' => true], 200);
            } else {
                return response()->json(['success' => false], 403);
            }
        }


        if (count((array)session('gift_offer_cart')) == 0) {

            return redirect(route('home'));
        }

        $use_credit = false;
        $auth_user = auth()->guard('web')->user();
        $current_credit = 0;
        if (!empty(session('use_credit_gift_offer'))) {
            $use_credit = true;
            $current_credit = !empty($auth_user->id) ? $auth_user->credit : 0;
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
        $field_name_checkout = "gift_offer_checkout";

        return view('frontend.cart.checkout_gift_offer', compact('field_name_checkout','billing_detail',  'use_credit','current_credit'));

    }

    public function addToCart(Request $request)
    {
        $rules['url'] = ['required'];
        $rules['gift_name'] = [ 'required'];
        $rules['price'] = [ 'required','numeric'];
        $rules['shipping_cost'] = [ 'nullable','numeric','gte:0'];
        $rules['quantity'] = [ 'required','numeric'];
        $rules['merchant'] = [ 'required'];
        $rules['picture'] = ['required',config('constants.IMG_VALIDATION'), config('constants.IMG_VALIDATION_SIZE')];

        $to_user = Website::find($request->to_user);
        if(empty($to_user)){
            return new JsonResponse(['errors' => ['general' => ['Invalid profile selected.']]], 422);

        }

        $customMessages = [
            'picture.dimensions' => config('constants.WISHLIST_IMG_ERR_MSG')
        ];

        $request->validate($rules, $customMessages);
        $imageName = "";
        if ($request->file('picture')) {
            $imagePath = $request->file('picture');
            $ext = $imagePath->getClientOriginalExtension();
            //$imageName = $imagePath->getClientOriginalName();
            $imageName = rand(1000,9999)."_".date('YmdHis').".".$ext;
            $request->file('picture')->storeAs('uploads/offer_gift', $imageName, 'public');

        }

        session()->forget('gift_offer_cart');
        session()->save();
        $cart = session()->get('gift_offer_cart', []);
        if(empty($request->shipping_cost)){
            $request->shipping_cost = 0;
        }
        $detail =
            [
                "gift_name"=>$request->gift_name,
                "url"=>$request->url,
                "price"=>$request->price,
                "shipping_cost"=>$request->shipping_cost,
                "merchant"=>$request->merchant,
                "quantity"=>$request->quantity,
                "gift_details"=>$request->gift_details,
                "digital_purchase"=>isset($request->digital_purchase) ? 1 : 0,
                "picture"=>$imageName,
                "to_user"=>$to_user->id,
            ];
        $cart[1] = $detail;

        session()->put('gift_offer_cart', $cart);
        if($request->wantsJson()){

            session()->flash('success', "Thank you for adding, please proceed the payment");
            return new JsonResponse(['status'=>'success','action'=>'redirect','url'=>route('gift-offer-checkout')],200);
        }else{
            return redirect()->route("gift-offer-checkout");
        }

    }

    public function giftOffer(Request $request){

        $auth_user = auth()->guard('web')->user();

        $records =  Order::with(['orderItems'])
            ->where('to_user','=',$auth_user->id)
            ->where('order_type','=','GiftOffer')
            ->orderBy('order_step','ASC')
            ->paginate(config('constants.PER_PAGE_LIMIT'));
        $last_page = $records->lastPage();
        $response = "";
        if(!empty($records)) {
            //$records = $records->toArray();
            //dd($records);
            $response = view('frontend.gift_offer.gift_offer_pagination', compact('records', 'last_page'))->render();
        }
        return response()->json(['data'=>$response,'last_page'=>$last_page], 200);

    }

    public function giftOfferDetail($id)
    {
        $auth_user = \auth()->guard('web')->user();
        $decrypt_id = Common::encrypt_decrypt($id,"decrypt");

        $gift_offer = OrderItem::with(['order','order.fromUser','order.billingInfo'])
            ->where('id','=',$decrypt_id)->first();

        if(empty($gift_offer)){
            return abort(404);
        }
        if($gift_offer->order->to_user!=$auth_user->id){
            return abort(404);
        }
        $order_timeline = OrderStatusTimeline::where('order_id','=',$gift_offer->order->id)->where('type','=','order')->get();
        $contributions[] = $gift_offer->toArray();
        //dd($contributions);
        $current_status = $contributions[0]['item_status'];
        $current_status_date = Common::CTL($contributions[0]['created_at']);
        $show_return_form = false;
        $return_day = 0;
        if(strtolower($current_status)=="received" && $gift_offer->return_description_giftee==""){
            $delivered_date = $order_timeline->where('status','=','Delivered')->first();
            if(!empty($delivered_date->created_at)){
                $diff = now()->diffInDays(Carbon::parse($delivered_date->created_at));
                if($diff < 25){
                    $show_return_form = true;
                    $return_day = 25 - $diff;
                }
            }
        }

        return view('frontend.gift_offer.gift_offer_detail',compact('auth_user','order_timeline','contributions','current_status','current_status_date','gift_offer','show_return_form','return_day'));
    }

    public function giftOfferConfirmation(Request $request){

        if(!empty($request->accpeted_status) && !empty($request->id)) {

            $accepted_status = $request->accpeted_status;

            if(strtolower($accepted_status)!="accepted" && strtolower($accepted_status)!="declined"){
                return new JsonResponse(['errors' => ['general' => ['Only accepted or declined status is allowed.']]], 422);
            }

            $id = Common::encrypt_decrypt($request->id,'decrypt');
            $auth_user = \auth()->guard('web')->user();

            $gift_offer = OrderItem::with(['order','order.fromUser','order.billingInfo'])
                ->where('id','=',$id)->first();
            if(empty($gift_offer)){
                return new JsonResponse(['errors' => ['general' => ['Gift offer not found']]], 422);
            }
            if($gift_offer->order->to_user!=$auth_user->id){
                return new JsonResponse(['errors' => ['general' => ['Invalid record provided']]], 422);
            }
            if(strtolower($gift_offer->item_status)!="pending"){
                return new JsonResponse(['errors' => ['general' => ['This gift offer has already been processed']]], 422);
            }
            try{
                $cancelled_order = Order::giftOfferAcceptedRejectedCancelled($request,$auth_user,$gift_offer,2,false);
                if($cancelled_order['status']==true){
                    session()->flash('success_gift_offer',$cancelled_order['msg']);
                    return new JsonResponse(['status'=>'success','action'=>'redirect'],200);
                }else{
                    return new JsonResponse(['errors' => $cancelled_order['errors']], 422);

                }

            }catch (\Exception $e) {
                DB::rollBack();
                return new JsonResponse(['errors' => ['exception' => [$e->getMessage()]]], 422);
            }
        }else{
            return new JsonResponse(['errors' => ['general' => ['Status or Id of gift offer is missing']]], 422);
        }
    }
    public function manageGiftOfferReceivedStatus(Request $request){

        $auth_user = \auth()->guard('web')->user();
        $request->merge(['id'=>Common::encrypt_decrypt($request->id,"decrypt")]);
        $statusResponse = Order::giftOfferOtherStatus($request,false,$auth_user);
        if($statusResponse['status']==true){
            session()->flash('success',"order updated successfully.");
            return new JsonResponse(['status'=>'success','action'=>'redirect'],200);
        }else{
            return new JsonResponse(['errors' => $statusResponse['errors']], 422);

        }

    }
    public function manageGiftOfferReturned(Request $request){

        $auth_user = \auth()->guard('web')->user();
        $request->merge(['id'=>Common::encrypt_decrypt($request->id,"decrypt")]);

        $statusResponse = Order::giftOfferReturnManage($request,false,$auth_user);
        if($statusResponse['status']==true){
            session()->flash('success',"Your message has been send to admin successfully.");
            return new JsonResponse(['status'=>'success','action'=>'redirect'],200);
        }else{
            return new JsonResponse(['errors' => $statusResponse['errors']], 422);

        }

    }
}
