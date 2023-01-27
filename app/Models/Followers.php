<?php

namespace App\Models;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Followers extends BaseModel
{
    protected $table = "followers";
    protected $fillable = ['id', 'user_id', 'follower_id','following_status','active'];

    public function user_detail()
    {
        return $this->belongsTo(Website::class, 'user_id', 'id');
    }

    public function follower_detail()
    {
        return $this->belongsTo(Website::class, 'follower_id', 'id');
    }

    public static function checkFollower($user_id,$follower_id){
        return Followers::where('user_id','=',$user_id)->where('follower_id','=',$follower_id)->where('following_status','=','Accepted')->count();

    }

    public static function notificationSetting($user_id,$setting_name){
       return GifteeNotificationSetting::where('user_id','=',$user_id)->where('setting_name','=',$setting_name)->where('notification_type','=','notification')->count();
    }

    public static function emailNotificationSetting($user_id,$setting_name){
        return GifteeNotificationSetting::where('user_id','=',$user_id)->where('setting_name','=',$setting_name)->where('notification_type','=','email')->count();
    }

    public static function user_followers($user_id){


    }


}