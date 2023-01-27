<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GifteeWishList extends BaseModel
{
    protected $table = "giftee_wishlist";
    protected $fillable = [
        'id', 'type', 'title','date','user_id','active'
    ];

    public static function validationRules(){

        $rules['type'] = ['required',Rule::in(["general","registry","experiences","events"])];
        $rules['title'] = [ 'required','max:60'];
        if(request()->input('id') > 0) {
            $rules['date'] = ['nullable', 'date', function ($attr, $value, $fail) {
                if (request()->input('type') != "general" && $value == "") {
                    $fail("Date is required");
                }
            }];
        }else{
            $rules['date'] = ['nullable', 'date', 'after:' . date('Y-m-d'), function ($attr, $value, $fail) {
                if (request()->input('type') != "general" && $value == "") {
                    $fail("Date is required");
                }
            }];
        }

        return $rules;
    }

    public static function validationMsgs(){
        $msgs = [];
        $msgs['title.max'] = "Please enter another wishlist title of 60 characters. This title is too long";

        return $msgs;
    }

    public function user()
    {
        return $this->belongsTo(Website::class, 'user_id', 'id')->where('active','=',1);
    }

    public function wishListItems()
    {
        return $this->hasMany(GifteeWishListItem::class, 'giftee_wishlist_id');
    }

    public static function updateWishListCount ($user_id){
        $count = GifteeWishList::where('user_id',$user_id)->count();
        Website::where('id',$user_id)->update(['wishlist_count'=>$count]);
    }

}