<?php

namespace App\Models;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\GifteeWishListItem;

class UserCreditHistory extends Model
{

    protected $table='user_credit_history';
    public $fillable = ['id','debit','credit','order_id','order_item_id','user_id','active','type'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
    public function order_item(){
        return $this->belongsTo(OrderItem::class,'order_item_id');
    }
}
