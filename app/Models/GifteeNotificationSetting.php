<?php

namespace App\Models;
use App\Models\User;

class GifteeNotificationSetting extends BaseModel
{
    protected $table = "giftee_notification_settings";
    protected $fillable = [
        'user_id','notification_type', 'setting_name', 'setting_description','active'
    ];

}