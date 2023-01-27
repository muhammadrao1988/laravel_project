<?php

namespace App\Http\Controllers\Frontend\WishList;

use App\BusinessPersonalLoan;
use App\ContactUs;
use App\Helpers\Common;
use App\HomePage;
use App\Http\Controllers\Controller;
use App\Jobs\WishlistItemAddNotificationJob;
use App\Models\Followers;
use App\Models\GifteeWishList;
use App\Models\GifteeWishListItem;
use App\Models\GiftIdea;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemStatusTimeline;
use App\Models\OrderStatusTimeline;
use App\Models\States;
use App\Models\Website;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Service;
use App\TraitLibraries\ResponseWithHttpStatus;

class ManageWishListController extends Controller
{
    use ResponseWithHttpStatus;
    protected $mainViewFolder = 'frontend.wishlist.';

    //
    public function index(Request $request, $id)
    {
         return view($this->mainViewFolder . 'cart');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function showWishListItems($id)
    {

        $decrypt_id = Common::encrypt_decrypt($id,"decrypt");
        $wishlist_detail = GifteeWishList::with(['user'])->where('id','=',$decrypt_id)->where('active','>',0)->first();
        if(empty($wishlist_detail) || empty($wishlist_detail->user->id) || empty($wishlist_detail->user->active)){
            if(\request()->get('notify')==1 ){
                session()->flash('error', " This item doesn't exist anymore");
                return redirect()->route('home');
            }
            return abort(404);
        }

        $auth_user = \auth()->guard('web')->user();
        $profile_detail = $wishlist_detail->user;
        $same_profile = false;
        $wishlist_privacy_setting = $wishlist_detail->user->privacy_setting;
        if($wishlist_privacy_setting==1){
            $user_permission = false;
        }else{
            $user_permission = true;
        }
        if($auth_user){
            if($wishlist_detail->user_id==$auth_user->id){
                $same_profile = true;
                $user_permission = true;
            }else{
                if(!$user_permission){
                    if(Followers::checkFollower($profile_detail->id,$auth_user->id)){
                        $user_permission = true;
                    }
                }
            }
        }
        if(!$same_profile && $wishlist_privacy_setting && !$user_permission){
            return abort(404);
        }
        $wish_list_items = GifteeWishListItem::where('giftee_wishlist_id','=',$wishlist_detail->id);

        if(\request()->get('sort_by')=="high"){
            $wish_list_items = $wish_list_items->orderByRaw('((IFNULL(price,0) * IFNULL(quantity,0)) + IF(accept_donation=1,IFNULL(shipping_cost,0) + IFNULL(service_fee,0) + IFNULL(tax_rate,0),0)) DESC');
        }else if(\request()->get('sort_by')=="low"){
            $wish_list_items = $wish_list_items->orderByRaw('((IFNULL(price,0) * IFNULL(quantity,0)) + IF(accept_donation=1,IFNULL(shipping_cost,0) + IFNULL(service_fee,0) + IFNULL(tax_rate,0),0)) ASC');
        }else{
            $wish_list_items = $wish_list_items->orderBy('created_at','DESC');
        }

        $wish_list_items = $wish_list_items ->get();
        return view($this->mainViewFolder . 'wishlist_item',compact('auth_user','same_profile','profile_detail','id','wish_list_items','wishlist_detail'));
    }

    public function showWishListItemDetail($id)
    {
        $auth_user = \auth()->guard('web')->user();
        $decrypt_id = Common::encrypt_decrypt($id,"decrypt");
        $wishlist_detail = GifteeWishListItem::with(['wishList'])
            ->where('id','=',$decrypt_id)
            ->where('active','>',0)
            ->where('user_id','=',$auth_user->id)
            ->first();
        if(empty($wishlist_detail) || empty($wishlist_detail->user->id)){
            return abort(404);
        }
        if($wishlist_detail->accept_donation==1) {
            $order_timeline = OrderStatusTimeline::where('order_id', '=', $wishlist_detail->id)->where('type','=','item')->get();
        }else{
            $order_timeline = OrderItemStatusTimeline::where('wishlist_item_id', '=', $wishlist_detail->id)->get();
        }
        $contributions = OrderItem::with(['order','order.fromUser','order.billingInfo'])->where('wishlist_item_id','=',$wishlist_detail->id)->orderBy('updated_at','desc')->get();
        if(empty($contributions)){
            return abort(404);
        }
        $contributions = $contributions->toArray();
        $current_status = $contributions[0]['item_status'];
        $current_status_date = Common::CTL($contributions[0]['created_at']);
        $show_return_form = false;
        $return_day = 0;
        if(strtolower($current_status)=="received" && $contributions[0]['return_description_giftee']==""){
            $delivered_date = $order_timeline->where('status','=','Delivered')->first();
            if(!empty($delivered_date->created_at)){
                $diff = now()->diffInDays(Carbon::parse($delivered_date->created_at));
                if($diff < 25){
                    $show_return_form = true;
                    $return_day = 25 - $diff;
                }
            }
        }

        return view($this->mainViewFolder . 'wishlist_item_detail',compact('return_day','show_return_form','auth_user','wishlist_detail','order_timeline','contributions','current_status','current_status_date'));
    }

    public function saveWishList(Request $request)
    {
        $validatedData = $request->validate(GifteeWishList::validationRules(),GifteeWishList::validationMsgs());
        try {
            $auth_user = auth()->guard('web')->user();
            if($request->input('id') && $request->input('id') > 0){
                $wishList = GifteeWishList::where('id','=',$request->input('id'))->where('user_id','=',$auth_user->id)->first();
                if(empty($wishList)){
                    return new JsonResponse(['errors' => ['save_record' => ['No record found,']]], 422);
                }
                $wishList->update([
                    'title' => $request->input('title'),
                    'type' => $request->input('type'),
                    'name' => $request->input('name'),
                    'user_id' => $auth_user->id,
                    'date' => date('Y-m-d', strtotime($request->input('date')))
                ]);
                $msg = "Wishlist updated successfully.";

            }else {
                $wishList = GifteeWishList::create(
                    [
                        'title' => $request->input('title'),
                        'type' => $request->input('type'),
                        'name' => $request->input('name'),
                        'user_id' => $auth_user->id,
                        'date' => date('Y-m-d', strtotime(empty($request->input('date')) ? date('Y-m-d') : $request->input('date')))
                    ]
                );
                $msg = "Wishlist created successfully.";
            }
            if ($wishList) {
                GifteeWishList::updateWishListCount($auth_user->id);
                $id = Common::encrypt_decrypt($wishList->id,'encrypt');
                session()->flash('success', $msg);
                return new JsonResponse(['success' => true,'id'=>$id,'url'=>route("show.wishlist.item",$id)], 200);
            } else {
                return new JsonResponse(['errors' => ['save_record' => ['Error in save record,']]], 422);

            }
        }catch (\Exception $exception){
            return new JsonResponse(['errors' => ['exception' => [$exception->getMessage()]]], 422);

        }
    }

    public function removeWishList(Request $request)
    {
        try {
            $auth_user = auth()->guard('web')->user();
            if($request->input('id')){
                $id = Common::encrypt_decrypt($request->input('id'),"decrypt");
                $wishList = GifteeWishList::where('id','=',$id)->where('user_id','=',$auth_user->id)->first();
                if(empty($wishList)){
                    return new JsonResponse(['errors' => ['save_record' => ['No record found,']]], 422);
                }
                $wishListItem = GifteeWishListItem::where('giftee_wishlist_id',$id)->where('collected_amount','>',0);
                if($wishListItem->count()==0){
                    $wishList->forceDelete();
                    GifteeWishListItem::where('giftee_wishlist_id',$id)->forceDelete();
                    $msg = "Wishlist deleted successfully.";
                    session()->flash('success', $msg);
                    return new JsonResponse(['success' => true], 200);
                }else{
                    return new JsonResponse(['errors' => ['save_record' => ['Unable to delete wishlist']]], 422);
                }

            }else {
                return new JsonResponse(['errors' => ['save_record' => ['Parameter missing']]], 422);
            }
        }catch (\Exception $exception){
            return new JsonResponse(['errors' => ['exception' => [$exception->getMessage()]]], 422);

        }
    }

    public function saveWishListItem(Request $request)
    {

        $validatedData = $request->validate(GifteeWishListItem::validationRules(),GifteeWishListItem::validationMsgs());

        try {
            DB::beginTransaction();
            $auth_user = auth()->guard('web')->user();
            $wishlist_id = $request->input('giftee_wishlist_id');
            $wishlist_id_decode = Common::encrypt_decrypt($wishlist_id,"decrypt");
            $model = new GifteeWishListItem($request->all());
            $action = "created";
            $redirect = 'eventManager.index';

            if(!empty(@$request->input('id')) && $request->input('id') > 0){
                $model = GifteeWishListItem::where('id',$request->input('id'))->where('giftee_wishlist_id',$wishlist_id_decode)->where('user_id',$auth_user->id)->first();
                if(empty($model->id)){
                    DB::rollBack();
                    return new JsonResponse(['errors'=>['general'=>['The record does not exist in system. Please refresh the page and try again.']]], 422);
                }
                $request->request->remove('type');
                if(empty($request->input('accept_donation'))){
                    $request->merge(['accept_donation'=>0]);
                }
                if($request->accept_donation ==1){
                    $item_total = ($request->quantity * $request->price) + $request->shipping_cost;
                    $service_fee =  $item_total * .1;
                    $tax_rate = States::getTaxValue($auth_user->state,$item_total);
                    $tax_rate = Common::decimalPlace($tax_rate,2);
                    $request->merge(['service_fee'=>$service_fee,'tax_rate'=>$tax_rate]);

                }else{
                    $request->merge(['service_fee'=>0,'tax_rate'=>0]);
                }
                $model->loadModel($request->all());
                $msg = "Record updated successfully.";
                $action = "updated";
            }else{
                $check_wishlist = GifteeWishList::where('id',$wishlist_id_decode)->where('user_id',$auth_user->id)->first();
                if(empty($check_wishlist)){
                    DB::rollBack();
                    return new JsonResponse(['errors'=>['general'=>['The record does not exist in system. Please refresh the page and try again.']]], 422);

                }
                $model->status = "Created";
                if($request->accept_donation ==1){
                    $item_total = ($request->quantity * $request->price) + $request->shipping_cost;
                    $service_fee =  $item_total * .1;
                    $tax_rate = States::getTaxValue($auth_user->state,$item_total);
                    $tax_rate = Common::decimalPlace($tax_rate,2);

                    $model->service_fee = $service_fee;
                    $model->tax_rate = $tax_rate;
                }
                $msg = "Wishlist item added successfully.";
            }
            if ($request->file('picture')) {
                $imagePath = $request->file('picture');
                $ext = $imagePath->getClientOriginalExtension();
                //$imageName = $imagePath->getClientOriginalName();
                $imageName = $wishlist_id."_".date('YmdHis').".".$ext;
                $request->file('picture')->storeAs('uploads/wishlist_item', $imageName, 'public');
                $model->picture = $imageName;
            }
            if(empty($request->expedited_shipping_fee)){
                $model->expedited_shipping_fee = 0.00;
            }
            if(empty($request->shipping_cost)){
                $model->shipping_cost = 0.00;
            }
            $model->giftee_wishlist_id = $wishlist_id_decode;
            $model->user_id = $auth_user->id;

            $model->total_price = $model->quantity * $model->price;
            if (!$model->save()){
                DB::rollBack();
                return new JsonResponse(['errors'=>['general'=>['Error in save record. Please refresh the page and try again.']]], 422);
            }
            if($request->wantsJson()){
                DB::commit();
                if($action=="created"){
                    dispatch(new WishlistItemAddNotificationJob($model))->onQueue('wishlist_notification');
                }
                session()->flash('success', $msg);
                return new JsonResponse(['status'=>'success','action'=>'redirect','url'=>route('show.wishlist.item',$wishlist_id)],200);
            }else{
                return redirect()->route("show.wishlist.item",$wishlist_id);
            }

        }catch (\Exception $exception){
            return new JsonResponse(['errors' => ['exception' => [$exception->getMessage()]]], 422);

        }
    }

    public function addToWishlist(Request $request)
    {

        $validatedData = $request->validate(GifteeWishListItem::validationRules(),GifteeWishListItem::validationMsgs());

        try {
            $model = new GifteeWishListItem($request->all());
            $model->giftee_wishlist_id = Common::encrypt_decrypt($request->giftee_wishlist_id,"decrypt");
            $auth_user = auth()->guard('web')->user();
            $model->user_id = $auth_user->id;
            $model->picture = $request->picture_name;
            $model->total_price = $model->quantity * $model->price;
            if(empty($request->expedited_shipping_fee)){
                $model->expedited_shipping_fee = 0.00;
            }
            if(empty($request->shipping_cost)){
                $model->shipping_cost = 0.00;
            }
            $model->save();
            dispatch(new WishlistItemAddNotificationJob($model))->onQueue('wishlist_notification');


            return response()->json(['status' => true, 'url' => '']);
        } catch (\Exception $e) {

            return new JsonResponse(['errors' => ['exception' => [$e->getMessage()]]], 422);
        }
    }

    public function deleteWishListItem(Request $request){
        if($request->input('id') && $request->input('wishlist_id')){
            $auth_user = auth()->guard('web')->user();
            $wishlist_item_id = $request->input('id');
            $wishlist_id = Common::encrypt_decrypt($request->input('wishlist_id'),'decrypt');
            $record = GifteeWishListItem::where('id','=',$wishlist_item_id)->where('giftee_wishlist_id','=',$wishlist_id)->where('user_id','=',$auth_user->id)->first();
            if(empty($record->id)){
                session()->flash('error', "Unable to delete record due to invalid record");
                return redirect()->route('show.wishlist.item',$request->input('wishlist_id'));
            }
            if($record->collected_amount > 0){
                session()->flash('error', "Unable to delete record due to collection of amount of that wishlist item.");
                return redirect()->route('show.wishlist.item',$request->input('wishlist_id'));
            }
            session()->flash('success', "Record has been deleted successfully.");
            $record->delete();
            return redirect()->route('show.wishlist.item',$request->input('wishlist_id'));
        }
    }

    public function manageWishListReceivedStatus(Request $request){

        $auth_user = \auth()->guard('web')->user();
        $request->merge(['id'=>Common::encrypt_decrypt($request->id,"decrypt")]);
        if($request->is_contributed=="yes"){
            $statusResponse = Order::contributedOtherStatus($request, false, $auth_user);
        }else {
            $statusResponse = Order::cartItemOtherStatus($request, false, $auth_user);
        }
        if($statusResponse['status']==true){
            session()->flash('success',"order updated successfully.");
            return new JsonResponse(['status'=>'success','action'=>'redirect'],200);
        }else{
            return new JsonResponse(['errors' => $statusResponse['errors']], 422);

        }

    }

    public function manageWishListReturned(Request $request){

        $auth_user = \auth()->guard('web')->user();
        $request->merge(['id'=>Common::encrypt_decrypt($request->id,"decrypt")]);
        if($request->is_contributed=="yes"){
            $statusResponse = Order::contributedReturnManage($request, false, $auth_user);
        }else {
            $statusResponse = Order::cartItemReturnManage($request, false, $auth_user);
        }
        if($statusResponse['status']==true){
            session()->flash('success',"Your message has been send to admin successfully.");
            return new JsonResponse(['status'=>'success','action'=>'redirect'],200);
        }else{
            return new JsonResponse(['errors' => $statusResponse['errors']], 422);

        }

    }
}
