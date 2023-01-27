<?php

namespace App\Models;
use App\Helpers\Common;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GifteeWishListItem extends BaseModel
{
    protected $table = "giftee_wishlist_items";
    protected $fillable = [
        'id','order_num', 'giftee_wishlist_id', 'type','url','gift_name','price','expedited_shipping_fee',
        'total_price', 'collected_amount', 'quantity','merchant','accept_donation','digital_purchase','tax_rate',
        'gift_details', 'user_id', 'status','purchasing_status','active','shipping_cost','service_fee'
    ];

    public static function validationRules(){

        if(empty(request()->input('id'))) {
            $rules['type'] = ['required', Rule::in(["url", "manual","gift_idea"])];

        }
        $rules['url'] = ['required'];
        $rules['giftee_wishlist_id'] = ['required',function($attr,$value,$fail){
            if(GifteeWishList::where('id','=',Common::encrypt_decrypt($value,'decrypt'))->count() == 0){
                $fail("Invalid wishlist selected");
            }
        }];
        $rules['gift_name'] = [ 'required','max:170'];
        $rules['price'] = [ 'required','numeric','gt:0'];
        $rules['quantity'] = [ 'required','numeric','gt:0'];
        $rules['shipping_cost'] = [ 'nullable','numeric','gte:0'];
        $rules['expedited_shipping_fee'] = [ 'nullable','numeric','gt:shipping_cost'];
        $rules['picture'] = ['nullable',config('constants.IMG_VALIDATION'), config('constants.IMG_VALIDATION_SIZE')];
        return $rules;
    }
    public static function validationMsgs(){
        $msgs = [];
        $msgs['picture.dimensions'] = config('constants.WISHLIST_IMG_ERR_MSG');

        return $msgs;
    }

    public function user()
    {
        return $this->belongsTo(Website::class, 'user_id', 'id')->where('active','=',1);
    }

    public function wishList()
    {
        return $this->belongsTo(GifteeWishList::class, 'giftee_wishlist_id', 'id');
    }

    public function orders(){
        return $this->hasMany(Order::class,'wishlist_item_id','id');
    }

    public function itemStatusHistory(){
        return $this->hasMany(OrderStatusTimeline::class,'order_id')->where('type','=','item');
    }



}