<?php

namespace App\Models;
use App\Helpers\Common;
use App\Models\Category;
use App\Models\User;
use App\Models\GifteeWishlistItem;

use Illuminate\Database\Eloquent\Model;

class ReturnTransaction extends Model
{
    protected $table = 'return_transactions';

    public static $module = "ReturnTransaction";
    protected $fillable = [
        'id',
        'order_id',
        'order_item_id',
        'previous_charge_id',
        'return_id',
        'balance_transaction',
        'payment_intent',
        'subtotal',
        'shipping_fee',
        'taxes',
        'credit_apply',
        'return_amount',
        'wishlist_item_id',
        'active'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    public function order(){
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function orderItem(){
        return $this->belongsTo(OrderItem::class, 'order_item_id', 'id');
    }

}
