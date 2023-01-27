<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Order;
use App\Models\GifteeWishListItem;

class OrderItem extends Model
{

    protected $table='order_items';
    public $fillable = ['id','order_id','wishlist_item_id','item_name','item_image','item_price','item_shipping_price',
        'item_expedited_shipping','item_expedited_shipping_price','item_qty', 'item_detail','item_merchant','rejected_returned_reason',
        'item_digital_purchase','item_url','active','item_status','giftee_item_specification','return_status_item',
        'return_description_giftee','return_description_admin'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
    public function wishlistItems(){
        return $this->belongsTo(GifteeWishlistItem::class,'wishlist_item_id');
    }
    public function orderItemStatusHistory(){
        return $this->hasMany(OrderItemStatusTimeline::class,'order_item_id');
    }
}
